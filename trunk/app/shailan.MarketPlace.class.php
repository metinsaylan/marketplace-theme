<?php // is the love
/*
	Version: 0.1
	Author: Matt Say
	Author URI: http://shailan.com
	
	The CSS, XHTML and design is released under Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License:
	http://creativecommons.org/licenses/by-nc-sa/3.0/
	
	(CC) BY NC SA
	Some rights reserved.
	
*/

$shailanMP_theme_data = get_theme_data(TEMPLATEPATH.'/style.css');

define('MSMP_TITLE','MarketPlace');
define('MSMP_CLASSNAME','shailan_marketPlace');
define('MSMP_VERSION', trim($shailanMP_theme_data['Version']));
define('MSMP_DOMAIN','shailanMP_domain');

/* Define Options Hash */
define('MSMP_OPTION_VERSION', 'shailanMP_version');
define('MSMP_OPTION_LOGO', 'shailanMP_logo');

class shailan_marketPlace{

	function init(){
		// Load the localisation text
		load_theme_textdomain(MSMP_DOMAIN);
		
		// Check installed version, upgrade if needed
		$MSMP_version = get_option(MSMP_OPTION_VERSION);

		if ( $MSMP_version === false )
			shailan_marketPlace::install();
		elseif ( version_compare($MSMP_version, MSMP_VERSION, '<') )
			shailan_marketPlace::upgrade($MSMP_version);
			
		DB_CustomSearch_Widget::init();
		wpGenerator::init();
		
			
		// Register our scripts with script loader
		//  shailan_marketPlace::register_scripts();
	}
	
	function print_styles(){ 
		// print styles at the header
	}
	
	function add_options_menu(){
		//$page = add_theme_page(__('Marketplace Options',), __('Marketplace Options',MSMP_DOMAIN), 'edit_themes', 'shailan-mp-options', );
		
		$page = add_menu_page(__('Marketplace Options', MSMP_DOMAIN), __('Marketplace', MSMP_DOMAIN), 'edit_themes', 'mp-theme-settings', array(MSMP_CLASSNAME, 'theme_options'), 'images/generic.png');
		
		add_submenu_page('mp-theme-settings', __('Market Place Theme Options', MSMP_DOMAIN), __('Theme Settings', MSMP_DOMAIN), 'edit_themes', 'mp-theme-settings');

		// add_action( "admin_head-$page", array('K2', 'admin_head') );
		// add_action( "admin_print_scripts-$page", array('K2', 'admin_print_scripts') );

		if ( function_exists('add_contextual_help') ) {
			add_contextual_help($page,
				'<a href="http://shailan.com/wordpress/themes/marketplace/">' .  __('View Theme Page', MSMP_DOMAIN) . '</a><br />' .
				'<a href="http://shailan.com/contact">' .  __('Contact Author', MSMP_DOMAIN) . '</a><br />'
				);
		}
	
	}
	
	function theme_options(){
	// Read options 
	// $style = get_option(MSMP_OPTION_STYLE);	
	$logo = get_option(MSMP_OPTION_LOGO);
	
	if(wp_verify_nonce($_POST['_wpnonce'])){ // Form submitted. Save settings.
		
		$logo = $_POST[MSMP_OPTION_LOGO];
		update_option(MSMP_OPTION_LOGO, $logo); // TODO: Validate before saving
		
		//$style = $_POST[MSMP_OPTION_STYLE];
		//update_option(MSMP_OPTION_STYLE, $style);
		
		?>
		<div class="updated"><p><strong><?php _e('Options saved.', 'shailanDropdownMenu_domain'); ?> </strong></p></div>
		
		<?php
	}
	
	?>
	<div class="wrap">
<h2><?php echo esc_html( MSMP_TITLE . ' ' . MSMP_VERSION ); ?></h2>
<p><?php echo esc_html( MSMP_TITLE); ?> is an easy-to-setup market place theme. Here you can set various theme settings: </p>
<form method="post" action="">
<?php wp_nonce_field(); ?>

<table class="form-table">
<tr valign="top">
<th scope="row"><label for="style"><?php _e('Logo URL') ?></label></th>
<td><input type="text" size="60" class="" name="<?php echo MSMP_OPTION_LOGO; ?>" id="<?php echo MSMP_OPTION_LOGO; ?>" value="<?php echo $logo; ?>" /><br /> <span class="description"><?php _e('Enter your logo URL here. It will be resized to fit.', MSMP_DOMAIN); ?></span></td>
</tr>	
</table>
</div>
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
</p>
<p>Visit <a href="http://shailan.com">shailan.com</a> for more wordpress <a href="http://shailan.com/wordpress/themes">themes</a>, <a href="http://shailan.com/wordpress/plugins">plugins</a> and <a href="http://shailan.com/wordpress/widgets">widgets</a>.</p>
</form>
<p>
<a href="http://shailan.com/wordpress/themes/marketplace"><?php echo esc_html( MSMP_TITLE . ' ' . MSMP_VERSION ); ?></a> by <a href="http://shailan.com">shailan</a></a> &copy; 2009 
</p>
</div>  <?php
	
	}
	
	function install(){
		// Add default options to the database
		add_option(MSMP_OPTION_VERSION, MSMP_VERSION);
	}
	
	function upgrade($previous) {
		// Delete deprecated options

		// Install options
		shailan_marketPlace::install();

		// Update the version
		update_option(MSMP_OPTION_VERSION, MSMP_VERSION);
	}
	
	
	function logo(){
		$logo = get_option(MSMP_OPTION_LOGO);
		$name = get_bloginfo('name');
		$home = get_bloginfo('url');
		
		if(strlen($logo)>0){
			echo '<a href="'.$home.'"><img src="'.$logo.'" width="370" class="sitelogo" title="'.$name.'" alt="'.$name.'" /></a>';
		} else {
			$desc = get_bloginfo('description');
			echo '<h1><a href="'.$home.'">'.$name.'</a></h1>
		<div class="description">'.$desc.'</div>';
		}
	}
	
	function version(){
		return MSMP_VERSION;
	}
	
	//function get_logo_url(){ } <- TODO
	
}; //class shailan_marketPlace

add_action('wp_head',array(MSMP_CLASSNAME, 'print_styles'));
add_action('admin_menu', array(MSMP_CLASSNAME, 'add_options_menu'));
?>