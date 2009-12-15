<hr />
<div id="footer" role="contentinfo">
	<?php if(!dynamic_sidebar('sidebar-footer')){ ?>
	
	<p>
		<?php bloginfo('name'); ?> is proudly powered by
		<a href="http://wordpress.org/">WordPress</a>
		<br /><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>
		and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</p>
	
	<?php } ?>
</div>

<div id="theme-credits">
	<p>Marketplace <?php marketplace::version(); ?> brought to you by <a href="http://shailan.com">Shailan.com</a></p>
</div>

</div>
	<?php wp_footer(); ?>
</body>
</html>
