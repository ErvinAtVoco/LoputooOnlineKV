<?php

add_action('user_register', 'create_user_dir', 10, 2);

function create_user_dir($user_id, $userdata) {
	wp_mkdir_p(WP_CONTENT_DIR . '/uploads/users/user-' . $user_id);
}