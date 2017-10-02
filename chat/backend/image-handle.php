<?php

    function addImageFile($image,$userName, $dirChanged=0) {
    	if ($dirChanged == 0) {
    		chdir("../../.."); // inside php dir
    	}

        $target_dir = "chat/backend/uploads/";
        $target_file = $target_dir . $image['name'] ; // trailing name normalized (validation)
        $uploadOk = 1; // flag to ensure that we are ok to upload
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION); // file type (supposed to be image type in our case)
        
        // Check if image file is a actual image or fake image
        $check = getimagesize( $image['tmp_name'] );
        if ($check !== false) {
            echo "File is an image - ", $check['mime'], ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        //}
        // Check if file already exists
        // if (file_exists($target_file)) {
        //     echo "Sorry, file already exists.";
        //     $uploadOk = 0;
        // }
        // Check file size
        if ($image['size'] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        var_dump($imageFileType);
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file( $image['tmp_name'], $target_file) ) {
                echo "The file ", basename( $image['name']), " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        if ( file_exists('chat/backend/uploads/'. $image['name']) ) {
            rename('chat/backend/uploads/'. $image['name'], 'chat/backend/uploads/'. $userName. ".jpg"); // rename it
        }
        //$imageFileName = str_replace(':', ' ', (string)date("Y-m-d H:i:s")).".jpg";
        //$ifp = fopen($imageFileName, "x+");
        //fwrite($ifp, base64_decode($imageData));
        //fclose($ifp);
        return "/chat/backend/uploads/" . $userName. ".jpg";
    }

    function editImage($imageData, $imageAttr, $userName) {
        $dirChanged = 0;
        if ($imageAttr != null) {
        	if (deleteImageFileIfExists($imageAttr) == 1) {
        		$dirChanged = 1;
        	}
        }
    	return addImageFile($imageData, $userName, $dirChanged);
    }

    function deleteImageFileIfExists($imageAttr) {
    	$imageFileName = basename($imageAttr);
    	chdir("../uploads");
        if (file_exists($imageFileName)) {
          	unlink($imageFileName);
          	return 1;
        }
        else {
        	return 0;
        }
    }

?>