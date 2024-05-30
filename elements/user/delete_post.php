<?php

/**
 * Returns posts array with edited and added fields for rending to frontend
 *
 * @param array   $posts  Array of posts that need to be edited for rendering
 * @param string $type Type of posts that need to be changed, this sets the posts object type to given parameter
 * 
 * @author Raikko
 * @return array Array that is ready to be rendered to frontend user interface
 */ 

// Deletes the selected post and rerenders posts
function delete_selected_post() 
{
    check_ajax_referer( 'validation', 'nonce');

    global $user_id;
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    $post = get_post($id);

    if(intval($post->post_author) !== $user_id){
        return header('HTTP/1.0 400 Failed to find post');
    }

    wp_delete_post($id);

    error_log(print_r(set_post_data(return_posts($user_id, $type), $type), true));

    $responseData = set_post_data(return_posts($user_id, $type), $type);
    echo json_encode($responseData);

    wp_die();
}
