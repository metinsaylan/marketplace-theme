<?php 
/*
Plugin Name: Shailan Loop Images Widget
Plugin URI: http://shailan.com/wordpress/plugins/loop-widget
Description: A Custom widget loops through your posts
Version: 0.1
Author: Matt Say
Author URI: http://shailan.com
*/

class mp_featured_products_widget extends WP_Widget {
    /** constructor */
    function mp_featured_products_widget() {
		$widget_ops = array('classname' => 'mp-featured-products', 'description' => __( 'Display post images for a category' ) );
		$this->WP_Widget('widget_mp_featured', __('MP Featured Products'), $widget_ops);
		$this->alt_option_name = 'widget_mp_featured';	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];		
		$cat = $instance['category'];
		$banner = (bool) $instance['banner'];
		
		echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
		<?php $r = new WP_Query( array('showposts' => $count, 'category_name' => $cat ) );
		if ($r->have_posts()) {
        ?>		
		
		<ul class="image-list">
        <?php while ($r->have_posts()) : $r->the_post(); ?> 	
            <li>
                <a href="<?php the_permalink() ?>" title="Click on image for details"><img src="<?php echo marketplace::get_first_image(); ?>" width="120" height="100" /></a>
            </li>
        <?php endwhile; ?>
		<?php 
			if($banner){
				echo '<li>';
					echo '<a href="'.marketplace::get_advertise_url().'" alt="Click!"><img src="'.get_bloginfo('template_directory').'/images/featured-banner.png" width="120" height="100" /></a>';
				echo '</li>';
			};
		?>		
        </ul>
        
		<?php } else { ?>
			<p>No posts found in category: <?php echo $cat; ?></p>
		
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
		$category = $instance['category'];
		$banner = (bool) $instance['banner'];
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:'); ?><select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" > 		
 <?php 
  $categories = get_categories(''); 
  foreach ($categories as $cat) {  
  	$option = '<option value="'.$cat->category_nicename .'" '. ( $cat->category_nicename == $instance['category'] ? ' selected="selected"' : '' ) .'>';
	$option .= $cat->cat_name;
	$option .= '</option>\n';
	echo $option;
  }
 ?>
</select></label></p>
			
			<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of items:'); ?> <input class="" size="4" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label><br /> 
			<small>Enter number of posts to be displayed.</small></p>
			
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('banner'); ?>" name="<?php echo $this->get_field_name('banner'); ?>"<?php checked( $banner ); ?> />
		<label for="<?php echo $this->get_field_id('banner'); ?>"><?php _e( 'Include banner' ); ?></label><br />
		
	<div class="widget-control-actions alignright">
	<p><small><a href="http://shailan.com/wordpress/plugins/custom-featured-images">Visit plugin site</a></small></p>
	</div>	
			<div class="clear"></div>
        <?php 
		
		
    }

} // class mp_featured_products_widget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("mp_featured_products_widget");'));