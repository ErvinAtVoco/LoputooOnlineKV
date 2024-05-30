// If cookie IS REAL! then user is logged in and we dont need to show register form
if (isUserLoggedIn()) {
	document.getElementById('uus-kasutaja-form').style.display = 'none';
}

function createUser() {

	let userCreateForm = document.getElementById('uus-kasutaja-form');
	console.log(userCreateForm);
	console.log(isUserLoggedIn());

	
	// Create a formdata object
	var userData = new FormData(userCreateForm);

	// Append ajax action
	userData.append('action', 'create-user');

	jQuery(function ($) {
		$.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data: userData,
			processData: false,
			contentType: false,
			success: function () {
				userConfirmed = true;
				userCreateForm.style.display = 'none';
			},
			error: function (xhr, status, error) {
				let errorMsg = `${JSON.parse(xhr.responseText).data}`;
				document.getElementById('error-result').innerHtml = "";
				let textNode = document.createTextNode(errorMsg);
				errorContainer.appendChild(textNode);
			}
		});
	});
}

// Ajax user creation
