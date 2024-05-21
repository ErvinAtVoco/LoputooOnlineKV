<?php

function handle_filter_search()
{
    //Declare global wordpress database variable
    global $wpdb;

    //Testing variable
    $maakond = array("Harjumaa", "Hiiumaa", "Raplamaa");
    $linn_vald = "";
    $asula = "";

    $omandivorm = "";
    $tube_alates = "";
    $tube_kuni = "";
    $pindala_alates = "";
    $pindala_kuni = "";

    /* //Turn post data into variables for later use
    //Filter header (puudub hoone tüüp ja tehingu tüüp)
    $maakond = $_POST['maakond'];
    $linn_vald = $_POST['linn-vald'];
    $asula = $_POST['asula'];

    //Basic filters (puudub otse omanikult)
    $omandivorm = $_POST['omandivorm'];
    $tube_alates = $_POST['tube-alates'];
    $tube_kuni = $_POST['tube-kuni'];
    $pindala_alates = $_POST['pindala-alates'];
    $pindala_kuni = $_POST['pindala-kuni'];
    $ehitusaasta_alates = $_POST['ehitusaasta-alates'];
    $ehitusaasta_kuni = $_POST['ehitusaasta-kuni'];
    $hind_alates = $_POST['hind-alates'];
    $hind_kuni = $_POST['hind-kuni'];
    $seisukord = $_POST['seisukord'];

    //Specific filters
    $energiaklass = $_POST['energiaklass'];
    $korruseid_alates = $_POST['korruseid-alates'];
    $korruseid_kuni = $_POST['korruseid-kuni'];
    $magamistubade_arv_alates = $_POST['magamistubade-arv-alates'];
    $magamistubade_arv_kuni = $_POST ['magamistubade-arv-kuni'];
    $WC_arv_alates = $_POST['WC-arv-alates'];
    $WC_arv_kuni = $_POST['WC-arv-kuni'];
    $vannitubade_arv_alates = $_POST['vannitubade-arv-alates'];
    $vannitubade_arv_kuni = $_POST['vannitubade-arv-kuni'];
    $sanitaarruum = $_POST['sanitaarruum'];
    $naabruskond = $_POST['naabruskond'];
    $kook = $_POST['kook'];
    $koogi_pindala = $_POST['koogi-pindala']; //filter_form.phps muuta "-" mitte "_"
    $sisustus = $_POST['sisustus'];
    $lisapinnad = $_POST['lisapinnad'];
    $muu_lisapind = $_POST['muu-lisapind'];
    $lisad = $_POST['lisad'];
    $muu_lisad = $_POST['muu-lisad'];
    $parkimine = $_POST['parkimine'];
    $teedeseisukord = $_POST['teedeseisukord'];
    $olemasolevad_teed = $_POST['olemasolevad-teed'];
    $muud_olemasolevad_teed = $_POST['muud-olemasolevad-teed'];
    $soe_vesi = $_POST['soe-vesi']; //filter_form.phps muuta "-" mitte "_"
    $muu_soevesi = $_POST['muu-soevesi'];
    $veevarustus = $_POST['veevarustus'];
    $muu_veevarustus = $_POST['muu-veevarustus'];
    $kanalisatsioon = $_POST['kanalisatsioon'];
    $muu_kanalisatsioon = $_POST['muu-kanalisatsioon'];
    $side = $_POST['side'];
    $kuttesusteem = $_POST['kuttesusteem'];
    $muu_kuttesusteem = $_POST['muu-kuttesusteem'];
    $turvalisus = $_POST['turvalisus'];
    $muu_turvalisus = $_POST['muu-turvalisus']; */

    //Create query arg variables
    $tax_query = [];
    $meta_query = [];

    //Check if incoming variables are empty or not, add filled variables to args
    //This is a major point to be optimised, I think

    //tax_query args
    //Maakond
    if (!empty($maakond)) {
        $tax_query[] = array(
        'taxonomy' => 'maakond',
        'field' => 'slug',
        'terms' => $maakond,
        'operator' => 'IN',
        );
    };
    //Linn or Vald
    if (!empty($linn_vald)) {
        $tax_query[] = array(
        'taxonomy' => 'linn',
        'field' => 'slug',
        'terms' => $linn_vald,
        );
    };
    //Asula or Linna osa
    if (!empty($asula)) {
        $tax_query[] = array(
        'taxonomy' => 'asulalinnaosa',
        'field' => 'slug',
        'terms' => $asula,
        );
    };

    //meta_query args
    //Omandivorm
    if(!empty($omandivorm)) {
        $meta_query[] = array(
            'key' => 'omandivorm',
            'value' => $omandivorm,
            'type' => 'string',
            'compare' => '=',
        );
    };
    //Tubade arv
    if(!empty($tube_alates) || !empty($tube_kuni)) {
        if(empty($tube_kuni)) {
            $tube_kuni = 99;
        };
        if(empty($tube_alates)) {
            $tube_alates = 1;
        };
        $meta_query[] = array(
                'key' => 'tubade_arv',
                'value' => array($tube_alates, $tube_kuni),
                'type' => 'numeric',
                'compare' => 'BETWEEN',
        );
    };
    //Pindala
    if(!empty($pindala_alates) || !empty($pindala_kuni)) {
        if(empty($pindala_kuni)) {
            $pindala_kuni = 9999999;
        };
        if(empty($pindala_alates)) {
            $pindala_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'pindala',
            'value' => array($pindala_alates, $pindala_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //Ehitusaasta
    if(!empty($ehitusaasta_alates) || !empty($ehitusaasta_kuni)) {
        if(empty($ehitusaasta_kuni)) {
            $ehitusaasta_kuni = 3000;
        };
        if(empty($ehitusaasta_alates)) {
            $ehitusaasta_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'ehitusaasta',
            'value' => array($ehitusaasta_alates, $ehitusaasta_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //Hind
    if(!empty($hind_alates) || !empty($hind_kuni)) {
        if(empty($hind_kuni)) {
            $hind_kuni = 9999999;
        };
        if(empty($hind_alates)) {
            $hind_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'hind',
            'value' => array($hind_alates, $hind_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //Seisukord
    if(!empty($seisukord)) {
        $meta_query[] = array(
            'key' => 'seisukord',
            'value' => $seisukord,
            'type' => 'string',
            'compare' => '=',
        );
    };
    //Energiaklass
    if(!empty($energiaklass)) {
        $meta_query[] = array(
            'key' => 'energiaklass',
            'value' => $energiaklass,
            'type' => 'string',
            'compare' => '=',
        );
    };
    //Korruseid kokku
    if(!empty($korruseid_alates) || !empty($korruseid_kuni)) {
        if(empty($korruseid_kuni)) {
            $korruseid_kuni = 999;
        };
        if(empty($korruseid_alates)) {
            $korruseid_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'korruseid_kokku',
            'value' => array($korruseid_alates, $korruseid_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //Magamistubade arv
    if(!empty($magamistubade_arv_alates) || !empty($magamistubade_arv_kuni)) {
        if(empty($magamistubade_arv_kuni)) {
            $magamistubade_arv_kuni = 99;
        };
        if(empty($magamistubade_arv_alates)) {
            $magamistubade_arv_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'magamistubade_arv',
            'value' => array($magamistubade_arv_alates, $magamistubade_arv_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //WCde arv
    if(!empty($WC_arv_alates) || !empty($WC_arv_kuni)) {
        if(empty($WC_arv_kuni)) {
            $WC_arv_kuni = 99;
        };
        if(empty($WC_arv_alates)) {
            $WC_arv_alates = 1;
        };
        $meta_query[] = array(
            'key' => 'wc_arv',
            'value' => array($WC_arv_alates, $WC_arv_kuni),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    };
    //Vannitubade arv
    if(!empty($vannitubade_arv_alates) || !empty($vannitubade_arv_kuni)) {
        if(empty($vannitubade_arv_kuni)) {
            $vannitubade_arv_kuni = 99;
        };
        if(empty($vannitubade_arv_alates)) {
            $vannitubade_arv_alates = 1;
        };
        $meta_query[] = array(
                'key' => 'vannitubade_arv',
                'value' => array($vannitubade_arv_alates, $vannitubade_arv_kuni),
                'type' => 'numeric',
                'compare' => 'BETWEEN',
        );
    };
    //Sanitaarruum
    if(!empty($sanitaarruum)) {
        $meta_query[] = array(
            'key' => 'sanitaarruum',
            'value' => $sanitaarruum,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Naabruskond
    if(!empty($naabruskond)) {
        $meta_query[] = array(
            'key' => 'naabruskond',
            'value' => $naabruskond,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Köök
    if(!empty($kook)) {
        $meta_query[] = array(
            'key' => 'kook',
            'value' => $kook,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Köögi pindala
    if(!empty($koogi_pindala)) {
        $meta_query[] = array(
            'key' => 'koogi_pindala',
            'value' => $koogi_pindala,
            'type' => 'numeric',
            'compare' => '=',
        );
    };
    //Sisustus
    if(!empty($sisustus)) {
        $meta_query[] = array(
            'key' => 'sisustus',
            'value' => $sisustus,
            'type' => 'string',
            'compare' => '=',
        );
    };
    //Lisapinnad
    if(!empty($lisapinnad)) {
        $meta_query[] = array(
            'key' => 'lisapinnad',
            'value' => $lisapinnad,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Muu Lisapinnad
    if(!empty($muu_lisapinnad)) {
        $meta_query[] = array(
            'key' => 'muu_lisapinnad',
            'value' => $muu_lisapinnad,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Lisad
    if(!empty($lisad)) {
        $meta_query[] = array(
            'key' => 'lisad',
            'value' => $lisad,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Muu Lisad
    if(!empty($muu_lisad)) {
        $meta_query[] = array(
            'key' => 'muu_lisad',
            'value' => $muu_lisad,
            'type' => 'string',
            'compare' => 'IN',
        );
    };
    //Parkimine
    if(!empty($parkimine)) {
        $meta_query[] = array(
            'key' => 'parkimine',
            'value' => $parkimine,
            'type' => 'string',
            'compare' => '=',
        );
    };



    //Add relation if multiple things have been searched for
    if(count($tax_query) > 1) {
        array_unshift($tax_query, array('relation' => 'AND'));
    };
    if(count($meta_query) > 1) {
        array_unshift($meta_query, array('relation' => 'AND'));
    };
    
    



    //Create args for WP_query
    $args = array(
        'post_type' => 'kuulutus',
        'post_status' => 'publish',
        'tax_query' => $tax_query,
        'meta_query' => $meta_query,
    );

    //Perform the WP_query using args
    $query = new WP_query($args);

    //Create variable to store featured post ids and regular post ids
    $featured_post_ids = [];
    $post_ids = [];

    //Handle query results
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            //Get the id of the post
            $post_id = get_the_ID();
            //Check if post is featured
            $is_featured = get_field('featured', $post_id);
            error_log("post featured status " . $is_featured);
            //If post is featured add to featured post ids array otherwise add to post ids array
            if($is_featured == 1) {
                $featured_post_ids[] = $post_id;
            } else {
                $post_ids[] = $post_id;
            };
            error_log("featured post ids in search" . json_encode($featured_post_ids));
            error_log("non featured post ids in search" . json_encode($post_ids));
            error_log(json_encode($post_id));
        };
        wp_reset_postdata();
    } else {
        error_log('Ei leidnud postitusi');
    };

    error_log("featured post ids from search" . json_encode($featured_post_ids));
    error_log("non featured post ids from search" . json_encode($post_ids));
};