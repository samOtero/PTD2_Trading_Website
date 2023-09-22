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
		echo 'Error in databse. Try again.';
		exit;
	}
	function do_Stuff() {
		global $id, $pokeID, $whichDB, $db, $whichProfile;
		$db_New = connect_To_Database_New();
		$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, ability, item, originalTrainer, currentTrainer, myTag FROM trainer_pickup WHERE uniqueID = ? AND pickup = 1 AND currentTrainer = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("si", $pokeID, $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $mSel, $ability, $item, $originalOwner, $otherTrainer, $myTag);
		if ($result->affected_rows == 0) {
			$result->close();
			echo "Error, can't pick this up.";
            return;
		}
		$result->fetch();
		$query = "DELETE FROM trainer_pickup WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $pokeID);
		$result->execute();
		$result->close();
		if ($pokeNum == 64) {
			if ($pokeNickname == "Kadabra") {
				$pokeNickname = "Alakazam";
			}
			$pokeNum = 65;
		}else if ($pokeNum == 67) {
			if ($pokeNickname == "Machoke") {
				$pokeNickname = "Machamp";
			}
			$pokeNum = 68;
		}else if ($pokeNum == 75) {
			if ($pokeNickname == "Graveler") {
				$pokeNickname = "Golem";
			}
			$pokeNum = 76;
		}else if ($pokeNum == 93) {
			if ($pokeNickname == "Haunter") {
				$pokeNickname = "Gengar";
			}
			$pokeNum = 94;
		}
		$dbActual = get_Pokemon_Database($whichDB, $db);
		$query = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalOwner, trainerID, whichProfile, myTag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $dbActual->prepare($query);
		$result->bind_param("iiiisiiiiiiiiiis", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $ability, $mSel, $item, $originalOwner, $id, $whichProfile, $myTag);
		$result->execute();
		echo 'Pickup completed! ';
		?>
        <a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
        <?php
	}
?>
