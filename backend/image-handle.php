<?php
    function addImageFile($image, $name, $x1 = null, $y1 = null, $w = null, $h = null) {
        $target_dir = dirname(__DIR__, 1) . "/uploads/";
        $target_file = $target_dir . $name;

        // Check file size
        // if ($image['size'] > 500000) {
        //     echo "Sorry, your file is too large.";
        //     exit();
        // }
        
        $imageFileType = exif_imagetype($image['tmp_name']); // file type (supposed to be image type in our case)
        $imageNameDb = '';
        $croppedImageNameDb = '';

        // Allow certain file formats
        if ($imageFileType != 2 && $imageFileType != 3) {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            exit();
        }

        $uploadFile = '/uploads/' . $name;

        if ($imageFileType == 2) {
            $imageNameDb = $uploadFile . '.jpeg';
            $croppedImageNameDb = $uploadFile . '_crop.jpeg';
        }
        elseif ($imageFileType == 3) {
            $imageNameDb = $uploadFile . '.png';
            $croppedImageNameDb = $uploadFile . '_crop.png';
        }

        if ($x1 === null && $y1 === null && $w === null && $h === null) {
            if ($imageFileType == 2) {
                $c = $target_file . '_crop.jpeg';                
                $c1 = $target_file . '.jpeg';
            }
            elseif ($imageFileType == 3) {
                $c = $target_file . '_crop.png';
                $c1 = $target_file . '.png';
            }

            move_uploaded_file( $image['tmp_name'], $c);
            copy($c, $c1);
        }
        else {
            $croppedImage = $target_file . '_crop';

            if ($imageFileType == 2) { // jpeg
                $croppedImage .= '.jpeg';
                $target_file .= '.jpeg';
            }
            elseif ($imageFileType == 3) { // png
                $croppedImage .= '.png';
                $target_file .= '.png';    
            }

            move_uploaded_file($image['tmp_name'], $croppedImage);
            copy($croppedImage, $target_file);

            // crop image
            // Create our small image
            $new = imagecreatetruecolor(150, 150);
            // Create original image
            $current_image = 0;

            if ($imageFileType == 2) {
                $current_image = imagecreatefromjpeg($croppedImage);
            }
            else {
                $current_image = imagecreatefrompng($croppedImage);
            }

            $whiteBackground = imagecolorallocate($new, 255, 255, 255);
            imagefill($new, 0, 0, $whiteBackground); // fill the background with white

            // resamling (actual cropping)
            imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, 150, 150, $w, $h);
            // creating our new image

            if ($imageFileType == 2) {
                imagejpeg($new, $croppedImage, 95);
            }
            else {
                imagepng($new, $croppedImage, 9);
            }

            imagedestroy($new);
        }
        
        return [$imageNameDb, $croppedImageNameDb];
    }

    function crop($image, $x1, $y1, $w, $h, $type) {
        // crop image
        // Create our small image
        $new = imagecreatetruecolor(150, 150);
        // Create original image
        $current_image = 0;

        if ($type == 2) {
            $current_image = imagecreatefromjpeg($image);
        }
        else {
            $current_image = imagecreatefrompng($image);
        }

        $whiteBackground = imagecolorallocate($new, 255, 255, 255);
        imagefill($new, 0, 0, $whiteBackground); // fill the background with white

        // resampling (actual cropping)
        imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, 150, 150, $w, $h);
        // creating our new image

        if ($type == 2) {
            imagejpeg($new, $image, 95);
        }
        else {
            imagepng($new, $image, 9);
        }

        imagedestroy($new);
    }

    function deleteImageFileName($fileName) {
        if (file_exists(dirname(__DIR__, 1) . $fileName)) {
            unlink(dirname(__DIR__, 1) . $fileName);

            return true;
        }

        return false;
    }

    function handlePictureUpdate($conn, $image, $x1 = null, $y1 = null, $w = null, $h = null) {
        if ($x1 != null || $y1 != null || $w != null || $h != null) {
            if (strlen($image) != 0) { // new image is gonna be uploaded
                $imageData = $image;
                list($imageFileType, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                //$imageFileType = exif_imagetype($image['tmp_name']);

                if ($imageFileType != 'data:image/jpeg' && $imageFileType != 'data:image/png') {
                    echo "Sorry, only JPEG and PNG files are allowed.";
                    exit();
                }

                $ext = pathinfo($_SESSION['image'], PATHINFO_EXTENSION);
                $imageNameDb = '/uploads/' . $_SESSION['email'];
                $croppedImageNameDb = '/uploads/' . $_SESSION['email'] . '_crop';

                if ($imageFileType == 'data:image/jpeg') {
                    $_SESSION['image'] = $imageNameDb . '.jpeg';
                    $_SESSION['croppedImage'] = $croppedImageNameDb . '.jpeg';
                    $f = 0;

                    if ($_SESSION['imageSet'] == 0) {
                        $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}', `imageSet` = 1 where `id` = '{$_SESSION['userId']}'");
                        $_SESSION['imageSet'] = 1;
                        $f = 1;
                    }

                    if ($ext != 'jpeg') {
                        if ($f == 0) {
                            $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '.png'); // remove the old picture
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '_crop.png'); // remove the old cropped
                        }
                    }
                    else {
                        if ($f == 0) {
                            $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '.jpeg'); // remove the old picture
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '_crop.jpeg'); // remove the old
                        }
                    }
                }
                else {
                    $_SESSION['image'] = $imageNameDb . '.png';
                    $_SESSION['croppedImage'] = $croppedImageNameDb . '.png';
                    $f = 0;

                    if ($_SESSION['imageSet'] == 0) {
                        $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}', `imageSet` = 1 where `id` = '{$_SESSION['userId']}'");
                        $_SESSION['imageSet'] = 1;
                        $f = 1;
                    }

                    if ($ext != 'png') {
                        if ($f == 0) {
                            $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '.jpeg'); // remove the old picture
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '_crop.jpeg'); // remove the old cropped
                        }
                    }
                    else {
                        if ($f == 0) {
                            $conn->query("update `users` set `image` = '{$_SESSION['image']}', `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '.png'); // remove the old picture
                            unlink(dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '_crop.png'); // remove the old
                        }
                    }
                }

                //echo $image;
                addBinaryImageFile($imageData, $_SESSION['email'], 1, $x1, $y1, $w, $h);
            }
            else {
                if ($_SESSION['imageSet'] == 1) {
                    $ext = pathinfo($_SESSION['image'], PATHINFO_EXTENSION);
                    $img = dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'];
                    $cropped = dirname(__DIR__, 1) . '/uploads/' . $_SESSION['email'] . '_crop';

                    if ($ext == 'jpeg') {
                        $ext = 2;
                        $img = $img . '.jpeg';
                        $cropped =  $cropped . '.jpeg';
                    }
                    elseif ($ext == 'png') {
                        $ext = 3;
                        $img = $img . '.png';
                        $cropped = $cropped . '.png';
                    }
                    
                    unlink($cropped);
                    copy($img, $cropped);
                    crop($cropped, $x1, $y1, $w, $h, $ext);
                }
                else {
                    $img = dirname(__DIR__, 1) . '/uploads/';
                    $cropped = dirname(__DIR__, 1) . '/uploads/';

                    if ($_SESSION['genderId'] == 1) {
                        $img .= 'maleimage.jpeg';
                    }
                    else {
                        $img .= 'femaleimage.jpeg';
                    }

                    $x = $_SESSION['email'] . '_crop.jpeg';
                    $cropped .= $x;
                    $_SESSION['croppedImage'] = dirname(__DIR__, 1) . '/uploads/' . $x;

                    $conn->query("update `users` set `croppedImage` = '{$_SESSION['croppedImage']}' where `id` = '{$_SESSION['userId']}'");

                    if (file_exists($cropped)) {
                        unlink($cropped);
                    }

                    copy($img, $cropped);
                    crop($cropped, $x1, $y1, $w, $h, 2);
                }
            }
        }
    }

    function editBinaryImage($imageData, $imageAttr, $name, $imageAttr2 = 0, $x1 = null, $y1 = null, $w = null, $h = null) {
        if ($imageAttr != null) {
            if (deleteImageFileName($imageAttr) === true) {
                if ($imageAttr2 !== 0) {
                    deleteImageFileName($imageAttr2);
                }

                return addBinaryImageFile($imageData, $name, $imageAttr2, $x1, $y1, $w, $h);
            }
        }
        else {
            return addBinaryImageFile($imageData, $name, $imageAttr2, $x1, $y1, $w, $h);
        }
    }

    function editImage($imageData, $imageAttr, $name) {
        if ($imageAttr != null) {
            if (deleteImageFileIfExists($imageAttr) == 1) {
                return addImageFile($imageData, $name);
            }
        }
    }

    function addBinaryImageFile($image, $name, $imageAttr2 = 0, $x1 = null, $y1 = null, $w = null, $h = null) {
        //var_dump($image);
        list($type, $imageData) = explode(';', $image);

        list(, $imageData)      = explode(',', $imageData);
        $imageData = base64_decode($imageData);
        $fileName = '';
        $croppedFileName = '';

        if ($imageAttr2 !== 0) { // maybe a user
            $c = dirname(__DIR__, 1) . "/uploads/" . $name;
            $c1 = $c . "_crop";

            if ($type == 'data:image/jpeg') {
                $croppedName = $name . '_crop.jpeg';
                $name = $name . '.jpeg';
                $c .= '.jpeg';
                $c1 .= ".jpeg";
                file_put_contents($c, $imageData);
                $fileName = '/uploads/' . $name;
                $croppedFileName = '/uploads/' . $croppedName;
                copy($c, $c1);
                crop(dirname(__DIR__, 1) . $croppedFileName, $x1, $y1, $w, $h, 2);
            }
            elseif ($type == 'data:image/png') {
                $croppedName = $name . '_crop.png';
                $name = $name . '.png';
                $c .= '.png';
                $c1 .= ".png";
                file_put_contents($c, $imageData);
                $fileName = '/uploads/' . $name;
                $croppedFileName = '/uploads/' . $croppedName;
                copy($c, $c1);
                crop(dirname(__DIR__, 1) . $croppedFileName, $x1, $y1, $w, $h, 3);
            }

            return [$fileName, $croppedFileName];
        }
        else {
            if ($type != 'data:image/jpeg' && $type != 'data:image/png') {
                return "not a valid image type";
                exit;
            }
            else {
                if ($type == 'data:image/jpeg') {
                    $name = $name . '.jpeg';
                }
                elseif ($type == 'data:image/png') {
                    $name = $name . '.png';
                }
            }

            $target_file = dirname(__DIR__, 1) . "/uploads/" . $name;
            file_put_contents($target_file, $imageData);
            $fileName = '/uploads/' . $name;

            return [$fileName];
        }        
    }
?>