<?php

$user_id = get_current_user_id();


// Function used to handle get_posts and pass the json encoded data to show_posts.js for post rendering
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
