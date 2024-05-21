<?php 

function edit_current_post() {
    $post_id = $_POST['id'];
    if(get_current_user_id() === intval(get_post($post_id)->post_author)){
        $redirect_url = esc_url( add_query_arg( 'id', $post_id, get_page_link(2496) ));
        echo $redirect_url;
        wp_die();
    }
    echo 'https://easyweb.ee/kv/';
    wp_die();
}