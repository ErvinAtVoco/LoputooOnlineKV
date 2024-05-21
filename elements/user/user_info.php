<?php
function update_user_info()
{

    global $wpdb;
    $id = get_current_user_id();
    $user_data = stripslashes($_POST['wp_data']);
    $user_data = json_decode($user_data);
    $user_data->ID = (int)$id;

    wp_update_user($user_data);

    $acf_data = stripslashes($_POST['acf_data']);
    $acf_data = json_decode($acf_data);
    $isikukood = isset($acf_data->isikukood) ? intval($acf_data->isikukood) : null;
    $telefon = isset($acf_data->telefon) ? intval($acf_data->telefon) : null;

    // Check what values have been changed if any
    if (is_int($isikukood) && !empty($isikukood) && $isikukood !== $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'isikukood' AND user_id = %d", $id))) {
        $sql = $wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'isikukood' AND user_id = %d", $isikukood, $id);
        $wpdb->query($sql);
    }
    // Check what values have been changed if any
    if (is_int($telefon) && !empty($telefon) && $telefon !== $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'telefon' AND user_id = %d", $id))) {
        $sql = $wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'telefon' AND user_id = %d", $telefon, $id);
        $wpdb->query($sql);
    }

    // Create user upload path
    $upload_dir = wp_upload_dir();
    $user_file_path = $upload_dir['basedir'] . '/' . 'users' . '/' . 'user-' . $id;

    // Handle new profile picture upload
    $avi_data = $_FILES['avi_data'];
    error_log(json_encode($avi_data));
    $profile_picture_id = NULL;

    if (is_array($avi_data['name'])) {
		$profile_picture_image = array(
			'name'     => $avi_data['name'][0],
			'type'     => $avi_data['type'][0],
			'tmp_name' => $avi_data['tmp_name'][0],
			'error'    => $avi_data['error'][0],
			'size'     => $avi_data['size'][0]
		);
		
		$profile_picture_id = process_uploaded_image($profile_picture_image, $user_file_path);
	};

    error_log($profile_picture_id);

    update_user_meta($id, 'profile_picture', $profile_picture_id);

    wp_die();
}

/* // Copied process_uploaded_file and user_dir_filter from custom_form_submit.php... One day should make single image upload handler somewhere... I fear wordpress inheritance (it is probably not that hard)
function process_uploaded_avi($file_array, $file_path) {
	// Get user upload dir
	$user_upload_dir = $file_path;
	
	// Create upload overrides for wp_handle_upload function
	$upload_overrides = array(
        'test_form' => false,
		'mimes' => array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png' => 'image/png'
		),
    );
	
    // Handle file upload with wp function
   	add_filter('upload_dir', 'user_avi_dir_filter');
	$move_file = wp_handle_upload($file_array, $upload_overrides);
	remove_filter('upload_dir', 'user_avi_dir_filter');
	
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
};

function user_avi_dir_filter($dirs) {
	$user_id = get_current_user_id();
	$upload_path = '/' . 'users' . '/' . 'user-' . $user_id;
	
	$dirs['subdir'] = $upload_path;
    $dirs['path'] = $dirs['basedir'] . $upload_path;
    $dirs['url'] = $dirs['baseurl'] . $upload_path;

    return $dirs;
}; */