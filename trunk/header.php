<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page">
<div id="header" role="banner">
	<div id="headerimg">
		<?php marketplace::logo(); ?>
	</div>
	<div id="banners-main">
		<?php if(!dynamic_sidebar('header-widgets-top')){
			echo '<a href="'.marketplace::get_advertise_url().'" alt="Click!"><img src="'.get_bloginfo('template_directory').'/images/top-banner.png" width="600" height="90" /></a>';
		}; ?>
	</div>
		<div class="clear"></div>
	<div id="banners-menu">
		<?php dynamic_sidebar('header-banners-top'); ?>
		<div class="clear"></div>
	</div>
</div>
<hr />
<div id="sidebar-left">
	<?php if(!dynamic_sidebar('sidebar-left')){ ?>
		
		<?php if(function_exists('wp_custom_fields_search')) 
					wp_custom_fields_search(); ?>
		
	<?php } ?>
</div>

<div id="content-wrapper">
<div class="menu">
		<ul class="top-menu">
		<li class="<?php if ( is_front_page() && !is_paged() ): ?>current_page_item<?php else: ?>page_item<?php endif; ?> blogtab"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home'); ?></a></li>
		<?php 
		$list_settings = array(
		'depth' => 1, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => '', 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title',
		'link_before' => '', 'link_after' => ''
	);
		
		wp_list_pages($list_settings); ?>	
	<?php
		// Display an Register tab if registration is enabled or an Admin tab if user is logged in
		wp_register('<li class="admintab">','</li>');
	?>		
		</ul>
	</div>

