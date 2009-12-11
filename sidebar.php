<?php
/**
 * Marketplace right sidebar.
 */
?>

</div>

	<div id="sidebar-right" role="complementary">
			<?php if(!dynamic_sidebar('sidebar-right')){ ?>
			
			<?php 
				$opts = array ('title'=>'Recently Added','category'=>'','count'=>'5');
				the_widget('mp_featured_products_widget', $opts);
			?>
			
			<?php 
				$opts = array ('title'=>'Random Products', 'count'=>'3');
				the_widget('mp_random_products_widget', $opts);
			?>
			
			<?php } ?>
	</div>

