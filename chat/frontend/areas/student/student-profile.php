		<?php
		
			require('../../areas/student/student-layout.php');

			require('../../../backend/functions.php');

			validatePageAccess($conn);

		?>

		<head>

			<title>Profile</title>

			<link rel="stylesheet" type="text/css" href="../../css/student/student-profile.css" />

			<script type="text/javascript" src="../../javascript/student/student-profile.js"></script>

		</head>

		<div ng-controller="studentProfile">

			<div ng-repeat="u in myClassMatesAndTeachers" class="panel classmate-and-teacher">

				<div class="panel-body" ng-bind="u.userName" ng-click="addChatWindow(u)"></div>

			</div>

		</div>

	</body>

</html>