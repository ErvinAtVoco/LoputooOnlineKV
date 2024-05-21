<?php
function update_user_cred($order_id, $old_status, $new_status)
{
    // Kui tellimus on juba completed siis me ei pea seda koodi jooksutama
    if ("completed" !== $new_status) {
        return;
    }

    // Saame kasutaja_id kes tellimuse tegi
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    // Saame toote id tellimusest
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        echo $product_id;
    }

    // Kontrollime, kas saadud toode on krediidi pakett
    $credit_product_ids = [788, 791, 794, 797];

    if (!in_array($product_id, $credit_product_ids)) {
        return;
    }

    // Määrame, kui palju krediiti kasutajale anname toote id põhjal
    switch ($product_id) {
        case 788:
            $metavalue_credits = 50;
            break;
        case 791:
            $metavalue_credits = 100;
            break;
        case 794:
            $metavalue_credits = 250;
            break;
        case 797:
            $metavalue_credits = 500;
            break;
    }

    // Votame kasutusele vajaliku global muutuja andmebaaside jaoks
    global $wpdb;

    // Valmistame sql query kasutaja uuendamiseks
    $sql = $wpdb->prepare(
        "UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = 'purchased_credits' AND user_id = %d",
        $metavalue_credits,
        $user_id
    );

    $result = $wpdb->query($sql);
    if (!$result) {
        echo "Error updating user meta: " . $wpdb->last_error;
    } else {
        echo "Update done!";
    }
};