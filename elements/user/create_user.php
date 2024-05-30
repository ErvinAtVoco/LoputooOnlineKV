<?php

function create_user_from_form() 
{
    global $email_pattern, $index_pattern, $free_text_pattern;

	// Check if form is valid with nonce
	if (!isset($_POST['user_nonce']) || !wp_verify_nonce($_POST['user_nonce'], 'user_nonce_action')) {
        // Handle the case where the nonce is not valid
        wp_send_json_error('Sisselogimise sisu on muudetud', 401); 
        wp_die();
    };

    if(!check_regex_of_array([$_POST['nimi']], $free_text_pattern, true)) {
        wp_send_json_error('Kontrollige, et nimi oleks korrektselt sisestatud!', 406);
    }

    if(!check_regex_of_array([$_POST['email']], $email_pattern, true)) {
        wp_send_json_error('Kontrollige, et email oleks korrektselt sisestatud!', 406);
    }
    
    if(!check_regex_of_array([$_POST['telefon']], $index_pattern, true)) {
        wp_send_json_error('Kontrollige, et telefon oleks korrektselt sisestatud!', 406);
    }
    
    
		
	$user_id = wp_create_user($_POST['nimi'], wp_generate_password(), $_POST['email']);
	
	// If we get an error from creationg user we need to send that and kill wp function
	if (is_wp_error($user_id)) {
        wp_send_json_error('Probleem kasutaja loomisega: ' . $user_id->get_error_message(), 500);
        wp_die();
    }
	
	// Log the user in
	$user = get_user_by('id', $user_id);
    wp_set_current_user($user_id, $user->user_login);
    wp_set_auth_cookie($user_id, true);
    do_action('wp_login', $user->user_login, $user);

    wp_send_json_success('Kasutaja edukalt sisse logitud');
	
	wp_die();
}