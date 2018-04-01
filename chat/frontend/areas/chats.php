<nav id="nav" class="navbar navbar-fixed-bottom view-them" ng-controller="chats" style="visibility: hidden">

	<div class="container-fluid" style="float: right">

		<div class="nav navbar-nav separate-between-windows chat" ng-repeat="c in chats | orderBy: 'i' " style="visibility: hidden" ng-cloak ng-click="removefromNewMessagesIfAny(c.id)">

			<div class="wrapper">

				<div class="chat-head">

					<span ng-bind="c.firstName" class="name-position"></span>

					<span class="close-chat-window" ng-click="closeWindow(c.id)" title="close">x</span>
				
				</div>

				<div id="chat{{c.id}}" class="chat-window" scroll-to-top="1">

					<li ng-repeat="m in c.messages | orderBy: 'id' " class="each-message" message>

						<span ng-if="m.sentFrom == c.id" class="message-content" ng-bind="m.content" style="background-color: grey;border: 2px solid grey;left: 10px"></span>

						<span ng-if="m.sentTo == c.id" class="message-content" ng-bind="m.content" style="background-color: mediumblue;border: 2px solid mediumblue;left: 60px"></span>

					</li>

				</div>

				<div>

					<textarea type="text" class="message-input" ng-model="c.message" send-button placeholder="send a message..." ></textarea>

				</div>

				<div>

					<input type="submit" value="send" class="btn btn-primary send-button" ng-click="sendMessageToUser(c)" />

				</div>

			</div>

		</div>

	</div>

</nav>