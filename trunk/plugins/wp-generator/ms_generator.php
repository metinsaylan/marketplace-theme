<?php
/*
Plugin Name: WP-Generator
Plugin URI: http://shailan.com/wordpress/plugins/wp-generator/
Description: Using WP-Generator, you can create your own listing fields and define easily how to use them on your website. After creating your fields <a href="options-general.php?page=wp-generator">here</a>, they will be available on Posts -&gt; Add New Screen for your use. You can use <code>&lt;?php ms_generator(); ?&gt;</code> template tag in your templates to show off your listing data or you can use the Generator Widget added by the plugin. You can configure generator <a href="options-general.php?page=wp-generator">here</a>.
Version: 0.4
Author: Matt Say
Author URI: http://shailan.com
*/

$MSG_path = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
$MSG_path = str_replace('\\','/',$MSG_path );
$MSG_dir  = substr($MSG_path ,0,strrpos($MSG_path ,'/'));

$MSG_realpath = str_replace('\\','/',dirname(__FILE__));
$MSG_siteurl  = get_bloginfo('wpurl');
$MSG_siteurl  = (strpos($MSG_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $MSG_siteurl;
$MSG_fullpath = $MSG_siteurl.'/wp-content/themes/marketplace/plugins/wp-generator/'; 

$MSG_db_version = "1.1";

define('MSG_FULLPATH', $MSG_fullpath);
define('MSG_NAME', 'WP-Generator');
define('MSG_CLASSNAME', 'wpGenerator');
define('MSG_VERSION', '0.4');

// Constants
define('MSG_OPTIONS_DB_VERSION', 'MSG_db_version');
define('MSG_OPTIONS_DISPLAY_LABELS', 'MSG_display_labels');
define('MSG_OPTIONS_TEXTSIZE',50);
define('MSG_PREFIX', '');

// Messages
define('MSG_MESSAGE_REMOVE','-1');


// Table prefix
global $wpdb;
$ctrls = $wpdb->prefix . 'ms_generator';
define('MSG_CONTROLS', $ctrls);

// Define wpGenerator Class
include_once( 'ms_generator.class.php' );
include_once( 'ms_generator.SearchWidget.php' );

// Create instance 
if (class_exists(MSG_CLASSNAME)) { $msGeneratorInstance = new wpGenerator(); } 

// Define actions and filters
if (isset($msGeneratorInstance)) {
    add_action('admin_menu', array(&$msGeneratorInstance, 'adminMenu'));	
	add_action('admin_head', array(&$msGeneratorInstance, 'addMetaBox') ); 
	add_action('edit_post', array(&$msGeneratorInstance, 'saveMeta' ) );
	add_action('save_post', array(&$msGeneratorInstance, 'saveMeta' ) );
	add_action('publish_post', array(&$msGeneratorInstance, 'saveMeta' ) );
	add_action('wp_head', array(&$msGeneratorInstance, 'getStylesheet'));
	add_action('ms_gen', array(&$msGeneratorInstance, 'writeFields' ) );
	
}



function ms_generate(){
	global $post;
	$thePostID = $post->ID;
	wpGenerator::writeFields($thePostID); 
}

function ms_generator(){
	ms_generate();
}

?>