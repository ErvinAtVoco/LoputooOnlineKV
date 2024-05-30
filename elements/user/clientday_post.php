<?php

function update_post_clientday($id, $date, $start_time, $end_time) {
    global $wpdb;

    $wpdb->query($wpdb->prepare("UPDATE wp_postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = 'kliendip_kuupaev'", $date, $id));
    $wpdb->query($wpdb->prepare("UPDATE wp_postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = 'kliendip_algus'", $start_time, $id));
    $wpdb->query($wpdb->prepare("UPDATE wp_postmeta SET meta_value = %s WHERE post_id = %d AND meta_key = 'kliendip_lopp'", $end_time, $id));
}

function create_client_day() {
    global $wpdb;
    $date = $_POST['date'];
    $start_time = $_POST['startTime'];
    $end_time = $_POST['endTime'];
    $post_id = $_POST['id'];
    $cost = 50;
    $user_id = get_current_user_id(  );

    // Get user credits
    $bonus_credits = intval($wpdb->get_var($wpdb->prepare("SELECT meta_value from wp_usermeta WHERE user_id = %s AND meta_key = 'bonus_credits'", $user_id)));
    $purchased_credits = intval($wpdb->get_var($wpdb->prepare("SELECT meta_value from wp_usermeta WHERE user_id = %s AND meta_key = 'purchased_credits'", $user_id)));

    // User bonus credits before purchased
    if ($bonus_credits >= $cost) {
        $bonus_credits = $bonus_credits - $cost;
        $wpdb->query($wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'bonus_credits' AND user_id = %d", $bonus_credits, $user_id));
    } else if ($purchased_credits >= $cost) {
        $purchased_credits = $purchased_credits - $cost;
        $wpdb->query($wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'purchased_credits' AND user_id = %d", $purchased_credits, $user_id));
    } else if (($bonus_credits+$purchased_credits) >= $cost) {
        $cost_left = abs($bonus_credits - $cost);
        $purchased_credits = $purchased_credits - $cost_left;
        $bonus_credits = 0;
        $wpdb->query($wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'bonus_credits' AND user_id = %d", $bonus_credits, $user_id));
        $wpdb->query($wpdb->prepare("UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'purchased_credits' AND user_id = %d", $purchased_credits, $user_id));
    } else {
        // Handle Error
        wp_die();
    }

    update_post_clientday($post_id, $date, $start_time, $end_time);

    wp_die();
}