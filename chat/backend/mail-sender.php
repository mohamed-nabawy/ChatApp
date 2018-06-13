<?php
	require(__DIR__ . '/lib/vendor/autoload.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	function sendMail($email, $user_id, $phoneNumber) {
		try {
			$acc = hash("sha256", $user_id, false);
			$hashKey = hash("sha256", $phoneNumber . $user_id, false);
			//send confirm mail
			$mail = new PHPMailer(true);                          // Passing `true` enables exceptions
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = "smtp.gmail.com";                       // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'mostafaelsayed9419@gmail.com';     // SMTP username
			$mail->Password = 'nacxgewvqqhvydoa';                 // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('mostafaelsayed9419@gmail.com', 'Cafeteria App');
			$mail->addAddress($email, "");
			$mail->Subject = "Cafeteria App Info Confirm";
			$bodyHref = "http://127.0.0.1/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/infoConfirm.php?acc=" . $acc . "&hashKey=" . $hashKey . "&userId=" . $user_id;
			$mail->Body = '<p>thank you for joining us, click on <a href=' . $bodyHref . '>this</a> to confirm</p>';
			$mail->IsHTML(true);

			// only on localhost
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		    
			$result = $mail->Send();
		}

		catch (phpmailerException $e) {
			die(var_dump($e->errorMessage()));
		}

		catch (Exception $e) {
			die(var_dump($e->getMessage()));
		}
	}
?>