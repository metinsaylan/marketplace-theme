<?php
if (!class_exists("wpGenerator")) {
	class wpGenerator {
	 var $adminOptionsName = "wpGeneratorAdminOptions";
	 
	 var $options_use_css = false;
	 var $wpCompat;
	
		// Constructor
		function wpGenerator(){
		
		}
		
		function init(){
			wpGenerator::getAdminOptions();
			
			$installed = get_option("wp-generator-installed");
			
			if($active != "true"){
				wpGenerator::install();
			}
		}
		
		function install(){
				wpGenerator::createFieldsTable();  // create database table for generator
				// Set active state to database (Debugging only)
				update_option("wp-generator-installed", "true");	
		}
		
		// Add settings page
		function adminMenu(){
			if (function_exists('add_options_page')) {
				//add_options_page('Settings for ' . MSG_NAME , MSG_NAME, 'administrator', 'wp-generator', array(MSG_CLASSNAME, 'getOptionsPage'));
				add_submenu_page('mp-theme-settings', __('MP Product Listing Fields', MSMP_DOMAIN), __('Listing Fields', MSMP_DOMAIN), 'edit_themes', 'mp-listing-fields',  array(&$this,'getOptionsPage'));
			}
		}
		
		// Let's set it up!
		function activate() {
			wpGenerator::createFieldsTable();  // create database table for generator
            $this->getAdminOptions();
			// Set active state to database (Debugging only)
			update_option(MSG_CLASSNAME, "active");
        }
		
		// Just leave this place..
		function deactivate(){
			global $wpdb;
			// Set active state to database (Debugging only)
			update_option(MSG_CLASSNAME, "inactive");
			// Drop table
			$sql = "DROP TABLE `".MSG_CONTROLS."`";
			mysql_query($sql);
			// and exit silently..
			return true;
		}
		
		// Drop table and recreate
		function reinstall(){
			// Drop database table
			$sql = "DROP TABLE `".MSG_CONTROLS."`";
			mysql_query($sql);
			// Re-create the database table
			wpGenerator::createFieldsTable();
		}
	
	    function getAdminOptions(){
			//load options and reload the administration form
		}
		
		// Creates database tables needed for the custom fields.
		function createFieldsTable(){		
		  global $wpdb;
		  global $MSG_db_version; 
			if($wpdb->get_var("SHOW TABLES LIKE '".MSG_CONTROLS."'") != MSG_CONTROLS){
			// NOTABLE. Create it.
				$sql = "CREATE TABLE `".MSG_CONTROLS."` (
						  `id` int(12) NOT NULL auto_increment,
						  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
						  `type` enum('text','dropdown','checkbox','radio','price', 'image') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
						  `values` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
						  `default` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
						  `req` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
						  `position` int(5) NOT NULL,
						  `flag` enum('1','2') NOT NULL,
						  `ListingID` int(11) NOT NULL,
						  PRIMARY KEY  (`id`)
						);";
				require_once(ABSPATH . "wp-admin/includes/upgrade.php");
				dbDelta($sql);
				
				return true;
			} 
			return false;
		}
		
		// Adds meta box for the custom fields.
		function addMetaBox(){
			add_meta_box('mscf-meta', 'Marketplace Listing Fields', array(MSG_CLASSNAME, 'metaBox') , 'post', 'normal');
			add_meta_box('mscf-meta', 'Marketplace Listing Fields', array(MSG_CLASSNAME, 'metaBox') , 'page', 'normal');
		}
		
		// Create metabox contents depending on current configuration.
		function metaBox(){
			// Get fields from database
			$fields = wpGenerator::getFields();
			// Display appropriate form data
			if( $fields == null) {
				echo '<p><big>No listing fields added yet. <a href="admin.php?page=mp-listing-fields">Add Listing fields here</a>. </big></p>';
				return;
			}
			
			// NONCE
			$out = '<input type="hidden" name="ms-generator-gui-verify-key" id="ms-generator-gui-verify-key"
			value="' . wp_create_nonce('ms-generator-gui') . '" />';
			
			//$out .= $top;
			$out .= '<table class="editform">';
			
			foreach( $fields as $title => $data ) {
				$out .= wpGenerator::makeField( $title, $data[ 'default' ], $data['values'], $data['req'], $data['type'] );
			}
			
			$out .= '</table>';
			
			$out .= '<p>You can add more listing fields at <a href="admin.php?page=mp-listing-fields">WP-Generator Settings page</a>.</p>';
			//$out .= $bottom;
			echo $out;
		}
		
				// Returns input for the field.
		function makeField( $name, $default ='', $values='', $req=0, $type='text' ) {
			$title = $name;
			$name = 'msg_' . wpGenerator::sanitizeName( $name );
			
			// Get custom field if opened.
			//if( isset( $_REQUEST[ 'post' ] ) ) {
			  $mvalue = get_post_meta( $_REQUEST[ 'post' ], MSG_PREFIX. $title );
			  $mvalue = $mvalue[ 0 ];
			//} elseif($default!='') {
			//  $mvalue = $default;
			//}
			
			if(empty($mvalue)){$mvalue = $default;};
			
			$out = '<tr>' .
			  '<th scope="row">' . $title . ' </th>' .
			  '<td>';

			switch($type){
				case 'text':
					$out .= '<input id="' . $name . '" name="' . $name . '" value="' . attribute_escape($mvalue) . '" type="text" size="'.MSG_OPTIONS_TEXTSIZE.'" />';
					break;
				case 'dropdown'	:
					$opts = explode(",", $values);
					$out .= '<select id="' . $name . '" name="' . $name . '">';
					
					foreach($opts as $opt){
						$out .= '<option name="'.$opt.'" value="'.$opt.'" ';
						if($mvalue===$opt){ $out .= ' selected="selected"'; };
						$out .= '>'.$opt.'</option>';
					}
					
					$out .= '</select> (Selected: ' . $mvalue . ')';			
					break;
				case 'checkbox'	:
					$out .='<input type="checkbox" name="'.$name.'" value="on" '.(($mvalue==on) ? ' checked="checked"': ''  ).' >';
					break;
				case 'radio':
					$out .= '<fieldset>';
					$opts = explode(",", $values);
					$first = true;
					foreach($opts as $opt){
						$out .= ' <input type="radio" name="'.$name.'" value="'.$opt.'" ';
						if($mvalue==$opt || ($mvalue=='' && $first)){ $out .= ' checked="checked"'; };
						$out .= ' label="'.$opt.'" /> '. $opt;
						$first = false;
					}
					$out .= '</fieldset>';
					break;				
				case 'price':
					$out .=  $values. '<input id="' . $name . '" name="' . $name . '" value="' . attribute_escape($mvalue) . '" type="text" size="'.MSG_OPTIONS_TEXTSIZE.'" />';
					break;			
				case 'image';
					$out .= '<input id="' . $name . '" name="' . $name . '" value="' . attribute_escape($mvalue) . '" type="text" size="'.MSG_OPTIONS_TEXTSIZE.'" />';
					break;
				default:
					$out .= 'This field type not implemented yet.';
			}
			
			if($req){ $out .= '<span class="required" style="color:#ff0000;">*required</span>'; };
			
			$out .= '</td></tr>';
			return $out;
		}
		
		// Save meta data of the post.
		function saveMeta( $id ) {  
			global $wpdb;
			
			// Get the id
			if( !isset( $id ) )
			  $id = $_REQUEST[ 'post_ID' ];
			
			// Check for capabilities
			if( !current_user_can('edit_post', $id) )
				return $id;
				
			// Verify source
			if( !wp_verify_nonce($_REQUEST['ms-generator-gui-verify-key'], 'ms-generator-gui') )
				return $id;
			
			// Get custom fields
			$fields = wpGenerator::getFields();
			
			if ( $fields == null )
				return;
			
			foreach( $fields as $title  => $data) {
			  $name = 'msg_' . wpGenerator::sanitizeName( $title );
			  $title = $wpdb->escape(stripslashes(trim($title)));
			  
			  $title = MSG_PREFIX . $title;
			  
			  $meta_value = stripslashes(trim($_REQUEST[ "$name" ]));
			  
			  if( isset( $meta_value ) && !empty( $meta_value ) ) {
				delete_post_meta( $id, $title );
				add_post_meta( $id, $title, $meta_value );
			  } else {
				delete_post_meta( $id, $title );
			  }
			}
			
			//return $id;
		}
		
		function getFields(){
			$fields = array();
			// Query fields from database.
			$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER by ListingID, id ASC";
			$db_process = mysql_query( $sql );
			
			// Add results to the fields array.
			while( $db_records = mysql_fetch_array( $db_process ) ) {
				$fieldname = $db_records['name'];
				$fields[$fieldname] = array( 'type' => $db_records['type'], 'values' => $db_records['values'], 'default'=> $db_records['default'], 'req'=>$db_records['req'] );
			}
			// return them as an array			
			return $fields;
		}
		
		function writeFields($args = ''){
			global $wpdb, $post;
			
			$defaults = array(
				'list_empty' => true,
				'show_labels' => true,
				'style' => 'list',
				'echo' => true
			);

			$r = wp_parse_args( $args, $defaults );
			extract($r, EXTR_SKIP);
			
			// Option names
			$show_labels_name = 'show_labels';
			$list_empty_name = 'list_empty';
			$style_tag = 'shailan_msgen_style';
	
			// Read options 
			$show_labels = get_option($show_labels_name);
			$list_empty = get_option($list_empty_name);
			$style = get_option($style_tag);
			
			$show_labels = (bool) ($show_labels == 'on');
			
			if($style=='List'){
				echo "<div class='ms-generator-container'><ul class='ms-generator-list'>";
			} elseif($style=='Table') {
				echo "<div class='ms-generator-container'><table class='ms-generator-list'>";
			} else {			
				echo "<span class='ms-generator-container'>";
			}
			
			$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER BY ListingID, id ASC ";
			$db_process = mysql_query($sql);
			$rows = mysql_num_rows($db_process);
			if( $rows > 0 ){
				while( $msg_field = mysql_fetch_array($db_process) ){
				
				if(strlen($msg_field['name'])==0){ // If name is empty delete from database.
					$position = $msg_field['position'];				
					$sql_delete = "Delete from ".MSG_CONTROLS." 
					 where position = '$position' ";
					mysql_query($sql_delete);
				}
				
				
				$meta_value = wpGenerator::get_generator_field($post->ID, $msg_field['name']);
					
				  if(strlen($meta_value)!=0 || ($list_empty && $show_labels)){
					if($meta_value==''){$meta_value="-";}
					
					$classname = 'msg_' . wpGenerator::sanitizeName( $msg_field['name'] );
					
					if($style=='List'){
						echo "<li class='$classname'>";
					} elseif($style=='Table') {
						echo "<tr><td class='$classname'>";
					} else {			
						echo "<span class='$classname'>";
					}					
					
					
					if($show_labels){
					
						echo $msg_field['name'];
						
							if($style=='List'){
								echo " :";
							} elseif($style=='Table') {
								echo "</td><td class='$classname'>";
							} else {			
								//echo "";
							}	
					}
					
					
					// TODO: Type specific format
					
					switch($msg_field['type']){
						case 'checkbox':
							if($meta_value=='on'){
								echo "Yes";
							} else {
								echo "No";
							}					
							break;					
						default:
							echo $meta_value;
					}
					

					if($style=='List'){
						echo "</li>";
					} elseif($style=='Table') {
						echo "</td></tr>";
					} else {			
						echo "</span>";
					}			
				  }
				}
			} else { echo "No fields exist."; }
			
			if($style=='List'){
				echo "</ul></div>";
			} elseif($style=='Table') {
				echo "</table></div>";
			} else {			
				echo "</span>";
			}		
		   
		   return true;
		}
		
		function get_generator_field($id, $name){
			$meta_value = get_post_meta($id, MSG_PREFIX . $name);
			return $meta_value[0];
		}
		
		function sanitizeName( $name ) {
			$name = sanitize_title( $name ); // taken from WP's wp-includes/functions-formatting.php
			$name = str_replace( '-', '_', $name );
			return $name;
		}
		
		function getStylesheet(){
			//echo '<link rel="stylesheet" href="' . MSG_FULLPATH . 'style.css" type="text/css">';
		}
		
		// Prints out the options page where you can edit options for the plugin.
		function getOptionsPage(){
			global $wpdb;
			
			// Option names
			$show_labels_name = 'show_labels';
			$list_empty_name = 'list_empty';
			$style_tag = 'shailan_msgen_style';
	
			// Read options 
			$show_labels = get_option($show_labels_name);
			$list_empty = get_option($list_empty_name);
			$style = get_option($style_tag);
			
			if(wp_verify_nonce($_POST['_wpnonce'])){ // Form submitted. Save settings.
			
				$action = $_POST['action'];
				
				if($action=='Re-install'){
					wpGenerator::reinstall();
					$msg = _('Database re-installed successfully.');
				} elseif ($action=='Save Changes'){
				
					$show_labels = $_POST[$show_labels_name];
					$list_empty = $_POST[$list_empty_name]; 
					$style = $_POST[$style_tag];
								
					update_option($show_labels_name, $show_labels);
					update_option($list_empty_name, $list_empty);				
					update_option($style_tag, $style);
					
					$msg = _('Options saved.');
				}
				
				?> <div class="updated"><p><strong><?php echo $msg; ?></strong></p></div> <?php
				
			} // Form submitted
			
			?>
			
<script type="text/javascript">
/* -------------------------- */
/* Show Hide */
/* -------------------------- */
function cfgShowHide(id) {
  var div = document.getElementById('collsp_'+id);
  var img = document.getElementById('cfgimg_'+id);
  if ( div.style.display == 'none' ) {
  	div.style.display = 'block';
	img.src = '<?php echo MSG_FULLPATH;?>/image/arr2.gif';
  } else { 
  	div.style.display = 'none';
	img.src = '<?php echo MSG_FULLPATH;?>/image/arr1.gif';
  }
}
/* -------------------------- */
/* Trim */
/* -------------------------- */
function trim(str) {
  return str.replace(/^\s+|\s+$/g, '');
}
/* -------------------------- */
/* Save */
/* -------------------------- */
var nocache = 0;

var fields = new Array();

//      field[] = {name of field, type, req}
fields[0] = new Array('name', 'text', 1);
fields[1] = new Array('default', 'text', 0);
fields[2] = new Array('type', 'dropdown', 0);
fields[3] = new Array('values', 'text', 0);
fields[4] = new Array('required', 'checkbox', 0);

function saveField(x){ 
	var position = x;
	var err=false;
	poststr = "position="+x;
	params = new Array();
	for(i=0; i<fields.length; i++){
		var param_tag = x + "_" + fields[i][0];
		//alert(param_tag);
		switch(fields[i][1])
		{
		case 'text':
		  params[i] = document.getElementById(param_tag).value;
		  /*
		  if( params[i].length==0 && fields[i][2] == 1){ 
				alert("Please enter a value for " + fields[i][0] ); err=true; break; };
			*/	
		  poststr += "&" + fields[i][0] + "=" + escape(encodeURI(params[i]));
		  break;
		case 'dropdown':
		  var sel = document.getElementById(param_tag);
		  poststr += "&" + fields[i][0] + "=" + sel.options[sel.selectedIndex].value;
		  break;
		case 'checkbox':
		  if (document.getElementById(param_tag).checked) {
              poststr += "&" + fields[i][0] + "=" + 
                   document.getElementById(param_tag).value;
          } else {
              poststr += "&" + fields[i][0] + "=&";
          }
		  break;
		case 'radio':
		  if (document.getElementById(param_tag).checked) {
            poststr += "&" + fields[i][0] + "=" + 
                   document.getElementById(param_tag).value;
          }
		  break;
		default:
		  //code to be executed if n is different from case 1 and 2
		}
	}
	
	if(!err){
		nocache = Math.random();
		// START AJAX
	document.getElementById(position+'insert_response').innerHTML = '<img src="<?php echo MSG_FULLPATH;?>image/spinner.gif" border="0" align="absmiddle"> Saving...';
	
	http.onreadystatechange = function insertReply() {
									if (http.readyState == 4) {
										 if (http.status == 200) {
											//alert(http_request.responseText);
											result = http.responseText;
											document.getElementById(position+'insert_response').innerHTML = result;            
										 } else {
											alert('There was a problem with the request.');
										 }
										 
										setTimeout('refreshdiv()', 1000);
									  }									
								}
	poststr += '&nocache = '+nocache;
	
	//alert(poststr);
	
	http.open('POST', '<?php echo MSG_FULLPATH; ?>process.php', true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", poststr.length);
    http.setRequestHeader("Connection", "close");
    http.send(poststr);
	}
}
/* ---------------------------- */
/* XMLHTTPRequest Enable */
/* ---------------------------- */
function createObject() {
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_type = new XMLHttpRequest();
	}
	return request_type;
}// end of function 
var http = createObject();
/* -------------------------- */
/* REMOVE */
/* -------------------------- */
function remove( position ){
	//var ret = removeField('recordsArray_'+position);
	boolReturn = confirm("All data related to the field will be lost. Are you sure you want to delete it?");
	
	if ( boolReturn == true ) {
		// Remove from stage
		var d = document.getElementById('stage');
		var olddiv = document.getElementById('recordsArray_'+position);
		d.removeChild(olddiv);
		// Remove from database
		nocache = Math.random();
		
		poststr = 'position='+position+'&msg='+<?php echo MSG_MESSAGE_REMOVE; ?>+'&nocache = '+nocache;
		
		//alert(poststr);
	
	http.onreadystatechange = function insertReply() {
									if (http.readyState == 4) {
										 if (http.status == 200) {
											//alert(http_request.responseText);
											result = http.responseText;
											document.getElementById(position+'insert_response').innerHTML = result;            
										 } else {
											alert('There was a problem with the request.');
										 }
										 
										//setTimeout('refreshdiv()', 1000);
									  }									
								}
	
	
	
	http.open('POST', '<?php echo MSG_FULLPATH; ?>process.php', true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", poststr.length);
    http.setRequestHeader("Connection", "close");
    http.send(poststr);
	
	} else {
		return false;
	}	
}
/* -------------------------- */
/* REFRESH */
/* -------------------------- */
function refreshdiv() {
	var connection = 1;
	nocache = Math.random();
	http.open('GET', '<?php echo MSG_FULLPATH; ?>reload.php?connection='+connection+'&nocache = '+nocache);
	http.onreadystatechange = function() {
								if(http.readyState == 4){
									var response = http.responseText;
									document.getElementById('stage').innerHTML = response;
								}
							}
	http.send(null);
}
/* -------------------------- */
/* ADD FIELD */
/* -------------------------- */
<?php 
			$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER BY ListingID, id ASC ";
			$db_process = mysql_query($sql);
			$rows = mysql_num_rows($db_process);
?>
curFieldNumber = <?php echo $rows; ?>;
function addField(){
	var html = document.getElementById('stage').innerHTML;
	if(html.length>0)html = html;
 // add it to display
	curFieldNumber =curFieldNumber+1;
	x = curFieldNumber;
	x = curFieldNumber;
	var fieldHtml = "<div id=\"recordsArray_"+x+"\"><div class='eHelp' align='left'><div style='float:right'><a onClick=\"saveField('"+x+"');\" style='text-decoration:none; cursor:pointer'>Save</a>&nbsp;|&nbsp;<a onClick=\"remove('"+x+"');\" style='text-decoration:none; cursor:pointer'>Remove</a>&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"cfgShowHide('"+x+"')\" style=\"cursor:pointer;cursor:hand;\"><img src=\"<?php echo MSG_FULLPATH;?>/image/arr1.gif\" id=\"cfgimg_"+x+"\" align=\"absmiddle\" border=\"0\" alt=\"\" /></a></div><div style=\"color: #0066FF;font-weight:bold; font-size:12px\">Field&nbsp;<span style='font-size:10px; font-weight:normal; color:#666666; padding-top:4px;' ></span>&nbsp;&nbsp;<span style='font-size:10px; font-weight:normal; color:#CC0000; padding-top:4px;' id='"+x+"insert_response'> </span></div><div id=\"collsp_"+x+"\" style=\"margin-top:6px; border-top:1px solid #dddddd;\"><table><tr><td><b>Name:</b></td><td><input type='text' id='"+x+"_name' class='widefat' style=\"width:400px;\" value=\"\" ></td></tr><tr><td><b>Default value:</b></td><td><input type='text' name='"+x+"_default' class='widefat' style='width:400px;' id='"+x+"_default' value=\"\" ></td></tr><tr><td><b>Type:</b></td><td><select name=\""+x+"_type\" id=\""+x+"_type\"><option name=\"\" value=\"text\" >Text</option><option name=\"\" value=\"dropdown\" >Dropdown</option><option name=\"\" value=\"checkbox\" >Checkbox</option><option name=\"\" value=\"radio\" >Radio</option><option name=\"\" value=\"Price\" >Price</option><option name=\"\" value=\"image\" >Image</option></select></td></tr>	  <tr><td><b>Values:</b></td><td><input type='text' name='"+x+"_values' class='widefat' style='width:400px;' id='"+x+"_values' value=\"\" ></td></tr><tr><td><b>Required:</b></td><td><input type='checkbox' name='"+x+"_required' id='"+x+"_required' value='1' /></td></tr></table></div></div></div>";
	
	html = html + fieldHtml; 
	document.getElementById('stage').innerHTML = html;
	document.getElementById(curFieldNumber+'insert_response').innerHTML = '* (Not saved yet)'
}
// Update listing for the table.
function updateListing(){
	var connection = 1;
	nocache = Math.random();
	http.open('POST', '<?php echo MSG_FULLPATH; ?>process.php?connection='+connection+'&nocache = '+nocache+ '&action=updateRecordsListings');
	http.onreadystatechange = function() {
								if(http.readyState == 4){
									var response = http.responseText;
									document.getElementById('stage').innerHTML = response;
								}
							}
	http.send(null);
}
</script>
<link rel="stylesheet" href="<?php echo MSG_FULLPATH; ?>admin-style.css" type="text/css">
<div class="wrap">
<h2><?php echo MSG_NAME.' '.MSG_VERSION; ?></h2>
<p>Using <?php echo MSG_NAME; ?> you can create your own listing fields and define easily how to use them on your website. After creating your fields here, they will be available on Posts -&gt; Add New Screen for your use. You can use <code>&lt;?php ms_generator(); ?&gt;</code> template tag in your templates to show off your listing data or you can use the Generator Widget added by the plugin. </p>
<form action="" name="form" method="post">
<?php wp_nonce_field(); ?>
<h3>Settings</h3>
<table class="form-table">
<tr valign="top"><th scope="row">
<label for="<?php echo $show_labels_name; ?>"><?php _e('Show labels:'); ?></label></th>
<td>
<input class="checkbox" type="checkbox" <?php checked($show_labels, true) ?> id="<?php echo $show_labels_name; ?>" name="<?php echo $show_labels_name; ?>" /> <span class="description"><?php _e('Show labels with the listing data.') ?></span></td>
</tr>	
<tr valign="top"><th scope="row">
<label for="<?php echo $list_empty_name; ?>"><?php _e('List empty Fields') ?></label></th>
<td><select name="<?php echo $list_empty_name; ?>">
<option value="Yes" <?php if($list_empty=='Yes'){echo "selected";}; ?> >Yes</option>
<option value="No" <?php if($list_empty=='No'){echo "selected";}; ?> >No</option>
</select> <span class="description"><?php _e('If show labels is checked this will show empty labels when there is no data for a field.') ?></span></td>
</tr>	
<tr valign="top"><th scope="row">
<label for="<?php echo $style_tag; ?>"><?php _e('Display style') ?></label></th>
<td><select name="<?php echo $style_tag; ?>">
<option value="List" <?php if($style=='List'){echo "selected";}; ?> >List</option>
<option value="Table" <?php if($style=='Table'){echo "selected";}; ?> >Table</option>
<option value="Inline" <?php if($style=='Inline'){echo "selected";}; ?> >Inline</option>
</select> <span class="description"><?php _e('How to display the listing fields.') ?></span></td>
</tr>	

</table>

<h3>Listing fields</h3>
<div id="stage" align="center">
<?php include_once('reload.php'); ?>	
</div>
<p class="submit"> 
	<input type="button" name="action" class="button" onclick="addField();" value="Add Field" /> 
</p>

<p class="submit">
<input type="submit" name="action" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /> <input type="submit" name="action" value="Re-install" />
</p>
</form>
<p>
<a href="http://shailan.com/wordpress/plugins/wp-generator/"><?php echo MSG_NAME.' '.MSG_VERSION; ?></a> &copy; 2009 
</p>
</div>  <?php
			
		} // End of Options Page
		
	} // class wpGenerator
}
?>