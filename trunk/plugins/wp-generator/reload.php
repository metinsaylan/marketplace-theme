<?php 
if( $_GET['connection'] == '1' ){ include_once('../../../../../wp-config.php'); }

	$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER BY ListingID, id ASC ";
	$db_process = mysql_query($sql);
	$rows = mysql_num_rows($db_process);
	
	if( $rows > 0 ){
		while( $rs = mysql_fetch_array($db_process) ){
?>

<div id="recordsArray_<?php echo $rs['position']; ?>">
<div class='eHelp' align='left'>
	  <div style='float:right'>
		<a onClick="saveField('<?php echo $rs['position']; ?>');" style='text-decoration:none; cursor:pointer'>Save</a>&nbsp;|&nbsp;
		<a onClick="remove('<?php echo $rs['position']; ?>');" style='text-decoration:none; cursor:pointer'>Remove</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a onclick="cfgShowHide('<?php echo $rs['position'];?>')" style="cursor:pointer;cursor:hand;"><img src="<?php echo MSG_FULLPATH;?>/image/arr1.gif" id="cfgimg_<?php echo $rs['position']; ?>" align="absmiddle" border="0" alt="" /></a>
	  </div>
	
	<div style="color: #0066FF;font-weight:bold; font-size:12px">Field&nbsp;<span style='font-size:10px; font-weight:normal; color:#666666; padding-top:4px;' ><?php if( '2' == $rs['flag'] ){ ?>:&nbsp;<?php  echo $rs['name'];  ?><?php } ?></span>&nbsp;&nbsp;<span style='font-size:10px; font-weight:normal; color:#CC0000; padding-top:4px;' id='<?php echo $rs['position']; ?>insert_response'> </span></div>
	
	<div id="collsp_<?php echo $rs['position']; ?>" style="margin-top:6px; border-top:1px solid #dddddd;display:none;">
	<table>
	  <tr><td><b>Name:</b></td><td><input type='text' id='<?php echo $rs['position']; ?>_name' class='widefat' style="width:400px;" value="<?php echo $rs['name']; ?>" ></td></tr>
	  <tr><td><b>Default value:</b></td><td><input type='text' name='<?php echo $rs['position']; ?>_default' class='widefat' style='width:400px;' id='<?php echo $rs['position']; ?>_default' value="<?php echo $rs['default']; ?>" ></td></tr>
	  <tr><td><b>Type:</b></td><td>
		<select name="<?php echo $rs['position']; ?>_type" id="<?php echo $rs['position']; ?>_type">
			<option name="" value="text" <?php if($rs['type']=='text'){ echo "selected"; } ?> >Text</option>
			<option name="" value="dropdown" <?php if($rs['type']=='dropdown'){ echo "selected"; } ?> >Dropdown</option>
			<option name="" value="checkbox" <?php if($rs['type']=='checkbox'){ echo "selected"; } ?> >Checkbox</option>
			<option name="" value="radio" <?php if($rs['type']=='radio'){ echo "selected"; } ?> >Radio</option>
			<option name="" value="price" <?php if($rs['type']=='price'){ echo "selected"; } ?> >Price</option>
			<option name="" value="image" <?php if($rs['type']=='image'){ echo "selected"; } ?> >Image</option>
		</select>
	  </td></tr>
	  <tr><td><b>Values:</b></td><td><input type='text' name='<?php echo $rs['position']; ?>_values' class='widefat' style='width:400px;' id='<?php echo $rs['position']; ?>_values' value="<?php echo htmlentities($rs['values'], ENT_QUOTES); ?>" ></td></tr>
		<tr><td><b>Required:</b></td><td>
			<input type='checkbox' name='<?php echo $rs['position']; ?>_required' id='<?php echo $rs['position']; ?>_required' value='1' <?php if($rs['req']=='1'){echo " checked='checked'";}; ?> />
		</td></tr>
	</table>
    </div>
</div> 
</div>

<?php
		 } // Eof while
	} // If $rows > 0
?>


