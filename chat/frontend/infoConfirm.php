<?php
	require_once(dirname(__DIR__) . '/backend/session.php');
	require_once(dirname(__DIR__) . '/backend/connection.php');
	require(dirname(__DIR__) . '/backend/controllers/users.php');

	//{
		//echo nl2br("Account\r\Activated\r\n!");
		//from user
		$acc = $_GET['acc'];
		$hashKey = $_GET['hashKey'];
		$userId = $_GET['userId'];

		//from db
		//userId = $_SESSION['userId'];
		$user = getUserById($conn, $userId);
	 	$acc2 = hash("sha256", $userId, false);
	 	$hashKey2 = hash("sha256", $user['phoneNumber'] . $userId, false);
		
		if ($acc === $acc2 && $hashKey === $hashKey2) {
			if ( activateUser($conn, $userId) ) {
				// $_SESSION['userId']    = $userId;
	   //          $_SESSION['email']  = $user['Email'];
	   //          $_SESSION['roleId']    = $user['RoleId'];
	   //          $_SESSION['langId']    = $user['LocaleId'];
	   //          $_SESSION['image']     = $user['Image'];
	   //          $_SESSION['croppedImage']     = $user['CroppedImage'];
	   //          $_SESSION['genderId'] = $user['GenderId'];
	   //          $_SESSION['imageSet'] = $user['ImageSet'];
	   //          $_SESSION['notifications'] = [];

	   //          if ( (!$_SESSION['orderId'] = getOpenOrderByUserId($conn)['Id']) && $_SESSION['roleId'] == 2 ) {
    //             	// if not found open order>>open a new one
    //             	$_SESSION['orderId'] = addOrder($conn, date('Y-m-d h:m'), 1, 1, $_SESSION['userId']);
    //         	}

    //         	header( "Location:" . "/CafeteriaApp/CafeteriaApp.Frontend/Public/categories.php");
				echo "<h1>Your Account is Confirmed Successfully</h1>";
					
			}
		}
		else {
			echo "<h2>Activation Failed !. please, contact customer care.</h2>";
		}
	//}
	//else {
	//	var_dump($_SESSION);
	//	echo "error";
	//}

	//echo 0 === null;
	//echo 0 == null; // false true
	//print_r(json_encode( array("Id"=>5, "Duration"=>50) ) )
?>