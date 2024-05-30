<?php

/**
 * Returns the response of get_posts() with the created arguments passed to the function
 *
 * @param integer   $id  The currently active and verified users ID
 * @param integer $amount How many times posts would you like to display (-1 all) by default is 5
 * @param string $type What type of posts are you looking for ( enter  taxonomy term for tehingu-tuup)
 * 
 * @author Raikko
 * @return array Array of all posts related to search arguments
 */ 

 function return_posts($id, $type){
	$args = array(
		'post_type' => 'kuulutus',
		'post_status' => array('publish','draft'),
		'author' => $id,
		'tax_query' => array(
			array(
				'taxonomy' => 'tehingu_tuup',
				'field' => 'slug',
				'terms' => $type,
			)
		)
	);

	return get_posts($args);
 }



/**
 * Returns posts array with edited and added fields for rending to frontend
 *
 * @param array   $posts  Array of posts that need to be edited for rendering
 * @param string $type Type of posts that need to be changed, this sets the posts object type to given parameter
 * 
 * @author Raikko
 * @return array Array that is ready to be rendered to frontend user interface
 */ 
function set_post_data($posts, $type) {

	global $wpdb;

	for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]->type = $type;
		$posts[$i]->status = get_post_status($posts[$i]->ID);
        $posts[$i]->post_date = date("d-m-Y", strtotime($posts[$i]->post_date));
        $posts[$i]->price = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM wp_postmeta WHERE meta_key = 'hind' AND post_id = %s", $posts[$i]->ID));
        $posts[$i]->image = get_the_post_thumbnail_url($posts[$i]->ID);
    }

	return $posts;
}

