// If cookie IS REAL! then user is logged in and we dont need to show register form
if (cookie) {
	document.getElementById('kasutaja-loomine').style.display = 'none';
}

function createUser() {

	let userCreateForm = document.getElementById('uus-kasutaja-form');
	console.log(userCreateForm);

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
			success: function (response) {
				userConfirmed = true;
				console.log(response);
				$('#uus-kasutaja-form').hide();
			},
			error: function (xhr, status, error) {
				//  Show error from backend
			}
		});
	});
}

// Ajax user creation
