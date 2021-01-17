<?php
/**
 * Plugin Name: Latest posts
 * Description: Display latest posts with thumbnail, excerpt, date
 * Author:      Oleg German
 * Version:     1.0
 */

class Latest_Posts_With_Thumbnail_Widget extends WP_Widget {

function __construct() {
	parent::__construct(
		'latest_posts_with_thumbnail', 
		'Latest posts with thumbnail',
		array( 'description' => 'Display latest posts with thumbnail, excerpt, date' )
	);
}

public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	$posts_per_page = $instance['posts_per_page'];
	global $post;
	$id_current_post = $post->ID;

	$args_wp_query = array(
		'posts_per_page' => $posts_per_page,
		'order'          => 'DESC',
		'post_type'      => array( 'post' ),
		'post_status'    => 'publish',
		'post__not_in'   => [ $id_current_post ],
	);

	$query = new WP_Query( $args_wp_query );
	if ( $query->have_posts() ) :
		echo $args['before_widget'];
		if ( ! empty( $title ) ) :
			echo $args['before_title'] . $title . $args['after_title'];
		endif; ?>
		<ul>
			<?php	while( $query->have_posts() ): $query->the_post();	?>
				<li>
					<div class="img-holder">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); ?>
						</a>
					</div>
					<div class="textbox">
						<strong class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
						<time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('F j Y'); ?></time>
					</div>
				</li>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</ul>
		<?php echo $args['after_widget'];
	endif;
}

public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	}
	if ( isset( $instance[ 'posts_per_page' ] ) ) {
		$posts_per_page = $instance[ 'posts_per_page' ];
	}
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'textdomain'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Count posts:', 'textdomain'); ?></label> 
		<input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="number" value="<?php echo $posts_per_page; ?>" />
	</p>
	<?php 
}

public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : 3;
	return $instance;
}
}

function latest_posts_with_thumbnail() {
	register_widget( 'Latest_Posts_With_Thumbnail_Widget' );
}
add_action( 'widgets_init', 'latest_posts_with_thumbnail' );