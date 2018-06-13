<link rel="icon" href="../../../favicon.ico"> <!-- url relative to the main page not this template -->

<nav class="navbar navbar-fixed-top navbar-inverse" style="position: relative">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>

	<div class="container-fluid collapse navbar-collapse" id="myNavbar">

		<ul class="nav navbar-nav navbar-right">

			<li><a href="#">Home</a></li>

			<li style="cursor: pointer" class="dropdown" ng-click="setAllNotificationsToRead()">

				<a class="dropdown-toggle" data-toggle="dropdown">

					<i class="fa fa-comments" ng-cloak>
						<span ng-show="newLen > 0" ng-bind={{newLen}} class="badge1"></span>
					</i>

				</a>

				<ul class="panel dropdown-menu wrapword" scroll-to-down style="height: 300px;width: 200px;">

					<!-- the dash is for descending order -->
					<div ng-repeat="m in messages | orderBy: '-messageId'" class="mes" ng-click="addChatWindow(m)">
						<div ng-if="m.sentFrom != currentUser.id && m.new == 1" style="background-color: grey">
							<li class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px"></li>

			        		<li class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px"></li>

			        	</div>

			        	<div ng-if="(m.sentFrom != currentUser.id && m.new == 0) || m.sentFrom == currentUser.id">
							<li class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px"></li>

			        		<li class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px"></li>

			        	</div>

		        		<hr/>
		        		
		        	</div>
		        	
		        </ul>
				
			</li>

			<li style="cursor: pointer">

				<a><i class="fa fa-bell"></i></a>

			</li>

			<li>

				<a href="../../logout.php">Logout</a>

			</li>

		</ul>

	</div>

</nav>