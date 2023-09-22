<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	if (!isset($whichProfile)) {
		echo 'Error, no profile present.';
		exit;
	}
	include 'database_connections.php';
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$db = connect_To_Database();
$query = "select whichDB from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($whichDB);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		echo 'Error, you have saved outside the Trading Center.';
		exit;
	}
	$pokeID = $_REQUEST['pokeID'];
	if (!isset($pokeID)) {
		echo 'Error, no pokeID present.';
		exit;
	}
	$newCurrentSave = uniqid(true);
	$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
	$result->execute();
	if ($result->sqlstate=="00000") {
		$currentSave = $newCurrentSave;
		$result->close();
		$_SESSION['currentSave'] = $currentSave;
		do_Stuff();
	}else{
		$result->close();
		echo 'Error in database. Try again.';
		exit;
	}
	function do_Stuff() {
		global $id, $pokeID, $whichDB, $db, $whichProfile;
		$db_New = get_Pokemon_Database($whichDB, $db);
	
		$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ? AND trainerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $pokeID, $id);
		if (!$result->execute()) {
			echo 'Error in database. Try again.';
		}else{
			echo 'Goodbye! ';
		?>
        <a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
        <?php
		}
		$result->close();
		$db_New->close();
		
	}
?>
