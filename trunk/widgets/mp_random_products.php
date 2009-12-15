<?php 
/*
Plugin Name: Marketplace Random Products Widget
Plugin URI: http://shailan.com/wordpress/plugins/loop-widget
Description: A Custom widget loops through your posts
Version: 0.1
Author: Matt Say
Author URI: http://shailan.com
*/

class mp_random_products_widget extends WP_Widget {
    /** constructor */
    function mp_random_products_widget() {
		$widget_ops = array('classname' => 'mp-random-products', 'description' => __( 'Display random post images' ) );
		$this->WP_Widget('widget_mp_random', __('MP Random Products'), $widget_ops);
		$this->alt_option_name = 'widget_mp_random';	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		
		echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
		<?php $r = new WP_Query( array('showposts' => $count, 'orderby' => 'rand' ) );
		if ($r->have_posts()) {
        ?>		
		
		<ul class="image-list">
        <?php while ($r->have_posts()) : $r->the_post(); ?> 	
            <li>
                <a href="<?php the_permalink() ?>" title="Click on image for details"><img src="<?php echo shailan::get_first_image(); ?>" width="120" height="100" /></a>
            </li>
        <?php endwhile; ?>   
        </ul>
		
		<?php }  echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$type = $instance['type'];
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of items:'); ?> <input class="" size="4" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label><br /> 
		<small>Enter number of posts to be displayed.</small></p>
		
	<div class="widget-control-actions alignright">
	<p><small><a href="http://shailan.com/wordpress/plugins/custom-featured-images">Visit plugin site</a></small></p>
	</div>	
			<div class="clear"></div>
        <?php 
		
		
    }

} // class mp_random_products_widget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("mp_random_products_widget");'));