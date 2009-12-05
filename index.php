<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">

	<?php if (have_posts()) : ?>
	
	<h2 class="pagetitle">Latest Additions</h2>

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
