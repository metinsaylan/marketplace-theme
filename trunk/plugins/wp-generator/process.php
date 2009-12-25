<?php 
include_once('../../../../../wp-config.php');

// Field Values
//'$name', '$type', '$values', '$default', '$req', '$position', '$flag', '$max_value'

$position = $_POST['position'];
$name = urldecode($_POST['name']);
$type = $_POST['type'];
$values = urldecode($_POST['values']);
$default = urldecode($_POST['default']);
$req = $_POST['required'];
$flag = 2; //$_POST['flag'];

$msg = $_POST['msg'];

$sql = "select position,flag from ".MSG_CONTROLS." where position = '$position' ";
$process = mysql_query($sql);
$db_position = mysql_fetch_array($process);

// Test
     $max_listingId_sql = "select MAX(ListingID) as max from ".MSG_CONTROLS."";
	 $process_max = mysql_query($max_listingId_sql);	
     $max = mysql_fetch_array($process_max);
	 $max_value = $max[0] + 1; 
// Eof test	

	if( $db_position['position'] == $position ){
		if( $db_position['position'] == $position && MSG_MESSAGE_REMOVE == $msg ) {
		 // msg = -1
		 // Delete it
			$sql_delete = " delete from ".MSG_CONTROLS." 
					 where position = '$position' ";
			mysql_query($sql_delete);
		} else {
		 // Update
			$sql = " UPDATE ".MSG_CONTROLS." SET 
					 `name` = '$name',
					 `type` = '$type',
					 `values` = '$values',
					 `default` = '$default',
					 `req` = '$req',
					 `flag` = '$flag'
					 WHERE `position` = '$position' ";
			mysql_query($sql);
		    $result = $name;
			$result .= ',';
			$result .= $position;
			echo  $result;
		}
	} else {
	  // INSERT
	  $sql = "INSERT INTO ".MSG_CONTROLS." (`id`, `name`, `type`, `values`, `default`, `req`, `position`, `flag`, `ListingID`) VALUES (NULL, '$name', '$type', '$values', '$default', '$req', '$position', '$flag', '$max_value');";
	  
	  //$sql = "INSERT INTO ".MSG_CONTROLS." (`id`, `name`, `before`, `after`, `default`, `position`, `flag`, `ListingID`) VALUES (NULL, , '$before', '$after', '$default', '$position', '$flag', '$max_value');";
	  
		mysql_query($sql);
	}
	
?>

<?php 
$action 				= $_POST['action'];
$updateRecordsArray 	= $_POST['recordsArray']; 

if ($action == "updateRecordsListings"){
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		$query = "UPDATE ".MSG_CONTROLS." SET ListingID = " . $listingCounter . " WHERE position = " . $recordIDValue;
		mysql_query($query) or die('Error, insert query failed');
		$listingCounter = $listingCounter + 1;	
	}
} 
?>