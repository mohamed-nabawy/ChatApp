<!DOCTYPE html>

<html>

	<head>

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

	</head>

	<body style="background-image: url('')" ng-app="student">

		<nav class="navbar navbar-fixed-top navbar-inverse" style="position: relative;">

			<div class="container-fluid" style="float: right;">

				<ul class="nav navbar-nav">

					<li><a href="#">Home</a></li>

					<li style="cursor: pointer;">

						<a><i class="fa fa-comments"></i></a>

					</li>

					<li style="cursor: pointer;">

						<a><i class="fa fa-bell"></i></a>

					</li>

					<li><a href="/ChatApp/chat/frontend/logout.php">Logout</a></li>

				</ul>

			</div>

		</nav>

		<nav class="navbar navbar-fixed-bottom" ng-controller="chats">

			<div class="container-fluid" style="float: right;">

				<div class="nav navbar-nav" ng-repeat="c in chats" style="position: relative;right: 180px;top: -15px" ng-cloak>

					<!-- <li  style="cursor: pointer;width: 200px;height: 20px;background-color: lightblue;position: relative;right: -100px;top: 10px" ng-click="changeCurrentChatUser(c)">

						<span ng-bind="c.firstName" class="name-position"></span>

						<span class="fa fa-close close-chat-window" ng-click="closeWindow(c)" title="close"></span>
						
					</li> -->

					<li>

						<input type="text" class="message-input" ng-model="c.message" placeholder="send a message..." />

					</li>

					<li>

						<input type="submit" value="send" class="btn btn-primary send-button" ng-click="sendMessageToUser(c)" />

					</li>

					<ul class="chat-window">

						<li ng-repeat="m in currentMessages | orderBy: 'm.id'" class="each-message">

							<span ng-if="m.sentFrom == c.id" class="message-content" ng-bind="m.content" style="background-color: grey;border: 2px solid grey;right: 40px"></span>

							<span ng-if="m.sentTo == c.id" class="message-content" ng-bind="m.content" style="background-color: mediumblue;border: 2px solid mediumblue;left: 50px"></span>

						</li>

					</ul>

					<span class="separate-between-windows"></span>

				</div>

			</div>

		</nav>