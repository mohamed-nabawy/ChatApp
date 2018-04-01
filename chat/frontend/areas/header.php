<link rel="icon" href="../../../favicon.ico"> <!-- url relative to the main page not this template -->

<nav class="navbar navbar-fixed-top navbar-inverse" style="position: relative">

	<div class="container-fluid" style="float: right;margin-right: 130px">

		<ul class="nav navbar-nav">

			<li><a href="#">Home</a></li>

			<li style="cursor: pointer" class="dropdown" ng-click="setAllNotificationsToRead()">

				<a class="dropdown-toggle" data-toggle="dropdown">

					<i class="fa fa-comments" ng-cloak>
						<span ng-show="newLen > 0" ng-bind={{newLen}} class="badge1"></span>
					</i>

				</a>

				<ul class="panel dropdown-menu wrapword" scroll-to-down style="height: 300px;width: 200px;overflow-y: scroll;overflow-x: hidden">

					<div ng-repeat="m in messages | orderBy: 'messageId': true" class="mes" ng-click="addChatWindow({id: m.id, firstName: m.firstName}, m)">
						<div ng-if="m.new == 1 && currentUser.id != m.sentFrom" style="background-color: grey">
							<li class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px"></li>

			        		<li class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px"></li>

			        		<hr/>
		        		</div>
		        		<div ng-if="m.new == 0 || (m.new == 1 && currentUser.id == m.sentFrom)">
							<li class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px"></li>

			        		<li class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px"></li>

			        		<hr/>
		        		</div>
		        		
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