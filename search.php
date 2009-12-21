<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results</h2>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>


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
					<a href="<?php the_permalink() ?>" title="Click on image for details"><img src="<?php echo marketplace::get_first_image(); ?>" width="180" height="150" /></a>
				</td></tr>
				</table>					
				</div>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">No posts found. Try a different search?</h2>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
