<?php 
	require(dirname(__DIR__, 3) . '/backend/functions.php');
	validatePageAccess($conn)
?>

<!DOCTYPE html>

<html>

	<head>

		<title>Profile</title>

		<!-- if run with ie 10 compatible -->
		<meta http-equiv="X-UA-Compatible" name="viewport" content="IE=11,width=device-width,initial-scale=1.0">

		<link rel="icon" href="/chat/favicon.ico">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/modules/bootstrap.css">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/student/student-layout.css">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/modules/font-awesome.css">

		<script type="text/javascript" src="/chat/frontend/javascript/modules/jquery-3.2.1.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/modules/bootstrap.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/modules/angular.js"></script>

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/student/student-profile.css" />
		
	</head>

	<body style="background-image: url('')" ng-app="student" ng-controller="studentProfile">

		<?php
			readfile(__DIR__ . '/../header.php');
		?>

		<div style="position: fixed;bottom: 0">

			<div class="ChatmatesPanel" style="display: inline-block;">
				
				<h4 onclick="$(this).parent().toggleClass('hideShowContacts');" class="clickable">Contacts</h4>

				<div ng-repeat="u in myClassMatesAndTeachers" class="panel classmate-and-teacher">

					<label class="panel-body clickable" ng-bind="u.email" ng-click="addChatWindow(u)"></label>

				</div>

			</div>

			<div style="display: inline-block;" class="thechats">
				<?php
					readfile(__DIR__ . '/../chats.php');
				?>
			</div>

		</div>

		<script type="text/javascript" src="/chat/frontend/javascript/student/student-layout.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/student/student-profile.js"></script>

	</body>

</html>