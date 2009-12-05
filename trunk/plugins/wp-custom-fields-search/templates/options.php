<div class='presets-selector'>
<h4><?php _e('Select Preset','wp-custom-fields-search')?></h4>
<ul>
<?php	foreach($presets as $key=>$name){ ?>
<li><a href='<?php echo $linkBase?>&selected-preset=<?php echo $key?>'><?php echo $name?></a></li>
<?php	}	?>
</ul>
<div class="clear"></div>
</div>

<form method='post'><div class='searchforms-config-form'>
<?php echo $hidden?>
		<h4>Edit Preset "<?php echo $presets[$preset]?>"</h4>
		<?php $plugin->configForm($preset,$shouldSave) ?>
		<div class='options-controls'>
			<div class='options-button'>
			<input type='submit' value='<?php _e('Save Changes','wp-custom-fields-search')?>'/>
			</div>
			<div class='options-button'>
			<input type='submit' name='delete' value='<?php _e('Delete','wp-custom-fields-search')?>' onClick='return confirm("<?php _e('Are you sure you want to delete this preset?','wp-custom-fields-search')?>")'/>
			</div>
		</div>
</div></form>
