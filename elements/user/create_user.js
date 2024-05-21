// If cookie IS REAL! then user is logged in and we dont need to show register form
if(cookie){
   document.getElementById('kasutaja-loomine').style.display = 'none';
}

// Ajax user creation
jQuery(function ($) {
	$('#uus-kasutaja-form').on('submit', function(e) {
		e.preventDefault();
		
		// Check Regex
		 if (!emailPattern.test($('#email').val()) || !indexPattern.test($('#telefon').val()) || !freeTextPattern.test($('#nimi').val())) {
            return;
        }
		
		// Create a formdata object
		var userData = new FormData(this);
				
		// Append ajax action
		userData.append('action', 'create-user');
		
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
});