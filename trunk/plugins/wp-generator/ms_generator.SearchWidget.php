<?php 

/*
Plugin Name: MS Generator Search
Plugin URI: http://shailan.com/wordpress/plugins/loop-widget
Description: A Custom widget loops through your posts
Version: 0.1
Author: Matt Say
Author URI: http://shailan.com
*/

/**
 * msgenerator_SearchWidget Class
 */
class msgenerator_SearchWidget extends WP_Widget {
    /** constructor */
    function msgenerator_SearchWidget() {
		$widget_ops = array('classname' => 'msgenerator-search', 'description' => __( 'Search your custom fields' ) );
		$this->WP_Widget('ms-generator-search', __('MS Generator Search'), $widget_ops);
		$this->alt_option_name = 'widget_msgenerator_search';	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		
		$fieldnames = array();
		$fields = array();
		// Query fields from database.
		$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER by ListingID, id ASC";
		$db_process = mysql_query( $sql );
		
		
		while( $db_records = mysql_fetch_array( $db_process ) ) {
			$fieldnames[] = $db_records['name'];
			$fieldname = $db_records['name'];
			$fields[$fieldname] = array('name'=>$db_records['name'], 'value' => $db_records['value'], 'default' => $db_records['default'], 'before'=> $db_records['before'], 'after'=>$db_records['after'] );
		
		}
        
		echo $before_widget; ?>
        <?php if ( $title )
			echo $before_title . $title . $after_title; ?>
		<form name="ms_generator_searchform">
		
		<?php
			foreach($fieldnames as $field){
				echo $field . "<br />";
			}
		?>    
		<p>
		<input type="submit" name="submit" value="Search" />
		</p>
		</form>
		<?php echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }

} // class msgenerator_SearchWidget

// register msgenerator_SearchWidget widget
add_action('widgets_init', create_function('', 'return register_widget("msgenerator_SearchWidget");'));