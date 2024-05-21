<?php

$user_id = get_current_user_id();

// function next_render()
// {
//     check_ajax_referer( 'validation', 'nonce');
//     if(!isset($_SESSION)) 
//     { 
//         session_start(); 
//     } 
//     $type = $_POST['type'];
//     $value = 'max_render_' . $type;

//     $_SESSION[$value] = isset($_SESSION[$value]) ? $_SESSION[$value] + 5 : 0;

//     header('Content-Type: application/json');

//     $args = get_args($type, $value);

//     echo render_posts($args);

//     wp_die();
// }

// function prev_render()
// {
//     check_ajax_referer( 'validation', 'nonce');
//     if(!isset($_SESSION)) 
//     { 
//         session_start(); 
//     } 
//     $type = $_POST['type'];
//     $value = 'max_render_' . $type;

//     $_SESSION[$value] = isset($_SESSION[$value]) ? $_SESSION[$value] - 5 : 0;

//     header('Content-Type: application/json');

//     $args = get_args($type, $value);

//     echo render_posts($args);

//     wp_die();
// }

function delete_selected_post() 
{
    check_ajax_referer( 'validation', 'nonce');

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

    global $user_id;
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    $post = get_post($id);

    if(intval($post->post_author) !== $user_id){
        return header('HTTP/1.0 400 Failed to find post');
    }

    wp_delete_post($id);

    // if($type === 'uur')
    // {
    //     $max_render = 'max_render_uur';
    // }
    // else if ($type === 'muuk')
    // {
    //     $max_render = 'max_render_muuk';
    // }


    header('Content-Type: application/json');

    $args = get_args($type);
    echo render_posts($args);

    wp_die();
}

function get_args($type)
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    global $user_id;
    // $max_render = isset($_SESSION[$render_amount]) ? $_SESSION[$render_amount] : 0;

    $args = [
        'post_type' => 'kuulutus',
        'post_status' => 'publish',
        'author' => $user_id,
        'offset' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'tehingu-tuup',
                'field' => 'slug',
                'terms' => $type
            ]
        ]
    ];

    return $args;
}

function render_posts($args)
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    global $wpdb;
    
    $posts = get_posts($args);

    error_log(print_r($posts));

    // This is good does not effect speed
    for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]->type = $args['tax_query'][0]['terms'];
        $posts[$i]->post_date = date("d-m-Y", strtotime($posts[$i]->post_date));
        $posts[$i]->price = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'hind' AND post_id = %s", $posts[$i]->ID));
        $posts[$i]->image = get_the_post_thumbnail_url($posts[$i]->ID);
    }

    $jsonData = json_encode($posts);

    return $jsonData;                                                                                          
}
