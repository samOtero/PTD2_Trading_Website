<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function trade_To_Pickup($db_New, $tradePokeID, $newOwner) {//PTD1
	$query2 = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalTrainer, myTag FROM trainer_trades WHERE uniqueID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $tradePokeID);
	$result2->execute();
	$result2->store_result();
	$result2->bind_result($pokeNum2, $pokeLevel2, $pokeExp2, $pokeShiny2, $pokeNickname2, $m12, $m22, $m32, $m42, $ability2, $mSel2, $item2, $originalOwner2, $myTag2);
	if ($result2->affected_rows == 0) {
		$result2->close();
		echo "You cannot complete this trade, trade pokemon doesn't exist.";
		exit;
	}
	$result2->fetch();
	$result2->close();
	$query2 = "INSERT INTO trainer_pickup (pickup, num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalTrainer, currentTrainer, myTag, uniqueID) VALUES (1, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("iiiisiiiiiiiiiss", $pokeNum2, $pokeLevel2, $pokeExp2, $pokeShiny2, $pokeNickname2, $m12, $m22, $m32, $m42, $ability2, $mSel2, $item2, $originalOwner2, $newOwner, $myTag2, $tradePokeID);
	$result2->execute();
	$result2->close();
	$query2 = "DELETE FROM trainer_trades WHERE uniqueID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $tradePokeID);
	$result2->execute();
	$result2->close();
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function trade_To_Pickup2($db_New, $tradePokeID, $newOwner, $shelmetTrade=false, $transactionFlag=true) {
	$query2 = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, myTag, gender, happy FROM trainer_trades WHERE uniqueID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $tradePokeID);
	$result2->execute();
	$result2->store_result();
	$result2->bind_result($pokeNum2, $pokeLevel2, $pokeExp2, $pokeShiny2, $pokeNickname2, $m12, $m22, $m32, $m42, $item2, $originalOwner2, $myTag2, $pokeGender2, $pokeHoF2);
	if ($result2->affected_rows == 0) {
		$result2->close();
		$transactionFlag = false;
		return $transactionFlag;
	}
	$result2->fetch();
	$result2->free_result();
	$result2->close();
	if ($shelmetTrade == true) {
		if ($pokeNum2 == 616) {
			$pokeNum2 = 617;
			$pokeNickname2 = "Accelgor";
		}else if ($pokeNum2 == 588) {
			$pokeNum2 = 589;
			$pokeNickname2 = "Escavalier";
		}
		$pokeHoF2 = 0;
	}
	$query2 = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("iiiisiiiiiiisii", $pokeNum2, $pokeLevel2, $pokeExp2, $pokeShiny2, $pokeNickname2, $m12, $m22, $m32, $m42, $item2, $originalOwner2, $newOwner, $myTag2, $pokeGender2, $pokeHoF2);
	if (!$result2->execute()) {
		$transactionFlag = false;
		return $transactionFlag;
	}
	$result2->close();
	$query2 = "DELETE FROM trainer_trades WHERE uniqueID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $tradePokeID);
	if (!$result2->execute()) {
		$transactionFlag = false;
		return $transactionFlag;
	}
	$result2->close();
	return $transactionFlag;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>