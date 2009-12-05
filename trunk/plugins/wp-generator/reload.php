<?php 
if( $_GET['connection'] == '1' ){ include_once('../../../../../wp-config.php'); }

	$sql = "SELECT * FROM ".MSG_CONTROLS." ORDER BY ListingID, id ASC ";
	$db_process = mysql_query($sql);
	$rows = mysql_num_rows($db_process);
	
		echo "<div class='debug'>";
		echo "<br/>SQL : " . $sql;
		echo "<br/>ROWS : " . $rows;
		echo "</div>";
	
	if( $rows > 0 ){
		while( $rs = mysql_fetch_array($db_process) ){
		
		 echo " \n <!--  | " . $rs['name'] . " | " . $rs['position'] . " | " . htmlentities($rs['before'], ENT_NOQUOTES). " | " . htmlentities($rs['after'], ENT_NOQUOTES). " | " . $rs['default']. " |  -->" ;
?>

<div id="recordsArray_<?php echo $rs['position']; ?>">
<div class='eHelp' align='left'>
	  <div style='float:right'>
		<a onClick="saveField('<?php echo $rs['position']; ?>_name','<?php echo $rs['position']; ?>_default','<?php echo $rs['position']; ?>_before','<?php echo $rs['position']; ?>_after','<?php echo $rs['position']; ?>');" style='text-decoration:none; cursor:pointer'>Save</a>&nbsp;|&nbsp;
		<a onClick="remove('<?php echo $rs['position']; ?>');" style='text-decoration:none; cursor:pointer'>Remove</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a onclick="cfgShowHide('<?php echo $rs['position'];?>', '<?php echo MSG_FULLPATH;?>')" style="cursor:pointer;cursor:hand;"><img src="<?php echo MSG_FULLPATH;?>/image/arr1.gif" id="cfgimg_<?php echo $rs['position']; ?>" align="absmiddle" border="0" alt="" /></a>
	  </div>
	
	<div style="color: #0066FF;font-weight:bold; font-size:12px">Field&nbsp;<span style='font-size:10px; font-weight:normal; color:#666666; padding-top:4px;' ><?php if( '2' == $rs['flag'] ){ ?>:&nbsp;<?php  echo $rs['name'];  ?><?php } ?></span>&nbsp;&nbsp;<span style='font-size:10px; font-weight:normal; color:#CC0000; padding-top:4px;' id='<?php echo $rs['position']; ?>insert_response'> </span></div>
	
	<div id="collsp_<?php echo $rs['position']; ?>" style="margin-top:6px; border-top:1px solid #dddddd;display:none;">
	<table>
	  <tr><td><b>Name:</b></td><td><input type='text' id='<?php echo $rs['position']; ?>_name' class='widefat' style="width:400px;" value="<?php echo $rs['name']; ?>" ></td></tr>
	  <tr><td><b>Default value:</b></td><td><input type='text' name='size' class='widefat' style='width:400px;' id='<?php echo $rs['position']; ?>_default' value="<?php echo $rs['default']; ?>" ></td></tr>
	  <tr><td><b>Before:</b></td><td><input type='text' name='size' class='widefat' style='width:400px;' id='<?php echo $rs['position']; ?>_before' value="<?php echo htmlentities($rs['before'], ENT_QUOTES); ?>" ></td></tr>
	  <tr><td><b>After:</b></td><td><input type='text' name='size' class='widefat' style='width:400px;' id='<?php echo $rs['position']; ?>_after' value="<?php echo htmlentities($rs['after'], ENT_QUOTES); ?>" ></td></tr>
	</table>
    </div>
</div>
</div> 

<?php
		 } // Eof while
	} // If $rows > 0
?>


