<?php

/**
 * Register/enqueue custom scripts and styles
 */
add_action('wp_enqueue_scripts', function () {
  // Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
  if (!bricks_is_builder_main()) {
    wp_enqueue_style('bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime(get_stylesheet_directory() . '/style.css'));
  }
});

/**
 * Register custom elements
 */
add_action('init', function () {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ($element_files as $file) {
    \Bricks\Elements::register_element($file);
  }
}, 11);

/**
 * Add text strings to builder
 */
add_filter('bricks/builder/i18n', function ($i18n) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__('Custom', 'bricks');

  return $i18n;
});

function get_json()
{
	$data = file_get_contents(__DIR__ . '/elements/form/eesti.json');
	echo $data;
}


// Require PHP scripts

require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/subscriptions/handle_subscription.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/credits/handle_credits.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/posts/handle_post_highlight.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/images/image_upload_handler.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/filter/filter_form.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/filter/filter_form_handler.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/listings/show_listings.php';
require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/backend_error_handling/error_handling.php';

// Setup JavaScript and Jquery scripts based on page
function enqueue_scripts()
  {
    if (is_page('test-post-preview')) {

      require_once('elements/form/custom_form.php');
      // Require jquery library
      wp_enqueue_script('jquery');

      // Get js
      wp_enqueue_script('global-var-js', get_stylesheet_directory_uri() . '/elements/global_var.js', array('jquery'), null, true);
      wp_enqueue_script('custom-form-js', get_stylesheet_directory_uri() . '/elements/form/custom_form.js', array('jquery'), null, true);
      wp_enqueue_script('create-user-js', get_stylesheet_directory_uri() . '/elements/user/create_user.js', array('jquery'), null, true);
      wp_enqueue_script('form-errors-js', get_stylesheet_directory_uri() . '/elements/form/form_errors.js', array('jquery'), null, true);
      wp_enqueue_script('make-preview-js', get_stylesheet_directory_uri() . '/elements/form/make_preview.js', array('jquery'), null, true);
      // Localize the script with the AJAX URL
      wp_localize_script('custom-form-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
      wp_localize_script('create-user-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

  if (is_page('user-interface')) {
    require_once('elements/user/user_interface.php');

    wp_enqueue_script('jquery');

    wp_enqueue_script('show-posts-js', get_stylesheet_directory_uri(  ) . '/elements/user/show_posts.js', array('jquery'), null, true);
    wp_enqueue_script('user-info-js', get_stylesheet_directory_uri(  ) . '/elements/user/user_info.js', array('jquery'), null , true);
    wp_enqueue_script('edit-post-js', get_stylesheet_directory_uri(  ) . '/elements/user/edit_post.js', array('jquery'), null , true);

    wp_localize_script(
      'show-posts-js',
      'myAjax',
      array('ajaxurl' => admin_url('admin-ajax.php'),
            'ajaxNonce' => wp_create_nonce('validation'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'baseURL' => get_rest_url()),
    );
    wp_localize_script(
      'user-info-js',
      'myAjax',
      array('ajaxurl' => admin_url('admin-ajax.php'),
            'ajaxNonce' => wp_create_nonce('validation')),
    );
    wp_localize_script(
      'edit-post-js',
      'myAjax',
      array('ajaxurl' => admin_url('admin-ajax.php'),
            'ajaxNonce' => wp_create_nonce('validation')),
    );
  };

  if (is_page('edit-post')){
    require_once('elements/user/edit_post.php');
    require_once('elements/user/edit_post_template.php');
	
	wp_enqueue_script('edit-post-handler-js', get_stylesheet_directory_uri(  ) . '/elements/user/edit_post_handler.js', array('jquery'), null , true);  
	
  }

}
add_action('wp_enqueue_scripts', 'enqueue_scripts');


// Include the file that contains the AJAX handler function

include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/form/custom_form_submit.php';
include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/user/create_user.php';
include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/form/preview_template.php';
include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/user/post_display_handler.php';
include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/user/user_info.php';
include_once WP_CONTENT_DIR . '/themes/bricks-child/elements/user/edit_post.php';


// Include Scripts

require_once WP_CONTENT_DIR . '/themes/bricks-child/elements/user/create_user_file.php';

// Register the AJAX actions

add_action('wp_ajax_create-post', 'handle_create_post');
add_action('wp_ajax_nopriv_create-post', 'handle_create_post');
add_action('wp_ajax_create-user', 'create_user_from_form');
add_action('wp_ajax_nopriv_create-user', 'create_user_from_form');
add_action('wp_ajax_show-default-muuk', 'show_default_muuk');
add_action('wp_ajax_show-default-uur', 'show_default_uur');
add_action('wp_ajax_show-next-posts', 'next_render');
add_action('wp_ajax_show-prev-posts', 'prev_render');
add_action('wp_ajax_delete-selected-post', 'delete_selected_post');
add_action('wp_ajax_update-user-info', 'update_user_info');
add_action('wp_ajax_edit-post', 'edit_current_post');

// Add filter for custom profile picture functionality
add_filter( 'get_avatar_url', 'get_pfp_url', 10, 3 );

function get_pfp_url( $url, $id_or_email, $args ) {
    if ( is_numeric( $id_or_email ) ) {
        $user_id = $id_or_email;
    } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
        $user_id = $user->ID;
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = (int) $id_or_email->user_id;
    }

    if ( empty( $user_id ) ) {
        return $url;
    }

    $profile_picture = get_field( 'profile_picture', 'user_' . $user_id );
	
	$pfp_url = wp_get_attachment_url($profile_picture);

    $url = $pfp_url;

    return $url;
}