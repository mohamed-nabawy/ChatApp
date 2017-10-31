	
<!DOCTYPE html>

<html>

	<head>
			<title>Profile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="icon" href="/ChatApp/chat/favicon.ico">

		<link rel="stylesheet" type="text/css" href="/ChatApp/chat/frontend/css/modules/bootstrap.css">

		<link rel="stylesheet" type="text/css" href="/ChatApp/chat/frontend/css/student/student-layout.css">

		<link rel="stylesheet" type="text/css" href="/ChatApp/chat/frontend/css/modules/font-awesome.css">

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/modules/jquery-3.2.1.js"></script>

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/modules/angular.js"></script>

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/modules/angular-route.js"></script>

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/modules/location_provider.js"></script>

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/student/student-service.js"></script>

		<script type="text/javascript" src="/ChatApp/chat/frontend/javascript/student/student-layout.js"></script>

	<link rel="stylesheet" type="text/css" href="../../css/student/student-profile.css" />

	<script type="text/javascript" src="../../javascript/student/student-profile.js"></script>

	</head>

	<body style="background-image: url('')" ng-app="student">

		<?php require('../header.php'); ?>
		<?php require('../chats.php'); ?>

		
		<?php
			require('../../../backend/functions.php');
			validatePageAccess($conn);
		?>


		<div ng-controller="studentProfile" class="ChatmatesPanel">
			<h4 onclick="$(this).parent().toggleClass('hideShowContacts');" class="clickable">Contacts</h4>

			<div ng-repeat="u in myClassMatesAndTeachers" class="panel classmate-and-teacher">

				<label class="panel-body clickable" ng-bind="u.userName" ng-click="addChatWindow(u)"></label>

			</div>

		</div>

	</body>

</html>