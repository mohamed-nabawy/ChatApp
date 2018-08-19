<div ng-controller="chats">

	<div ng-cloak class="container-fluid">

		<div class="nav navbar-nav chats" ng-repeat="c in chats" style="margin-right: 50px" ng-click="removefromNewMessagesIfAny(c)">

			<div class="wrapper">

				<div class="chat-head" ng-click="showHideChat($index)" ng-class="{hideShowChats: c.open == 0}">

					<span class="phone" title="call" ng-click="call(c)"><i class="fa fa-phone"></i></span>

					<span ng-bind="c.firstName" class="name-position"></span>

					<span class="close-chat-window" ng-click="closeWindow(c.secondUserId)" title="close">x</span>
				
				</div>

				<div id="chat{{c.secondUserId}}" class="chat-window" scroll-to-top="1">

					<li ng-repeat="m in c.messages | orderBy: '-id'" class="each-message">

						<div ng-if="m.sentFrom == c.secondUserId" style="background-color: grey;border: 2px solid grey;left: 10px;position: relative;text-align: center;max-width: 125px" class="message-content">

							<div ng-bind="m.content"></div>

						</div>

						<div ng-if="m.sentTo == c.secondUserId" style="background-color: mediumblue;border: 2px solid mediumblue;left: 60px;text-align: center;max-width: 125px" class="message-content">

							<div ng-bind="m.content"></div>
							
						</div>

					</li>

				</div>

				<div>

					<textarea type="text" class="message-input" ng-model="c.message" send-button placeholder="send a message..."></textarea>

				</div>

				<div>

					<input type="submit" value="send" class="btn btn-primary send-button" ng-click="sendMessageToUser(c)" />

				</div>

			</div>

		</div>

	</div>

</div>