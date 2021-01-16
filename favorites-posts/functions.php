<?php

function favorites_posts( $content ){
	if ( ! is_single() || ! is_user_logged_in() ) return $content;
	global $post;
	if ( is_favorite_post( $post->ID ) ){
		$loader_src = plugins_url( '/img/loader.gif', __FILE__ );
		return '<p class="block-add-favorite-post"><span class="fp-loader"><img width=30px src="' . $loader_src . '" /></span><a data-action="remove" href="#" class="favorites-link">' . __( 'Remove from favorites', 'textdomain' ) . '</a></p>' . $content;
	}
	else {
		return '<p class="block-add-favorite-post"><span class="fp-loader"><img width=30px src="' . $loader_src . '" /></span><a data-action="add" href="#" class="favorites-link">' . __( 'Add to favorites posts', 'textdomain' ) . '</a></p>' . $content;
	}
}

function favorites_posts_enqueue_style_script(){
	if ( ! is_single() || ! is_user_logged_in() ) return;
		wp_enqueue_style( 'fp-style', plugins_url( 'css/style.css', __FILE__ ) );
		wp_enqueue_script( 'fp-main', plugins_url( 'js/main.js', __FILE__ ), array( 'jquery' ), time(), true );
		global $post;
		wp_localize_script( 'fp-main', 'obj', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'favoriteposts' ),
			'postId'  => $post->ID,
		) );
}

function favorites_posts_enqueue_style_script_admin( $hook ){
	if ( $hook == 'index.php' ){
		wp_enqueue_style( 'fp-style-admin', plugins_url( 'css/admin-style.css', __FILE__ ) );
		wp_enqueue_script( 'fp-main-admin', plugins_url( 'js/admin-main.js', __FILE__ ), array( 'jquery' ), time(), true );
		wp_localize_script( 'fp-main-admin', 'obj', array(
			'nonce'   => wp_create_nonce( 'favoriteposts' ),
		) );
	}
}

function wp_ajax_favoritePosts(){
	if ( ! wp_verify_nonce( $_POST['security'], 'favoriteposts' ) ) :
		_e( 'Error security', 'textdomain' );
		return;
	endif;
	$post_id = (int)$_POST['postId'];
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	if ( $_POST['flag'] == 'add' ) :
		if ( ! is_favorite_post( $post_id ) ){
			if ( add_user_meta( $current_user_id, 'id_favorite_posts', $post_id ) ){
				_e( 'Added post in favorite', 'textdomain' );
			};
		};
	elseif ( $_POST['flag'] == 'remove' ) :
		if ( is_favorite_post( $post_id ) ){
			if ( delete_user_meta( $current_user_id, 'id_favorite_posts', $post_id ) ){
				_e( 'Deleted post from favorite', 'textdomain' );
			};
		};
	endif;
	wp_die();
}

function deleteFavoritePostAdmin(){
	if ( ! wp_verify_nonce( $_POST['security'], 'favoriteposts' ) ) :
		_e( 'Error security', 'textdomain' );
		return;
	endif;
	$post_id = (int)$_POST['post_id'];
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
		 if ( is_favorite_post( $post_id ) ){
			if ( delete_user_meta( $current_user_id, 'id_favorite_posts', $post_id ) ){
				_e( 'Deleted', 'textdomain' );
			};
		 };
	wp_die();
}

function is_favorite_post( $post_id ){
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$id_favorite_posts = get_user_meta( $current_user_id, 'id_favorite_posts' );
	foreach ($id_favorite_posts as $id_favorite_post) {
		if ( $id_favorite_post == $post_id ) {
			return true;
		}
	}
	return false;
}

function add_dashboard_widgets_favorite_posts() {
	wp_add_dashboard_widget( 'List_of_favorites_posts', 'List of favorites post', 'list_of_favorites_posts_dashboard_widget_function' );
}

function list_of_favorites_posts_dashboard_widget_function() {
	$user_data = wp_get_current_user();
	$user_id = $user_data->ID;
	$favorites_posts_array = get_user_meta( $user_id, 'id_favorite_posts' );
	if ( ! $favorites_posts_array ) {
		_e( '<h3>Not favorites posts</h3>', 'textdomain');
		return;
	} else {
		$loader_src = plugins_url( '/img/loader.gif', __FILE__ ); ?>
		<ul>
			<?php foreach ( $favorites_posts_array as $post_id ) { ?>
				<li>
					<a href="<?php echo get_permalink( $post_id ); ?>" target="_blank">
						<?php echo get_the_title( $post_id ); ?>
					</a>
					<span><a class="fp-admin-delete" data-post=<?php echo $post_id; ?> href="#">&#10008</a></span>
					<span class="fp-loader"><img width=15px src="<?php echo $loader_src; ?>" /></span>
				</li>
			<?php } ?>
		</ul>
		<div class="block-button-delete-all">
			<button class="button" id="button-delete-all" ><?php _e( 'Delete all', 'textdomain' ); ?></button>
			<span class="fp-loader"><img width=15px src="<?php echo $loader_src; ?>" /></span>
		</div>
		
	<?php }
}

function deleteAllFavoritePostAdmin(){
	if ( ! wp_verify_nonce( $_POST['security'], 'favoriteposts' ) ) :
		_e( 'Error security', 'textdomain' );
	endif;
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	if ( delete_metadata( 'user', $current_user_id, 'id_favorite_posts' ) ){
		echo 1;
	} else {
		echo 0;
	};
	wp_die();
}