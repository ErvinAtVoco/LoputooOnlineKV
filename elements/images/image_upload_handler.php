<?php

/**
 * Turns incoming files into WordPress attachments and returns the attachment ID
 *
 * @param array $file_array containing the data of the file uploaded through the front-end
 * 
 * @author Ervin
 * @return int the ID for the created attachment
 */ 

function process_uploaded_image($file_array)
{
	// Create upload overrides for wp_handle_upload function
	$upload_overrides = array(
		'test_form' => false,
		'mimes' => array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png' => 'image/png'
		),
	);

	// Handle file upload with wp function
	add_filter('upload_dir', 'user_dir_filter');
	$move_file = wp_handle_upload($file_array, $upload_overrides);
	remove_filter('upload_dir', 'user_dir_filter');

	// Create target_path
	$target_path = $move_file['file'];

	// Turn file into attachment
	$file_attachment = [
		"post_mime_type" => $move_file['type'],
		"post_title" => basename($target_path),
		"post_content" => "",
		"post_status" => "inherit",
	];

	$file_attachment_id = wp_insert_attachment(
		$file_attachment,
		$target_path
	);

	require_once ABSPATH . "wp-admin/includes/image.php";
	$file_attachment_data = wp_generate_attachment_metadata(
		$file_attachment_id,
		$target_path
	);

	wp_update_attachment_metadata(
		$file_attachment_id,
		$file_attachment_data
	);

	return $file_attachment_id;
}

function user_dir_filter($dirs) {
    $user_id = get_current_user_id();
 	$upload_path = '/' . 'users' . '/' . 'user-' . $user_id;
	
 	$dirs['subdir'] = $upload_path;
    $dirs['path'] = $dirs['basedir'] . $upload_path;
    $dirs['url'] = $dirs['baseurl'] . $upload_path;

    return $dirs;
}