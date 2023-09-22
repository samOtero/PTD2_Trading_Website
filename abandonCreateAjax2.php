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
	$reason = get_Current_Save_Status($id, $currentSave);		
	if ($reason != "good") {
		echo 'Error, you have saved outside the Trading Center.';
		exit;
	}
	$db = connect_To_Database();
	$pokeID = $_REQUEST['pokeID'];
	if (!isset($pokeID)) {
		echo 'Error, no pokeID present.';
		exit;
	}
	$updateResult = update_Current_Save($db, $id, $currentSave);
	$reason = $updateResult[0];
	$currentSave = $updateResult[1];
	$db->close();
	if ($reason == "error") {
		echo 'Error in database. Try again.';
		exit;
	}
	do_Stuff();
	function do_Stuff() {
		global $id, $pokeID, $whichProfile;
		
		$db_New = connect_To_ptd2_Story_Database();
		$query = "SELECT whichDB FROM poke_accounts WHERE trainerID = ? AND whichProfile = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $id, $whichProfile);
		$result->execute();
		$result->store_result();
		$result->bind_result($whichDB);
		if ($result->affected_rows == 0) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo "Error, can't abandon.";
            return;
		}
		$result->fetch();
		$result->free_result();
		$result->close();
		$db_New->close();
		
		$db_New = get_PTD2_Pokemon_Database($whichDB);
	
		$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ? AND trainerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $pokeID, $id);
		if ($result->execute()) {
			echo 'Goodbye! ';
			?>
			<a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
			<?php
		}else{
			echo 'Error in database. Try again.';
		}
		$result->close();
		$db_New->close();
	}
?>
