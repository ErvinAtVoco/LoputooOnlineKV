<?php




function handle_create_post()
{

	global $index_pattern, $free_text_pattern;
	$user_id = get_current_user_id();
	global $wpdb;

	////////////////////////////
	/////Edgecases
	///////////////////////////
	if (!isset($_POST['my_nonce_name']) || !wp_verify_nonce($_POST['my_nonce_name'], 'my_nonce_action')) {
		wp_send_json_error('Palun kasutage ette antud kuulutuse loomise küsitlust', 401);
		wp_die();
	}

	if (!is_user_logged_in()) {
		wp_send_json_error('Kasutaja pole sisse logitud', 401);
		wp_die();
	}

	$segment = $_POST['segment'];

	switch (intval($segment)) {
		case 0:

			if ($_POST['realEstateType'] === "" || $_POST['salesType'] == "") {
				return json_encode(array("error" => true, "message" => "Veenduge, et oleks valitud hoone ja tehingu tüüp"));
			}

			$maakond = $_POST['maakond'];
			$linn_vald = $_POST['linn-vald'];
			$asula = $_POST['asula'];
			$tanav = $_POST['tänav'];
			$omandivorm = $_POST['omandivorm'];

			check_regex_of_array([$_POST['tänav']], $free_text_pattern);
			check_regex_of_array([$_POST['tubade-arv'], $_POST['korrus'], $_POST['korruseid-kokku'], $_POST['pindala'], $_POST['ehitusaasta'], $_POST['korter'], $_POST['postiindeks'], $_POST['maja-nr'], $_POST['hind'], $_POST['kinnistu-number'], $_POST['katastrinumber']], $index_pattern);

			try {
				$format = numfmt_create('eu', NumberFormatter::CURRENCY);
				$ruutmeetri_hind = numfmt_format_currency($format, intval($_POST["hind"]) / intval($_POST["pindala"]), "EUR");
				error_log($ruutmeetri_hind);
			} catch (DivisionByZeroError $e) {
				$ruutmeetri_hind = 0;
				wp_send_json_error('Ruutmeetri hind või pindala ei saa olla 0', 401);
				wp_die();
			}

			$korterMaja = $_POST['korter'] ? $_POST['maja-nr'] . '-' . $_POST['korter'] : $_POST['maja-nr'];

			$post_data = [
				"post_title" => $_POST['tubade-arv'] . ' tuba, ' . $tanav . ' ' . $korterMaja . ' ' . $asula . ' ' . $linn_vald . ' ' . $maakond,
				"post_status" => "draft",
				"post_type" => "kuulutus",
				"post_visibility" => "private",
				"meta_input" => [
					"otse_omanikult" => $_POST['otse-omanikult'],
					"tanav" => $tanav,
					"maja_nr" => $_POST['maja-nr'],
					"korter" => $_POST['korter'],
					"postiindeks" => $_POST['postiindeks'],
					"katastrinumber" => $_POST['katastrinumber'],
					"kinnistu_number" => $_POST['kinnistu-number'],
					"omandivorm" => $omandivorm,
					"ehitusaasta" => $_POST['ehitusaasta'],
					"seisukord" => $_POST['seisukord'],
					"pindala" => $_POST['pindala'],
					"hind" => $_POST['hind'],
					"energiaklass" => $_POST['energiaklass'],
					"tubade_arv" => $_POST['tubade-arv'],
					"korrus" => $_POST['korrus'],
					"korruseid_kokku" => $_POST['korruseid-kokku'],
					"ruutmeetri_hind" => $ruutmeetri_hind,
					"featured" => 0,
				],
				"tax_input" => [
					"asulalinnaosa" => $asula,
					"linn" => $linn_vald,
					"maakond" => $maakond,
					"hoone_tuup" => $_POST["realEstateType"],
					"tehingu_tuup" => $_POST["salesType"],
				],
			];

			
			$post_id = wp_insert_post($post_data);

			if (is_wp_error($post_id)) {
				error_log("Failed to insert post: " . $post_id->get_error_message());
				return;
			}
		
			if (!$post_id) {
				error_log("Failed to insert post: Unknown error");
				return;
			}

			$old_post = $wpdb->get_var($wpdb->prepare('SELECT meta_value FROM wp_usermeta WHERE meta_key = "recent_draft" AND user_id = %s', $user_id));

			wp_delete_post($old_post, true);

			$wpdb->query($wpdb->prepare('UPDATE wp_usermeta SET meta_value = %s WHERE meta_key = "recent_draft" AND user_id = %d', $post_id, $user_id));

		case 1:
			$new_post = $wpdb->get_var($wpdb->prepare('SELECT meta_value FROM wp_usermeta WHERE meta_key = "recent_draft" AND user_id = %s', $user_id));

			check_regex_of_array([$_POST['muu-sanitaarruum'], $_POST['muu-naabruskond'], $_POST['muu-lisapind'], $_POST['muud-olemasolevad-teed'], $_POST['muu-soevesi'], $_POST['muu-veevarustus'], $_POST['muu-kanalisatsioon'], $_POST['muu-side'], $_POST['muu-turvalisus'], $_POST['muu-kuttesusteem']], $free_text_pattern);
			check_regex_of_array([$_POST['magamistubade-arv'], $_POST['vannitubade-arv'], $_POST['kommunaal-suvi'], $_POST['kommunaal-talv']], $index_pattern);
			
			$post_data = [
				"ID" => intval($new_post),
				"meta_input" => [
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

			wp_update_post($post_data);

		case 2:

			$new_post = $wpdb->get_var($wpdb->prepare('SELECT meta_value FROM wp_usermeta WHERE meta_key = "recent_draft" AND user_id = %s', $user_id));

			check_regex_of_array([$_POST['content']], $free_text_pattern);

			$post_data = [
				"ID" => intval($new_post),
				"post_content" => $_POST['content'],
			];

			$post_id = wp_update_post($post_data);
			error_log("This is the posts ID in case 2:" . $post_id);

			// Get Wordpress upload directory
			$upload_dir = wp_upload_dir();

			// Create user file path
			$user_file_path = $upload_dir['basedir'] . '/' . 'users' . '/' . 'user-' . $user_id;

			/////////////////////////////////
			/////// Thumbnail and gallery creation
			/////////////////////////////////

			$thumbnail_attachment_id = "";
			$thumbnail = $_FILES['thumbnail'];
			error_log(json_encode($thumbnail));

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

			$galerii = $_FILES['uploads'];
			error_log(json_encode($galerii));
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
			}

			// Set post thumbnail
			set_post_thumbnail($post_id, $thumbnail_attachment_id);

			// Set galerii images
			update_field("galerii", $galerii_attachment_ids, $post_id);
	}
	wp_die();
}
