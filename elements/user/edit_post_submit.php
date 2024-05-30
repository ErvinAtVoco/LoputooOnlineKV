<?php
    /* function handle_edit_post() {
        $id = $_GET['id'];
        
        //Post data array
        $post_data = [
            "ID" => $id,
            "meta_input" => [
                "otse_omanikult" => $_POST['otse-omanikult'],
                "tanav" => $_POST['tanav'],
                "maja_nr" => $_POST['maja-nr'],
                "korter" => $_POST['korter'],
                "postiindeks" => $_POST['postiindeks'],
                "katastrinumber" => $_POST['katastrinumber'],
                "kinnistu_number" => $_POST['kinnistu-number'],
                "omandivorm" => $_POST['omandivorm'],
                "ehitusaasta" => $_POST['ehitusaasta'],
                "seisukord" => $_POST['seisukord'],
                "pindala" => $_POST['pindala'],
                "hind" => $_POST['hind'],
                "energiaklass" => $_POST['energiaklass'],
                "tubade_arv" => $_POST['tubade-arv'],
                "korrus" => $_POST['korrus'],
                "korruseid_kokku" => $_POST['korruseid-kokku'],
                "ruutmeetri_hind" => $ruutmeetri_hind,
                "magamistubade_arv" => $_POST['magamistubade-arv'],
                "wc_arv" => $_POST['wc-arv'],
                "vannitubade_arv" => $_POST['vannitubade-arv'],
                "wc_ja_vannituba_koos" => $_POST['WC-vannituba-koos'],
                "sisustus" => $_POST['sisustus'],
                "sanitaarruum" => $_POST['sanitaarruum'],
                "muu_sanitaarruum" => $_POST['muu-sanitaarruum'],
                "naabruskond" => $_POST['naabruskond'],
                "muu_naabruskond" => $_POST['muu-naabruskond'],
                "kook" => $_POST['kook'],
                "koogi_pindala" => $_POST['koogi-pindala'],
                "lisapinnad" => $_POST['lisapinnad'],
                "muu_lisapind" => $_POST['muu-lisapind'],
                "parkimine" => $_POST['parkimine'],
                "parkimiskoht" => $_POST['parkimiskoht'],
                "teedeseisukord" => $_POST['teedeseisukord'],
                "olemasolevad_teed" => $_POST['olemasolevad-teed'],
                "muud_olemasolevad_teed" => $_POST['muud-olemasolevad-teed'],
                "lisad" => $_POST['lisad'],
                "muu_lisad" => $_POST['muu-lisad'],
                "soe_vesi" => $_POST['soe-vesi'],
                "muu_soevesi" => $_POST['muu-soevesi'],
                "veevarustus" => $_POST['veevarustus'],
                "muu_veevarustus" => $_POST['muu-veevarustus'],
                "kanalisatsioon" => $_POST['kanalisatsioon'],
                "muu_kanalisatsioon" => $_POST['muu-kanalisatsioon'],
                "side" => $_POST['side'],
                "muu_side" => $_POST['muu-side'],
                "turvalisus" => $_POST['turvalisus'],
                "muu_turvalisus" => $_POST['muu-turvalisus'],
                "kuttesusteem" => $_POST['kuttesusteem'],
                "muu_kuttesusteem" => $_POST['muu-kuttesusteem'],
                "kommunaal_suvi" => $_POST['kommunaal-suvi'],
                "kommunaal_talv" => $_POST['kommunaal-talv']
            ],
        ];

        // Update post using the post data 
        wp_update_post($post_data);

        $thumbnail_attachment_id = "";
        $thumbnail = $_FILES['thumbnail'];

        if (is_array($thumbnail['name'])) {
            $thumbnail_image = array(
                'name'     => $thumbnail['name'][0],
                'type'     => $thumbnail['type'][0],
                'tmp_name' => $thumbnail['tmp_name'][0],
                'error'    => $thumbnail['error'][0],
                'size'     => $thumbnail['size'][0]
            );

            $thumbnail_attachment_id = process_uploaded_image($thumbnail_image);
        };

        error_log("Thumbnail uploaded " . $id . " in edit." );

        $galerii = $_FILES['uploads'];
        $galerii_attachment_ids = [];

        if (is_array($galerii['name'])) {
            $files = count($galerii['name']);
            for ($i = 0; $i < $files; $i++) {
                $galerii_image = array(
                    'name'     => $galerii['name'][$i],
                    'type'     => $galerii['type'][$i],
                    'tmp_name' => $galerii['tmp_name'][$i],
                    'error'    => $galerii['error'][$i],
                    'size'     => $galerii['size'][$i]
                );

                $galerii_attachment_id = process_uploaded_image($galerii_image);

                array_push($galerii_attachment_ids, $galerii_attachment_id);
            }
        };

        error_log("Galerii uploaded " . $post_id . " in edit.");

        // Set post thumbnail
        set_post_thumbnail($post_id, $thumbnail_attachment_id);

        // Set galerii images
        update_field("galerii", $galerii_attachment_ids, $post_id);


    };
?>