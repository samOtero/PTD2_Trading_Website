<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	if (!isset($whichProfile)) {
		echo 'Error, no profile present.';
		exit;
	}
	include 'database_connections.php';
	include 'ptd2_basic.php';
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	
	//$reason = get_Current_Save_Status($id, $currentSave);		
	//if ($reason != "good") {
		//echo 'Error, you have saved outside the Trading Center.';
		//exit;
	//}
	$pokeID = $_REQUEST['pokeID'];
	if (!isset($pokeID)) {
		echo 'Error, no pokeID present.';
		exit;
	}
	//$db = connect_To_Database();
	//$updateResult = update_Current_Save($db, $id, $currentSave);
	//$reason = $updateResult[0];
	//$currentSave = $updateResult[1];
	//$db->close();
	//if ($reason == "error") {
		//echo 'Error in database. Try again.';
		//exit;
	//}
	do_Stuff();
	function do_Stuff() {
		global $pokeID;
		$db_New = connect_To_ptd2_Trading();
		$query = "DELETE FROM trainer_pickup WHERE uniquePickupID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("i", $pokeID);
		if ($result->execute()) {
			echo 'Goodbye! ';
		?>
        <a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
        <?php
		}else{
			echo 'Error in database. Try again.';
		}
		$result->close();
	}
?>
