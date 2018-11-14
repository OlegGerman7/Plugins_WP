<?php
/**
 * Plugin Name: Новый тип записей
 * Description: Плагин "my_type_post" создает тип записей mypost и выводит 
 				записи по определенным критериям
 * Author:      Герман О.Ю.
 * Version:     1.0
 *
 */
add_action('init', 'my_custom_init');
function my_custom_init(){
	register_post_type('mypost', array(
		'labels'             => array(
			'name'               => 'Новини', // Основное название типа записи
			'singular_name'      => 'Новина', // отдельное название записи типа 
			'add_new'            => 'Додати нову',
			'add_new_item'       => 'Додати нову',
			'edit_item'          => 'Редагувати новину',
			'new_item'           => 'Нова новина',
			'view_item'          => 'Переглянути новину',
			'search_items'       => 'Знайти новину',
			'not_found'          => 'Новин не знайдено',
			'not_found_in_trash' => 'В кошику новин не знайдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Новини'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 2,
		'menu_icon'          =>'dashicons-images-alt2',
		'supports'           => array('title','editor','thumbnail','custom-fields')
	) );
}
add_shortcode( 'sc', sc_func );

function sc_func($assets, $content){

    $query = new WP_Query( array ( 'post_type' => 'mypost', 'meta_value' => 'popular',
    	'posts_per_page' => '5',  'order' => 'DESC' ) ); ?>

    	<div class="col-md-8 col-sm-12">
  			<?php while($query->have_posts()):$query->the_post();?>
  				<div class="row">
    				<div class="col-md-8 offset-md-2">
    					<?php the_post_thumbnail();?>
          				<h2 class="article-title">
            				<?php the_title(); ?>
          				</h2>
					<?php the_content();?>
    				</div>
  				</div> 
  		<?php endwhile; ?> 
		</div>
<?php } ?>
<?php wp_reset_postdata(); ?>
<?php add_shortcode( 'sc1', sc_func1 );

function sc_func1($assets, $content){
	$query = new WP_Query( array( 'post_type'=> $assets['type'], 
	'post__in' => array( $assets['id'] ) ) ); ?>

    <div class="col-md-8 col-sm-12">
  		<?php while($query->have_posts()):$query->the_post();?>
  			<div class="row">
    			<div class="col-md-8 offset-md-2">
    				<?php the_post_thumbnail();?>
          			<h2 class="article-title">
            			<?php the_title(); ?>
          			</h2>
				<?php the_content();?>
        		</div>
    		</div>
  	</div> 
  		<?php endwhile; ?> 
<?php wp_reset_postdata(); ?>
<?php }

//Кнопки редактона Tiny MCE

if( !function_exists('_add_my_quicktags') ){
function _add_my_quicktags()
{ ?>
<script type="text/javascript">
QTags.addButton( 'fivepost', 'fivepost', '[sc]', '[/sc]' );

QTags.addButton( 'onepost', 'onepost', '[sc1]', '[/sc1]' );
</script>
<?php }
add_action('admin_print_footer_scripts', '_add_my_quicktags');
}