function user_interface()
{
	//////////////////////////////////////////
	// SECURITY CHECKS
	//////////////////////////////////////////

	global $wpdb;
	if(!is_user_logged_in()){
		echo("<script>location.href = 'https://easyweb.ee/kv/'</script>");
        die();
	}

	//////////////////////////////////////////
	// GET USER INFO
	//////////////////////////////////////////

	$user = wp_get_current_user();
	$user_id = $user->ID;
	$user_email = $user->user_email;
	$user_firstname = $user->user_firstname;
	$user_lastname = $user->user_lastname;
	$user_isikukood = $wpdb->get_var($wpdb->prepare("SELECT meta_value from wp_usermeta WHERE meta_key = 'isikukood' AND user_id = %s", $user_id));
	$user_telefon = $wpdb->get_var($wpdb->prepare("SELECT meta_value from wp_usermeta WHERE meta_key = 'telefon' AND user_id = %s", $user_id));
	$user_avatar = esc_url(get_avatar_url(wp_get_current_user(  )->ID));

	//////////////////////////////////////////
	// GET USER POINTS
	//////////////////////////////////////////

	$bonus_punktid = $wpdb->prepare("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'bonus_credits' AND user_id = %s", $user_id);

	$ostetud_punktid = $wpdb->prepare("SELECT meta_value FROM wp_usermeta WHERE meta_key = 'purchased_credits' AND user_id = %s", $user_id);

	$kasutaja_punktid = intval($wpdb->get_var($bonus_punktid)) + intval($wpdb->get_var($ostetud_punktid));

	//////////////////////////////////////////
	// GET USER POSTS
	//////////////////////////////////////////

	// GET ÜÜR

	$uur_posts = set_post_data(return_posts(2, 'uur'), 'uur');

	// Get müük

	$muuk_posts = set_post_data(return_posts(2, 'muuk'), 'muuk');

	// Get müük

	$luhaja_posts = set_post_data(return_posts(2, 'luhiajaline-uur'), 'luhiajaline-uur');

	ob_start(); ?>
	<script src="https://kit.fontawesome.com/dbe83c52a8.js" crossorigin="anonymous"></script>
	<div id='popup-container' class="popup"></div>
	<div class="container">
		<div>
			<h5>Sinu punktid</h5>
			<h3><?php echo $kasutaja_punktid ?></h3>
			<p>kokku</p>
		</div>
		<div>
			<h5>Sinu vaatamised</h5>
			<h3></h3>
			<p>7 päeva vaatamised</p>
		</div>
		<h3 class="section-title">Aktiivsed kuulutused(<?php echo count($muuk_posts) + count($uur_posts) + count($luhaja_posts) ?>)</h3>

		<div class="post-sections">
				<div class="section-seperator">
					<h5 id="section-type" class="column-name">Müük (<?php echo count($muuk_posts) ?>)</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Aadress</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Hind</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Kuupäev</h5>
				</div>
		</div>

		<div id="muuk">
			<div id="muuk-posts">
				<?php
					foreach($muuk_posts as $post ) {
						?>
						<div class="post-container">
							<div class="post-seperator">
								<img class="post-image" src=<?php echo $post->image ?> />
							</div>
							<div class="post-seperator">
								<h5 class="post-title"><?php echo $post->post_title ?> 
								<?php if($post->status == 'draft'){
									echo '(DRAFT)';
								} else {
									echo '(AKTIIVNE)';
								}?></h5>
								<p class="post-id"><?php echo $post->ID ?></p>
							</div>
							<div class="post-seperator">
								<h5 class="post-price"><?php echo $post->price ?>€</h5>
							</div>
							<div class="post-seperator">
									<div>
										<p class="post-date-subtitle">Sisestatud:</p>
										<p class="post-date"><?php echo $post->post_date ?></p>
									</div>
							</div>
                		</div>
						<div class="post-container">
							<button onclick="createClientDay(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Telli kliendipäev</button>
							<button id="post-button" onclick="editPost(<?php echo $post->ID ?>)">Muuda</button>
							<button onclick="deleteNotification(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Kustuta</button>
						</div>
						<?php
					}
				?>
			</div>
		</div>

		<div class="post-sections">
				<div class="section-seperator">
					<h5 id="section-type" class="column-name">Üür (<?php echo count($uur_posts) ?>)</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Aadress</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Hind</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Kuupäev</h5>
				</div>
		</div>

		<div id="uur">
			<div id="uur-posts">
			<?php
				foreach($uur_posts as $post ) {
					?>
					<div class="post-container">
						<div class="post-seperator">
							<img class="post-image" src=<?php echo $post->image ?> />
						</div>
						<div class="post-seperator">
							<h5 class="post-title"><?php echo $post->post_title ?>
							<?php if($post->status == 'draft'){
									echo '(DRAFT)';
								} else {
									echo '(AKTIIVNE)';
								}?></h5>
							<p class="post-id"><?php echo $post->ID ?></p>
						</div>
						<div class="post-seperator">
							<h5 class="post-price"><?php echo $post->price ?>€</h5>
						</div>
						<div class="post-seperator">
								<div>
									<p class="post-date-subtitle">Sisestatud:</p>
									<p class="post-date"><?php echo $post->post_date ?></p>
								</div>
						</div>
                	</div>
					<div class="post-container">
						<button onclick="createClientDay(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Telli kliendipäev</button>
						<button id="post-button" onclick="editPost(<?php echo $post->ID ?>)">Muuda</button>
						<button onclick="deleteNotification(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Kustuta</button>
					</div>
						<?php
					}
				?>
			</div>
		</div>

		<div class="post-sections">
				<div class="section-seperator">
					<h5 id="section-type" class="column-name">Lühiajaline Üür (<?php echo count($luhaja_posts) ?>)</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Aadress</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Hind</h5>
				</div>
				<div class="section-seperator">
					<h5 class="column-name">Kuupäev</h5>
				</div>
		</div>

		<div id="luhiajaline-uur">
			<div id="luhiajaline-uur-posts">
			<?php
				foreach($luhaja_posts as $post ) {
					?>
					<div class="post-container">
						<div class="post-seperator">
							<img class="post-image" src=<?php echo $post->image ?> />
						</div>
						<div class="post-seperator">
							<h5 class="post-title"><?php echo $post->post_title ?>
							<?php if($post->status == 'draft'){
									echo '(DRAFT)';
								} else {
									echo '(AKTIIVNE)';
								}?></h5>
							<p class="post-id"><?php echo $post->ID ?></p>
						</div>
						<div class="post-seperator">
							<h5 class="post-price"><?php echo $post->price ?>€</h5>
						</div>
						<div class="post-seperator">
								<div>
									<p class="post-date-subtitle">Sisestatud:</p>
									<p class="post-date"><?php echo $post->post_date ?></p>
								</div>
						</div>
                	</div>
					<div class="post-container">
						<button onclick="createClientDay(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Telli kliendipäev</button>
						<button id="post-button" onclick="editPost(<?php echo $post->ID ?>)">Muuda</button>
						<button onclick="deleteNotification(<?php echo $post->ID ?>, '<?php echo $post->type ?>', '<?php echo $post->image ?>', '<?php echo $post->post_title ?>', <?php echo $post->price ?>, '<?php echo $post->post_date ?>')" id="post-button">Kustuta</button>
					</div>
						<?php
					}
				?>
			</div>
		</div>



		<div>
			<h3 class="section-title">Kasutaja andmed</h3>
			<div class="user-info">
				<form>
					<div class="form-inner">
						<div class="form-section">
                            <div class="avatar-div">
                                <div class="avatar-image-div">
                                    <img style="border-radius: 100%; width: 100%; height: 100%;" src="<?php echo $user_avatar?>"/>
                                </div>
                                <button type="button" class="edit-avatar-button" onclick="updateAvatar('<?php echo $user_avatar?>')"><i class="fa-solid fa-pencil"></i></button>
                            </div>
                            <input type="file" id="avatar-input" name="avatar" accept="image/png, image/jpeg" hidden />
                        </div>
						<div class="form-section">
							<label>Nimi*</label>
							<input id="nimi" value="<?php echo $user_firstname?>"/>
							<label>Perekonnanimi*</label>
							<input id="perekonnanimi" value="<?php echo $user_lastname?>"/>
						</div>
						<div class="form-section">
							<label>Isikukood*</label>
							<input id="isikukood" value="<?php echo $user_isikukood ?>"/>
							<label>E-mail*</label>
							<input id="email" value="<?php echo $user_email?>"/>
						</div>
						<div class="form-section">
							<label>Telefon*</label>
							<input id="telefon" value="<?php echo $user_telefon?>"/>
							<label>Suhtluskeeled</label>
							<select>
								<option>Eesti</option>
								<option>Vene</option>
								<option>Inglise</option>
							</select>
						</div>
					</div>
					<div class="form-button-container">
						<button onclick="updateUser()" id="form-button" type="button">Salvesta</button>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php return ob_get_clean();

	if (is_page('user-interface')) {
		echo do_shortcode('[user_interface]');
	}
}
add_shortcode("user_interface", "user_interface");
?>