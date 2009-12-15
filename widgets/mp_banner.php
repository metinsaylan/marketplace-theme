<?php 
/*
Plugin Name: Shailan Banner Widget
Plugin URI: http://shailan.com/wordpress/plugins/loop-widget
Description: A Custom widget loops through your posts
Version: 0.1
Author: Matt Say
Author URI: http://shailan.com
*/

/**
 * Banner Widget Class
 */
class shailan_BannerWidget extends WP_Widget {
    /** constructor */
    function shailan_BannerWidget() {
		$widget_ops = array('classname' => 'shailan_banner_widget', 'description' => __( 'Banner Widget' ) );
		$this->WP_Widget('banner', __('Marketplace Banner'), $widget_ops);
		$this->alt_option_name = 'widget_banner';	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$url = $instance['url'];
		//$width = $instance['width'];
		//$height = $instance['height'];
		$link = $instance['link'];
		
        echo $before_widget; ?>
		
		<?php if ( $title )
			echo $before_title . $title . $after_title; ?>
			
		<a href="<?php echo $link; ?>"><img src="<?php echo $url; ?>" <?php /* width="<?php echo $width; ?>" height="<?php echo $height; ?>" */ ?> alt="" title="<?php echo $title; ?>" class="banner-image" /></a>
		
		<?php echo $after_widget; 
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		$url = $instance['url'];
		//$width = $instance['width'];
		//$height = $instance['height'];
		$link = $instance['link'];
				
		if(strlen($url)>0){
        ?>
            <p><div class="thumb" style="overflow:hidden; height:40px; position:relative; float:right;">Preview: <img src="<?php echo $url ?>" width="40" /></div></p>
			
		<?php } ?>
		
			<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Image URL:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" /></label><br /> 
		<small>Enter image URL here. <br />(Eg. http://shailan.com/mybanner.jpg)</small></p>
			<!-- <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" /></label></p> -->
			<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label><br /> 
		<small>Enter link address here.</small></p>
		
	<div class="widget-control-actions alignright">
	<p><small><a href="http://shailan.com/wordpress/plugins/custom-image-banner">Visit plugin site</a></small></p>
	</div>		
		
			<div class="clear"></div>
			
        <?php 
		
		
    }

} // class shailan.LoopWidget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_BannerWidget");'));