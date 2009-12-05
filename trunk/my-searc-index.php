<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">
	
	<?php 
	
		$city = "Bursa";
	
		$r = new WP_Query( array('meta_key' => 'msgen_City', 'meta_compare' => '=', 'meta_value'=>$city ) );
		if ($r->have_posts()) :	?>
		
		<ul class="">
        <?php while ($r->have_posts()) : $r->the_post(); ?> 	
            <li>
                <strong><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></strong>
            </li>
        <?php endwhile; ?>   
        </ul>
              
        <?php
		endif;
	/* READ ONLY 
	
	// postmeta queries
		if ( ! empty($q['meta_key']) || ! empty($q['meta_value']) )
			$join .= " JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) ";
		if ( ! empty($q['meta_key']) )
			$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_key = %s ", $q['meta_key']);
		if ( ! empty($q['meta_value']) ) {
			if ( ! isset($q['meta_compare']) || empty($q['meta_compare']) || ! in_array($q['meta_compare'], array('=', '!=', '>', '>=', '<', '<=')) )
				$q['meta_compare'] = '=';

			$where .= $wpdb->prepare("AND $wpdb->postmeta.meta_value {$q['meta_compare']} %s ", $q['meta_value']);
		}
	
	*/	
		
	/* REMOVE
		$post_ids = (array) $wpdb->get_col( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'msgen_City' AND meta_value = %s", $city) );
		
		$post_ids = join( ',', $post_ids );

		$results = (array) $wpdb->get_results("SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'msgen_City' AND post_id IN ($post_ids)");
		
		*/
		
		
	
	?>

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="entry">
				<div class="controls"><?php edit_post_link('Edit', '', ''); ?></div>
				<table class="information" width="100%" style="padding:10px;"><tr><td colspan="2">
					<?php the_title(); ?>
				</td></tr><tr><td>
					<?php if(function_exists('ms_generator')){ ms_generator(); } else { 
						echo "MS WP-Generator must be installed and activated for listings to work.";
					}?>
				</td><td width="120">
					<a href="<?php the_permalink() ?>" title="Click on image for details"><img src="<?php echo shailan::get_first_image(); ?>" width="120" height="100" /></a>
				</td></tr>
				</table>					
				</div>
			</div>

		<?php endwhile; ?>

		
		<?php if(function_exists('wp_pagenavi')){?>
		
		<div class="navigation">
			<?php wp_pagenavi(); ?>
		</div>
		
		<?php } else { ?>
		
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
		
		<?php } ?>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
