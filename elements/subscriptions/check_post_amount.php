<?php 

function check_post_amount() {
    global $wpdb;
    $user_id = get_current_user_id();

    $current_post_amount = count_user_posts( $user_id,'kuulutus', true );
    $max_post_amount = $wpdb->prepare($wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = %d AND meta_key = 'max_posts'", $user_id));

    if($max_post_amount > $current_post_amount) {
        return true;
    } else {
        return false;
    }
}