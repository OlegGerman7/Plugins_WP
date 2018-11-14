<?php
/*
Plugin Name: Похожие записи
Description: Выводит 4 похожие записи (связывается по категории)
Version: 1.0
Author: Герман О.Ю.
*/
add_action('wp_enqueue_scripts','ger_style');
function ger_style(){
	wp_register_style('style', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('style');
}

add_filter('the_content', 'ger_rel');
function ger_rel($content){
	if( !is_single() ) return $content;
	$id = get_the_ID();
	$categories = get_the_category($id);
	foreach($categories as $category){
		$cat[] = $category->cat_ID;
	}
	
	$arg = array(
		'posts_per_page'=>4,
		'category__in'=>$cat,
		'post__not_in'=>array($id)
		);
	$ger_rel = new WP_Query($arg);
	if( $ger_rel->have_posts()): 
		$content.='<div><h4>Похожие записи: </h4>';
		while($ger_rel->have_posts()): $ger_rel->the_post();
			if( has_post_thumbnail()){
				$img = get_the_post_thumbnail(get_the_ID(), array(150,150), array('title'=>get_the_title(), 'alt'=>get_the_title()));
				$content.= '<a href="'.get_permalink().'">'.$img.'</a>';
			} else{
				$img = '<img src="'.plugins_url('img/no-image.gif', __FILE__).'">';
				$content.= '<a href="'.get_permalink().'">'.$img.'</a>';
			}
		endwhile;
	$content.='</div>';
	wp_reset_query();
	endif;
return $content;
}
?>