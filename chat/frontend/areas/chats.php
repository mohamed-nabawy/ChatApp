<nav id="navv" class="navbar navbar-fixed-bottom view-them" ng-controller="chats" style="visibility: hidden">

	<div class="container-fluid" style="float: right">

		<div class="nav navbar-nav separate-between-windows chat" ng-repeat="c in chats" ng-cloak last>

			<div class="wrapper">

				<div  class="chat-head" ng-click="changeCurrentChatUser(c)">

					<span ng-bind="c.firstName" class="name-position"></span>

					<span class="close-chat-window" ng-click="closeWindow(c)" title="close">x</span>
				
				</div>	

				<div class="chat-window" scroll-to-top="{{c.id}}">

					<li ng-repeat="m in getCurrentMessages(c.id) | orderBy: 'id' " class="each-message" finished>

						<span ng-if="m.sentFrom == c.id" class="message-content" ng-bind="m.content" style="background-color: grey;border: 2px solid grey;right: 50px"></span>

						<span ng-if="m.sentTo == c.id" class="message-content" ng-bind="m.content" style="background-color: mediumblue;border: 2px solid mediumblue;left: 50px"></span>

					</li>

				</div>

				<div>

					<textarea type="text" class="message-input" ng-model="c.message" placeholder="send a message..." ></textarea>

				</div>

				<div>

					<input type="submit" value="send" class="btn btn-primary send-button" ng-click="sendMessageToUser(c)" />

				</div>

			</div>

		</div>

	</div>

</nav>