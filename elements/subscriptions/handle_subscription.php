<?php
add_action("woocommerce_order_status_changed", "update_user_sub", 10, 3);

add_action("updated_postmeta", "cancel_sub", 10, 4);

add_action("woocommerce_order_status_changed", "update_user_cred", 10, 3);

function update_user_sub($order_id, $old_status, $new_status)
{
    // Kui tellimus on juba completed siis me ei pea seda koodi jooksutama
    if ("completed" !== $new_status) {
        return;
    }

    // Saame kasutaja_id kes tellimuse tegi
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        echo $product_id;
    }

    $sub_product_ids = [668, 705, 708, 711, 714];

    if (!in_array($product_id, $sub_product_ids)) {
        return;
    }

    switch ($product_id) {
        case 668:
            $metavalue_sub = 1;
            $metavalue_cred = 25;
            $metavalue_ads = 8;
            $metavalue_client_days = 5;
            $metavalue_highlights = 1;
            break;
        case 705:
            $metavalue_sub = 2;
            $metavalue_cred = 50;
            $metavalue_ads = 16;
            $metavalue_client_days = 20;
            $metavalue_highlights = 3;
            break;
        case 708:
            $metavalue_sub = 3;
            $metavalue_cred = 100;
            $metavalue_ads = 50;
            $metavalue_client_days = 0;
            $metavalue_highlights = 6;
            break;
        case 711:
            $metavalue_sub = 4;
            $metavalue_cred = 0;
            $metavalue_ads = 1;
            $metavalue_client_days = 0;
            $metavalue_highlights = 0;
            break;
        case 714:
            $metavalue_sub = 5;
            $metavalue_cred = 10;
            $metavalue_ads = 0;
            $metavalue_client_days = 0;
            $metavalue_highlights = 1;
            break;
    }

    //Votame kasutusele vajaliku global muutuja andmebaaside jaoks
    global $wpdb;

    // Valime kus kohas me andmebaasis muudatusi tahame teha
    $table_name = $wpdb->prefix . "usermeta";

    // Määrame ära milline peaks data olema
    $data = [
        "meta_value" => $metavalue_sub,
    ];

    // Kindlustame, et me teeme seda õige kasutajaga
    $where = [
        "user_id" => $user_id,
        "meta_key" => "subscription",
    ];

    // Saame teada tulemused ja vea korral viskame veasõnumi
    $result = $wpdb->update($table_name, $data, $where);

    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Määrame ära milline peaks data olema

    $data = [
        "meta_value" => $metavalue_cred,
    ];

    // Kindlustame, et me teeme seda õige kasutajaga

    $where = [
        "user_id" => $user_id,
        "meta_key" => "bonus_credits",
    ];

    // Saame teada tulemused ja vea korral viskame veasõnumi

    $result = $wpdb->update($table_name, $data, $where);

    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Teeme sql käsu et uuendada kasutajate kuulutuste kogust
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'max_posts' AND user_id = %d",
        $metavalue_ads,
        $user_id
    );

    // Saame teada tulemused ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);

    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Teeme sql update query kliendipäevade jaoks
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'kliendipaevad' AND user_id = %d",
        $metavalue_client_days,
        $user_id
    );

    // Saame teada tulemused ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);

    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Teeme sql update query highlightisde jaoks
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'highlight_amount' AND user_id = %d",
        $metavalue_highlights,
        $user_id
    );

    // Saame teada tulemused ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);

    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Saame teada subsrctiptioni post_id läbi order_id
    $sql = $wpdb->prepare(
        "SELECT post_id FROM wp_postmeta WHERE meta_key = 'wps_parent_order' AND meta_value = %s",
        $order_id
    );

    $post_id = $wpdb->get_var($sql);

    // Valmistame sql query
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'subscription_id'",
        $post_id
    );

    // Jooksutame sql querit ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);
    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Saame tellimuse alguse aja
    $sql = $wpdb->prepare(
        "SELECT meta_value FROM wp_postmeta WHERE post_id = %s AND meta_key = 'wps_schedule_start'",
        $post_id
    );

    $sub_start_time = $wpdb->get_var($sql);

    // Sisestame algus aja kasutajasse
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'sub_start_time' AND user_id = %d",
        $sub_start_time,
        $user_id 
    );

    // Jooksutame sql querit ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);
    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }

    // Saame tellimuse lõppu aja
    $sql = $wpdb->prepare(
        "SELECT meta_value FROM wp_postmeta WHERE post_id = %s AND meta_key = 'wps_next_payment_date'",
        $post_id
    );

    $sub_end_time = $wpdb->get_var($sql);

    // Sisestame lõppu aja kasutajasse
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'sub_end_time' AND user_id = %d",
        $sub_end_time,
        $user_id 
    );

    // Jooksutame sql querit ja vea korral viskame veasõnumi
    $result = $wpdb->query($sql);
    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }
}

function cancel_sub($meta_id, $object_id, $meta_key, $meta_value)
{
    if ($meta_key !== "wps_subscription_status") {
        return;
    }
    if ($meta_value !== "cancelled") {
        return;
    }

    global $wpdb;

    $sql = $wpdb->prepare(
        "SELECT meta_value FROM wp_postmeta WHERE meta_key ='wps_customer_id' AND post_id = %s",
        $object_id
    );

    $user_id = $wpdb->get_var($sql);

    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = 0 WHERE meta_key = 'subscription_id' AND user_id = %s",
        $user_id
    );

    $result = $wpdb->query($sql);
    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }
}
