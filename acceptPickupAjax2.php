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
			echo "Error, can't pick this up.";
            return;
		}
		$result->fetch();
		$result->free_result();
		$result->close();
		$db_New->close();
		
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender, happy FROM trainer_pickup WHERE uniquePickupID = ? AND currentTrainer = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $pokeID, $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $otherTrainer, $myTag, $pokeGender, $pokeHoF);
		if ($result->affected_rows == 0) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo "Error, can't pick this up.";
            return;
		}
		$result->fetch();
		$result->free_result();
		$result->close();
		$db_New->autocommit(false);
		$transactionFlag = true;
		$query = "DELETE FROM trainer_pickup WHERE uniquePickupID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("i", $pokeID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		//$db_New->close();
		include 'tradeEvolution2.php'; //handles the evolutions
		$pokePos = 999;
		$dbActual = get_PTD2_Pokemon_Database($whichDB);
		$dbActual->autocommit(false);
		$query = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalOwner, trainerID, whichProfile, myTag, gender, pos, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $dbActual->prepare($query);
		$result->bind_param("iiiisiiiiiiiisiii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $whichProfile, $myTag, $pokeGender, $pokePos, $pokeHoF);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		if ($transactionFlag == true) {
			$db_New->commit();
			$dbActual->commit();
			echo 'Pickup completed! ';
		?>
        <a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
        <?php
		}else{
			$db_New->rollback();
			$dbActual->rollback();
			echo 'Error. Please Try Again.';
		}
		$db_New->autocommit(true);
		$dbActual->autocommit(true);
		$db_New->close();
		$dbActual->close();
	}
?>
