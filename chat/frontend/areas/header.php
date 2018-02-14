<link rel="icon" href="../../../favicon.ico"> <!-- url relative to the main page not this template -->

<nav class="navbar navbar-fixed-top navbar-inverse" style="position: relative">

	<div class="container-fluid" style="float: right;margin-right: 130px">

		<ul class="nav navbar-nav">

			<li><a href="#">Home</a></li>

			<li style="cursor: pointer" class="dropdown">

				<a class="dropdown-toggle" data-toggle="dropdown">

					<i class="fa fa-comments"></i>

				</a>

				<ul class="panel dropdown-menu wrapword" style="height: 300px;width: 200px;overflow-y: scroll;overflow-x: hidden">

					<div ng-repeat="m in messages" class="mes" ng-click="addChatWindow(m)">

						<li class="panel-heading" ng-bind="m.firstName" style="text-align: center;margin-left: 20px"></li>

		        		<li class="panel-body" ng-bind="m.content" style="text-align: center;margin-left: 20px;font-size: 8px"></li>

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