<?php
/**
 * Plugin Name: Favorites posts
 * Description: Add favorites posts for users, display them in admin panel
 * Author:      Oleg German
 * Version:     1.0
 */

require __DIR__ . '/functions.php';

add_filter( 'the_content', 'favorites_posts' );

add_action( 'wp_enqueue_scripts', 'favorites_posts_enqueue_style_script' );

add_action( 'wp_dashboard_setup', 'add_dashboard_widgets_favorite_posts' );

add_action( 'admin_enqueue_scripts', 'favorites_posts_enqueue_style_script_admin' );

add_action( 'wp_ajax_deleteFavoritePostAdmin', 'deleteFavoritePostAdmin' );

add_action( 'wp_ajax_favoritePosts', 'wp_ajax_favoritePosts' );

add_action( 'wp_ajax_deleteAllFavoritePostAdmin', 'deleteAllFavoritePostAdmin' );
