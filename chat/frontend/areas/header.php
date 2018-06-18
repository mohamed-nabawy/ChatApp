<nav class="navbar navbar-fixed-top navbar-inverse" style="position: fixed">

	<div class="container-fluid" id="myNavbar">

		<ul class="nav navbar-nav navbar-right">

			<li><a href="#">Home</a></li>

			<li style="cursor: pointer;" ng-click="setAllNotificationsToRead($event)">

				<a class="toggleChats">

					<i class="fa fa-comments" ng-cloak>
						<span ng-show="newLen > 0" ng-bind={{newLen}} class="badge1"></span>
					</i>

				</a>

				<div style="width: 160px;position: absolute;margin-top: -5px;height: 300px;overflow-y: scroll;overflow-x: hidden;" ng-cloak ng-show="lastMessagesClicked == 1" class="panel wrapword" scroll-to-down>

					<!-- the minus sign is for descending order -->
					<div ng-repeat="m in messages | orderBy: '-messageId'" class="mes" ng-click="addChatWindow(m)">
						<div ng-if="m.sentFrom != currentUser.id && m.new == 1" style="background-color: grey">
							<div class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px;"></div>

			        		<div class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px;"></div>

			        	</div>

			        	<div ng-if="(m.sentFrom != currentUser.id && m.new == 0) || m.sentFrom == currentUser.id">
							<div class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px;"></div>

			        		<div class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px;"></div>

			        	</div>

		        		<hr class="horiz" />
		        		
		        	</div>
		        	
		        </div>
				
			</li>

			<li style="cursor: pointer;">

				<a><i class="fa fa-bell"></i></a>

			</li>

			<li>

				<a href="/ChatApp/chat/frontend/logout.php">Logout</a>

			</li>

		</ul>

	</div>

</nav>