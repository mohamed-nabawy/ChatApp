function sendRequest(methodName, url, data=null) {
	var request;

	if (window.XMLHttpRequest) { // modern browser
		request = new XMLHttpRequest();
	}
	else { // old IE browser
		request = new ActiveXObject('Microsoft.XMLHTTP');
	}

	request.open(methodName, url, true);

	if (methodName == 'get') {
		request.responseType = 'json';
	}

	if (methodName == 'put') {
		request.setRequestHeader('Content-type', 'application/json');
	}

	if (data == null) {
		request.send(); // get and delete
	}
	else {
		request.send(data); // post and put
	}

	return request;
}

function getUsers() {
	var request = sendRequest('get', '/chat/backend/requests/user.php');

	request.onload = function() {
		console.log(request.response);
	};
}

function editUser(data) {
	var request = sendRequest('put', '/chat/backend/requests/user.php', data);

	request.onload = function() {
		console.log(request.response);
	};
}

function deleteUser(id) {
	var request = sendRequest('delete', '/chat/backend/requests/user.php?userId=' + id);

	request.onload = function() {
		console.log(request.response);
	};
}

function getErrorElements() {
	return document.getElementsByClassName('error');
}

function getInputElements() {
	return document.getElementsByTagName('input');
}

function getFormElement() {
	return document.forms['myform'];
}

getFormElement().onfocusout = function(e) {
	var errorElements = getErrorElements();
	var len = errorElements.length;
	var z = e.target.value;

	for (var i = 0; i < len; i++) {
		var x = e.target.attributes[1].value;
		var y = errorElements[i].attributes[1].value;
		if (x == y && z == "") {
			errorElements[i].style.visibility = 'visible';
		}
		else if (x == y && z != "") {
			errorElements[i].style.visibility = 'hidden';
		}
	}
}

getFormElement().onfocusin = function(e) {
	e.target.onkeydown = function() {
		var errorElements = getErrorElements();
		var len = errorElements.length;
		var z = e.target.value;

		for (var i = 0; i < len; i++) {
			var x = e.target.attributes[1].value;
			var y = errorElements[i].attributes[1].value;

			if (x == y && z != "") {
				errorElements[i].style.visibility = 'hidden';
			}
		}
	}
}

function checkInput() {
	var inputElements = getInputElements();
	var len = inputElements.length;

	for (var i = 0; i < len; i++) {
		if (inputElements[i].value == "" && inputElements[i].attributes[1].value != "file") {
			return false;
		}
	}
}

(document.getElementsByName('submit')[0]).onclick = function(e) {

	return checkInput();
};

//editUser();
//deleteUser();
//getUsers();