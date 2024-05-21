<?php

function get_initial_listings() {
    $args = [
        'numberposts' => 10,
        'offset' => 0,
        'orderyby' => 'date',
        'order' => 'DESC',
        'post_type' => 'kuulutus',
        'post_status' => 'publish',
    ];

    return get_posts($args);
};

function set_listing_data($listings) {
    global $wpdb;

	for ($i = 0; $i < count($listings); $i++) {
        $maakond_terms = get_the_terms($listings[$i]->ID, 'maakond');
        $listings[$i]->maakond = wp_list_pluck($maakond_terms, 'name');
        $linn_vald_terms = get_the_terms($listings[$i], 'linn');
        $listings[$i]->linn_vald = wp_list_pluck($linn_vald_terms, 'name');
        $listings[$i]->korrus = get_field("korrus", $listings[$i]->ID);
        $listings[$i]->korruseid_kokku = get_field("korruseid_kokku", $listings[$i]->ID);
        $listings[$i]->pindala = get_field("pindala", $listings[$i]->ID);
        $listings[$i]->tubade_arv = get_field("tubade_arv", $listings[$i]->ID);
        $tehingu_tuup_terms = get_the_terms($listings[$i]->ID, 'tehingu_tuup');
        $listings[$i]->tehingu_tuup = wp_list_pluck($tehingu_tuup_terms, 'name');
        $listings[$i]->price = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'hind' AND post_id = %s", $listings[$i]->ID));
        $listings[$i]->image = get_the_post_thumbnail_url($listings[$i]->ID);
    };

	return $listings;
};

function show_listings() {
    $listings = set_listing_data(get_initial_listings());
    ob_start(); ?>
        <div id="listings">
            <?php foreach($listings as $listing) { ?>
                <div class="listing-card">
                    <img class="listing-img" src="<?php echo $listing->image ?>">
                    <div class="listing-info">
                        <p><?php if (is_array($listing->maakond)) {
                                        echo implode(', ', $listing->maakond);
                                    } else {
                                        echo $listing->maakond;
                                    } ?>, 
                                    <?php if (is_array($listing->linn_vald)) {
                                                echo implode(', ', $listing->linn_vald);
                                            } else {
                                                echo $listing->linn_vald;
                                            } 
                                    ?>
                        </p>
                        <h3><?php echo $listing->post_title ?></h3>
                        <div class="listing-meta">
                            <p><?php echo $listing->korrus ?>/<?php echo $listing->korruseid_kokku ?> korrus | <?php echo $listing->tubade_arv?> tube | <?php echo $listing->pindala ?>m&sup2;</p>
                        </div>
                        <h3><?php echo $listing->price ?>€</h3>
                    </div>
                    <p><?php if (is_array($listing->tehingu_tuup)) {
                                echo implode(', ', $listing->tehingu_tuup);
                            } else {
                                echo $listing->tehingu_tuup;
                            }?>
                    </p>
                </div>
            <?php 
        };?>
        </div>
    <?php return ob_get_clean();
};
add_shortcode("show_listings", "show_listings");