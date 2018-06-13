// layoutApp.directive('message', ['$timeout', '$rootScope', function($timeout, $rootScope) {
// 	return {
// 		link: function(scope, elem) {
// 			if (scope.$parent.$last && scope.$last && scope.$parent.load == 1) { // only on load
// 				$timeout(function() {
// 					var z = $('.chat-window'); // chat window
// 					var y = $('.chat'); // chat window and the frame (close, name of chat user)
// 					var yLen = y.length;
// 					var zLen = z.length;
					
// 					// for (var i = 0; i < zLen; i++) {
// 					// 	z[i].scrollTop = z[i].scrollHeight - z[i].clientHeight; // scroll all chats to top
// 					// }

// 					// we control the visibility of chat not the display
// 					// because we want the content in the page so we can scroll down
// 					// and then we show it (if there is no content, we won't be able to scroll down)
// 					for (var i = 0; i < yLen; i++) {
// 						y[i].style.visibility = 'visible'; // make them all visible
// 					}

// 					//scope.chats = scope.chats.filter(function(e) {return e;});

// 					$('.view-them')[0].style.visibility = 'visible'; // then make their container visible

// 					scope.$parent.load = 0; // flag (cancel)

// 					//$rootScope.$broadcast('finished');
// 				}, 1700);
// 			}
// 		}
// 	}
// }]);