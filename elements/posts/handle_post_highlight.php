<?php

add_action("updated_postmeta", "add_highligh", 10, 4);

function add_highligh($meta_id, $object_id, $meta_key, $meta_value)
{
    if ($meta_key !== "featured") {
        return;
    }

    if ($meta_value === 1) {
        global $wpdb;

        $update_algus = $wpdb->prepare(
            "UPDATE wp_postmeta SET esiletostmise_algus_aeg = %s WHERE post_id = %d",
            time(),
            $object_id
        );

        $wpdb->query($update_algus);
    }
}
