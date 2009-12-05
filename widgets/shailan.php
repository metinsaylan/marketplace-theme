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

//include_once('shailan.Utilities.php');

class shailan{

	function shailan(){
	
	}
	
	function pages(){
		return wp_list_pages('echo=0');	
	}
	
	function get_first_image() {
	 global $post, $posts;
	 $first_img = '';
	 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	 $first_img = $matches [1][0];
	 if($first_img){
		return $first_img;
	 } else {
		return "No images found";
	 }
	}
	
}; //class shailan

?>