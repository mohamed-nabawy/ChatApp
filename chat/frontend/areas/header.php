<link rel="icon" href="/ChatApp/chat/favicon.ico"> <!-- url relative to the main page not this template -->

<nav class="navbar navbar-fixed-top navbar-inverse" style="position: relative">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>


		<ul class="nav navbar-nav navbar-right">

			<li><a href="#">Home</a></li>



					<i class="fa fa-comments" ng-cloak>
						<span ng-show="newLen > 0" ng-bind={{newLen}} class="badge1"></span>
					</i>

				</a>


					<div ng-repeat="m in messages | orderBy: '-messageId'" class="mes" ng-click="addChatWindow(m)">
						<div ng-if="m.sentFrom != currentUser.id && m.new == 1" style="background-color: grey">


			        	</div>

			        	<div ng-if="(m.sentFrom != currentUser.id && m.new == 0) || m.sentFrom == currentUser.id">


			        	</div>

		        		
		        	</div>
		        	
				
			</li>


				<a><i class="fa fa-bell"></i></a>

			</li>

			<li>

				<a href="/ChatApp/chat/frontend/logout.php">Logout</a>

			</li>

		</ul>

	</div>

</nav>