<?php
$shailanMP_theme_data = get_theme_data(TEMPLATEPATH.'/style.css');

define('THEME_NAME', 'MarketPlace');
define('THEME_AUTHOR', $shailanMP_theme_data['Author'] );
define('THEME_URI', $shailanMP_theme_data['URI'] );
define('THEME_VERSION', trim($shailanMP_theme_data['Version']) );

automatic_feed_links();

/**
 *  Register theme sidebars
 */
if ( function_exists('register_sidebar') ) {
	$args = array(
    'name'          => 'Header',
    'id'            => 'header-widgets-top',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>' );
	register_sidebar( $args );
	
	$args = array(
    'name'          => 'Header Banners',
    'id'            => 'header-banners-top',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>' );
	register_sidebar( $args );
	
	$args = array(
    'name'          => 'Sidebar Left',
    'id'            => 'sidebar-left',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>' );
	register_sidebar( $args );
	
	$args = array(
    'name'          => 'Sidebar Right',
    'id'            => 'sidebar-right',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>' );
	register_sidebar( $args );
	
	$args = array(
    'name'          => 'Footer',
    'id'            => 'sidebar-footer',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>' );
	register_sidebar( $args );
	
}

/** 
 * Base theme class
 */ 
include_once('app/marketplace.php');

/** 
 * Widgets
 */ 
include_once('widgets/shailan.BannerWidget.php');
include_once('widgets/mp_featured_products_widget.php');
include_once('widgets/mp_random_products_widget.php');
include_once('widgets/shailan.php');

/** 
 * Plugins
 */ 
include_once('plugins/wp-custom-fields-search/wp-custom-fields-search.php');
include_once('plugins/wp-generator/ms_generator.php');


// Initialize theme
marketplace::init();

?>