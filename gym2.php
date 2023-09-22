<?php
session_start();
include 'database_connections.php';
$Code = $_REQUEST['Code'];
$Action = $_REQUEST['Action'];
$doEncrypt = $_REQUEST['samOnly'];
$dateCheck = $_REQUEST['Date'];
check_Request($Action);
$db = connect_To_Database();
$email = $_REQUEST['Email'];
$pass = $_REQUEST['Pass'];
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
if ($Action == "saveGym") {
	save_Gym($db, $email, $pass);
}else if ($Action == "loadGym") {
	load_Gym($db, $email, $pass);
}else if ($Action == "sendGiveaway") {
	$db->close();
	send_Giveaway();
}else if ($Action == "saveTrainerVS") {
	save_TrainerVS($db, $email, $pass);
}else if ($Action == "loadTrainerVS") {
	load_TrainerVS($db, $email, $pass);
}else if ($Action == "randomWinner") {
	$db->close();
	get_Random_Winner_From_Trainer_Pass();
}else if ($Action == "getCount") {
	$db->close();
	get_Total_Trainer_Pass();
}
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
 function check_Request($value1) {
 	if (empty($value1)) {
	 	echo 'Result=Failure&Reason=MissingCode';
	 	exit;
 	}
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
 function get_TrainerID($db, $email, $pass) {
	$query = "select trainerID from poke_accounts WHERE email = ? AND pass = ?";
	$result = $db->prepare($query);
	$result->bind_param("ss", $email, $pass);
	$result->execute();
	$result->store_result();
	$result->bind_result($myTrainerID);
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
		return $myTrainerID;
	}
	$result->close();
	return -1;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
function save_TrainerVS($db, $email, $pass) {
	$myTrainerID = get_TrainerID($db, $email, $pass);
	$db->close();
	if ($myTrainerID <= 0) {
		echo 'Result=Failure&Reason=NotFound';
		return;
	}
	$extra = $_REQUEST['extra'];
	$checkSum = $_REQUEST['extra2'];
	$needChange = $_REQUEST['extra3'];
	$doWinsLose = $_REQUEST['extra4'];
	$extraWins = $_REQUEST['extra5'];
	$doAvatar = $_REQUEST['extra6'];
	$myNickname = $_REQUEST['nickname'];
	if (empty($extra)) {
		echo 'Result=Failure&Reason=hacking&Other=MissingExtra';
		return;
	}else if (empty($checkSum)) {
		echo 'Result=Failure&Reason=hacking&Other=MissingExtra2';
		return;
	}
	$myCheckSum = check_Sum($extra);
	$flashCheckSum = whole_String_To_Num($checkSum);
	if ($myCheckSum != $flashCheckSum) {
		echo 'Result=Failure&Reason=hacking&Other=CS';
		return;
	}
	
	$currentIndex = 0;
	$substring = substr($extra, $currentIndex++, 1);
	$howManySecond = string_To_Num($substring);
	$totalLengthOfExtra = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
	if ($totalLengthOfExtra != strlen($extra)) {
		echo 'Result=Failure&Reason=hacking&Other=length';
		return;
	}
	$db_New = connect_To_ptd2_Trading();
	$keepGoing = true;
	
	$db_New->autocommit(false);
	$transactionFlag = true;
	if ($needChange == "y") {//IF NEED CHANGE
		//DELETE PREVIOUS MONS
		$query = "DELETE FROM trainerVSPoke3 WHERE trainerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("i", $myTrainerID);
		if (!$result->execute()) {
			$transactionFlag = false;
			$keepGoing = false;
		}
		$result->close();
	
		if ($keepGoing == true) {//INSERT POKE INTO DB
			$query = "INSERT INTO trainerVSPoke3 (trainerID, num, lvl, gender, shiny, m1, m2, m3, m4, abilitySelected, commandStyle, item, pos) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$result = $db_New->prepare($query);
			for ($i=1; $i<=3; $i++) {
				
				//get Info from encrypted input string
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$num = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$lvl = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$m1 = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$m2 = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$m3 = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$m4 = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$gender = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$item = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$shiny = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$abilitySelected = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
				$commandStyle = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
				$pos = $i;
				//
				
				$result->bind_param("iiiiiiiiiiiii", $myTrainerID, $num, $lvl, $gender, $shiny, $m1, $m2, $m3, $m4, $abilitySelected, $commandStyle, $item, $pos);
				$result->execute();
				if (!$result->affected_rows) {
					$transactionFlag = false;
					$keepGoing = false;
				}
				if ($keepGoing == false) {
					break;
				}
			}
			$result->close();
		}
	}
	if ($keepGoing == true) {//ADD Trainer to DB if necessary
		$query = "select autoID from trainerVSTrainers WHERE trainerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("i", $myTrainerID);
		$result->execute();
		$result->store_result();
		$result->bind_result($myAutoID);
		$totalProfiles = $result->affected_rows;
		if ($totalProfiles == 0) {
			$trainerName = "Satoshi";
			$trainerAvatar = 0;
			$result->close();
			$query = "INSERT INTO trainerVSTrainers (trainerID, trainerName, trainerAvatar) VALUES (?, ?,?)";
			$result = $db_New->prepare($query);
			$result->bind_param("isi", $myTrainerID, $trainerName, $trainerAvatar);
			$result->execute();
			if (!$result->affected_rows) {
				$keepGoing = false;
				$transactionFlag = false;
			}
			$result->close();
		}else{
			$result->close();
			if ($doWinsLose == "y") {
				//$myNickname = "Sam";
				$trainerAvatar = 0;
				$query = "UPDATE trainerVSTrainers SET wins3v3 = ?, loses3v3 = ?, trainerName = ?, trainerAvatar = ? WHERE trainerID = ?";
				$result = $db_New->prepare($query);
				$currentIndex = 0;
				$substring = substr($extraWins, $currentIndex++, 1);
				$howManySecond = string_To_Num($substring);
				$totalLengthOfExtra = whole_String_To_Num(substr($extraWins, $currentIndex, $howManySecond));
				if ($totalLengthOfExtra != strlen($extraWins)) {
					echo 'Result=Failure&Reason=hacking&Other=lengthWins';
					return;
				}
				$currentIndex += $howManySecond;
				$howMany2 = string_To_Num(substr($extraWins, $currentIndex++, 1));
				$howManySecond = string_To_Num(substr($extraWins, $currentIndex, $howMany2));
				$currentIndex += $howMany2;
				$newWins = whole_String_To_Num(substr($extraWins, $currentIndex, $howManySecond));
				$currentIndex += $howManySecond;
				$howMany2 = string_To_Num(substr($extraWins, $currentIndex++, 1));
				$howManySecond = string_To_Num(substr($extraWins, $currentIndex, $howMany2));
				$currentIndex += $howMany2;
				$newLoses = whole_String_To_Num(substr($extraWins, $currentIndex, $howManySecond));
				if ($doAvatar == "y") {
					$currentIndex += $howManySecond;
					$howManySecond = string_To_Num(substr($extraWins, $currentIndex++, 1));
					$trainerAvatar = whole_String_To_Num(substr($extraWins, $currentIndex, $howManySecond));
					//echo "hms=$howManySecond&ta=$trainerAvatar&";
				}
				$result->bind_param("iisii", $newWins, $newLoses, $myNickname, $trainerAvatar, $myTrainerID);
				$result->execute();
				if ($result->sqlstate!="00000") {
					$keepGoing = false;
					$transactionFlag = false;
				}
				echo 'Testing=updateDo&';
				$result->close();
			}
		}
	}
		
	if ($transactionFlag == true) {
		$db_New->commit();
	}else{
		$db_New->rollback();
		echo 'Result=Failure&Reason=error';
	}
	$db_New->autocommit(true);
	
	//load random player stats
	if ($keepGoing == true) {
		//find random id
		$query = "SELECT trainerID, wins3v3, trainerName, trainerAvatar FROM trainerVSTrainers WHERE wins3v3 > 0 AND trainerID != $myTrainerID ORDER BY RAND() LIMIT 0,1;";
		$result = $db_New->prepare($query);
		$result->execute();
		$result->store_result();
		$result->bind_result($currentID, $currentWins, $currentName, $currentAvatar);	
		$hmp = $result->affected_rows;
		if ($hmp <= 0) {
			$keepGoing = false;
			$result->close();
			echo 'Result=Failure&Reason=error';
		}else{
			$result->fetch();
			$result->close();
		}
		if ($keepGoing == true) {
			//Get poke for that ID
			$query = "SELECT num, lvl, gender, shiny, m1, m2, m3, m4, abilitySelected, commandStyle, item FROM trainerVSPoke3 WHERE trainerID = ? ORDER BY pos";
			$result = $db_New->prepare($query);
			$result->bind_param("i", $currentID);
			$result->execute();
			$result->store_result();
			$result->bind_result($num, $lvl, $gender, $shiny, $m1, $m2, $m3, $m4, $abilitySelected, $commandStyle, $item);	
			$hmp = $result->affected_rows;
			if ($hmp <= 0) {
				$keepGoing = false;
				echo 'Result=Failure&Reason=error';
				$result->close();
			}
		}
		if ($keepGoing == true) {
			$pokeInfo = "";
			for ($i=0; $i<$hmp; $i++) {
				$result->fetch();
				$pokeInfo = $pokeInfo."".strlen($num);
				$pokeInfo = $pokeInfo.$num;
				$pokeInfo = $pokeInfo.strlen($lvl);
				$pokeInfo = $pokeInfo.$lvl;
				$pokeInfo = $pokeInfo.strlen($gender);
				$pokeInfo = $pokeInfo.$gender;
				$pokeInfo = $pokeInfo.strlen($shiny);
				$pokeInfo = $pokeInfo.$shiny;
				$pokeInfo = $pokeInfo.strlen($m1);
				$pokeInfo = $pokeInfo.$m1;
				$pokeInfo = $pokeInfo.strlen($m2);
				$pokeInfo = $pokeInfo.$m2;
				$pokeInfo = $pokeInfo.strlen($m3);
				$pokeInfo = $pokeInfo.$m3;
				$pokeInfo = $pokeInfo.strlen($m4);
				$pokeInfo = $pokeInfo.$m4;
				$pokeInfo = $pokeInfo.strlen($abilitySelected);
				$pokeInfo = $pokeInfo.$abilitySelected;
				$pokeInfo = $pokeInfo.strlen($commandStyle);
				$pokeInfo = $pokeInfo.$commandStyle;
				$pokeInfo = $pokeInfo.strlen($item);
				$pokeInfo = $pokeInfo.$item;
			}
			$pokeInfo = $pokeInfo.strlen($currentWins);
			$pokeInfo= $pokeInfo.$currentWins;
			$pokeInfo = $pokeInfo.strlen($currentAvatar);
			$pokeInfo= $pokeInfo.$currentAvatar;
			$pokeInfo = $pokeInfo.strlen($currentID);
			$pokeInfo= $pokeInfo.$currentID;
			$result->close();
			$constantLength = strlen($pokeInfo);
			$length = strlen($constantLength);
			$prefix = get_Length($length, $constantLength);
			$resultString = "".$prefix.$pokeInfo;
			$resultString = whole_Num_To_String($resultString);
			echo 'Result=Success&extra='.$resultString.'&nick='.$currentName;
		}
	}
	$db_New->close();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
function load_TrainerVS($db, $email, $pass) {
	$myTrainerID = get_TrainerID($db, $email, $pass);
	$db->close();
	if ($myTrainerID <= 0) {
		echo 'Result=Failure&Reason=NotFound';
		return;
	}
	$db_New = connect_To_ptd2_Trading();
	$keepGoing = true;
	$transactionFlag = true;
	if ($keepGoing == true) {
		$query = "select autoID, trainerName, trainerAvatar from trainerVSTrainers WHERE trainerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("i", $myTrainerID);
		$result->execute();
		$result->store_result();
		$result->bind_result($myAutoID, $trainerName, $trainerAvatar);
		$totalProfiles = $result->affected_rows;
		if ($totalProfiles == 0) {
			$trainerName = "Satoshi";
			$result->close();
		}else{
			$result->fetch();
			$result->close();
		}
	}
	echo 'Result=Success&nick='.$trainerName.'&wa='.$trainerAvatar;
	$db_New->close();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
function load_Gym($db, $email, $pass) {
	$myTrainerID = get_TrainerID($db, $email, $pass);
	$db->close();
	if ($myTrainerID == -1) {
		echo 'Result=Failure&Reason=NotFound';
		return;
	}
	$dbPTD2 = connect_To_ptd2_1on1_Database();
	$query = "select levelBeaten from gymInfo WHERE userID = ?";
	$result = $dbPTD2->prepare($query);
	$result->bind_param("i", $myTrainerID);
	$result->execute();
	$result->store_result();
	$result->bind_result($lastLevelBeaten);
	$totalProfiles = $result->affected_rows;
	$profileInfo = "".$totalProfiles;
	for ($i=1; $i<=$totalProfiles; $i++) {
		$result->fetch();
		$levelLength = strlen($lastLevelBeaten);
		$profileInfo = $profileInfo.$levelLength.$lastLevelBeaten;
	}
	$result->close();
	$constantLength = strlen($profileInfo);
	$length = strlen($constantLength);
	$prefix = get_Length($length, $constantLength);
	$resultString = "".$prefix.$profileInfo;
	$resultString = whole_Num_To_String($resultString);
	echo 'Result=Success&extra='.$resultString;
	$dbPTD2->close();
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
function save_Gym($db, $email, $pass) {
	$myTrainerID = get_TrainerID($db, $email, $pass);
	$db->close();
	if ($myTrainerID == -1) {
		echo 'Result=Failure&Reason=NotFound';
		return;
	}
	$extra = $_REQUEST['extra'];
	if (empty($extra)) {
		echo 'Result=Failure&Reason=hacking';
		return;
	}
	$currentIndex = 0;
	$substring = substr($extra, $currentIndex++, 1);
	$howManySecond = string_To_Num($substring);
	$totalLengthOfExtra = whole_String_To_Num(substr($extra, $currentIndex, $howManySecond));
	if ($totalLengthOfExtra != strlen($extra)) {
		echo 'Result=Failure&Reason=hacking';
		return;
	}
	$currentIndex += $howManySecond;
	$howManySecond = string_To_Num(substr($extra, $currentIndex++, 1));
	$lastLevelBeaten = substr($extra, $currentIndex, $howManySecond);
	$dbPTD2 = connect_To_ptd2_1on1_Database();
	$query = "select levelBeaten from gymInfo WHERE userID = ?";
	$result = $dbPTD2->prepare($query);
	$result->bind_param("i", $myTrainerID);
	$result->execute();
	$result->store_result();
	$result->bind_result($previousLevelBeaten);
	$totalProfiles = $result->affected_rows;
	$previousLevel = 0;
	$newLevel = whole_String_To_Num($lastLevelBeaten);
	if ($totalProfiles) {
		$result->fetch();
		$result->close();
		$previousLevel = whole_String_To_Num($previousLevelBeaten);
		$query = "UPDATE gymInfo SET levelBeaten = ? WHERE userID = ?";
		$result = $dbPTD2->prepare($query);
		$result->bind_param("si",$lastLevelBeaten, $myTrainerID);
		$result->execute();
		$result->close();
		add_Gym_Reward($previousLevel, $newLevel, $myTrainerID);
		echo 'Result=Success&Reason=saved';
	}else{
		$result->close();
		$query = "INSERT INTO gymInfo (userID, levelBeaten) VALUES (?, ?)";
		$result = $dbPTD2->prepare($query);
		$result->bind_param("is", $myTrainerID, $lastLevelBeaten);
		$result->execute();
		if (!$result->affected_rows) {
			echo 'Result=Failure&Reason=DatabaseConnection';
		}else{
			echo 'Result=Success&Reason=savedCreateNew';
			add_Gym_Reward($previousLevel, $newLevel, $myTrainerID);
		}
		$result->close();
	}
	$dbPTD2->close();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
function get_Total_Trainer_Pass() {
	$dbNew = connect_To_ptd2_Story_Database();
	$query = "SELECT COUNT(*) FROM  trainerPass";
	$result = $dbNew->prepare($query);
	$result->execute();
	$result->store_result();
	$result->bind_result($totalCount);	
	$hmp = $result->affected_rows;
	$result->fetch();
	echo "There are a total of ($totalCount) Trainer Pass.";
	$dbNew->close();
}
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
function get_Random_Winner_From_Trainer_Pass() {
	$dbNew = connect_To_ptd2_Story_Database();
	$query = "SELECT trainerID FROM  trainerPass ORDER BY RAND() LIMIT 0,1;";
	$result = $dbNew->prepare($query);
	$result->execute();
	$result->store_result();
	$result->bind_result($currentID);	
	$hmp = $result->affected_rows;
	$result->fetch();
	echo "The winner has id: $currentID";
	$dbNew->close();
}
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
 function send_Giveaway() {
	$dbNew = connect_To_ptd2_Story_Database();
	$query = "SELECT trainerID FROM  trainerPass";
	$result = $dbNew->prepare($query);
	$result->execute();
	$result->store_result();
	$result->bind_result($currentID);	
	$hmp = $result->affected_rows;
	$dbNew->close();
	$db_New2 = connect_To_ptd2_Trading();
	$query2 = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
	$result2 = $db_New2->prepare($query2);
	$pokeNum = 686;
	$pokeLevel = 1;
	$pokeShiny = 1;
	$pokeNickname = "Inkay";
	$pokeGender = 1;
	$pokeItem = 100;
	$m1 = 1;
	$m2 = 42;
	$m3 = 201;
	$m4 = 0;
	$originalTrainer = 0;
	$pokeTag = 'n';
	for ($i=0; $i<$hmp; $i++) { 
		$result->fetch();
		$pokeGender = 1;
		$result2->bind_param("iiisiiiiiiiis", $pokeNum, $pokeLevel, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $pokeItem, $currentID, $originalTrainer, $pokeGender, $pokeTag);
		$result2->execute();
		$pokeGender = 2;
		$result2->bind_param("iiisiiiiiiiis", $pokeNum, $pokeLevel, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $pokeItem, $currentID, $originalTrainer, $pokeGender, $pokeTag);
		$result2->execute();
	}
	$result2->close();
	$db_New2->close();
	$result->close();
	 echo 'Sent the Giveaway!';
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
function add_Gym_Reward($previousLevel, $newLevel, $myTrainerID) {
	//echo '&prev='.$previousLevel.'&new='.$newLevel;
	if ($previousLevel >= $newLevel) {
		//echo '&hadit=true';
		return;
	}
	if ($previousLevel < 1 && $newLevel >= 1) {
		add_To_Pickup_3($myTrainerID, 142, 1, 0, "Aerodactyl", 1, 71, 19, 30, 38, 75, 0, 'n');
	}
	if ($previousLevel < 2 && $newLevel >= 2) {
		add_To_Pickup_3($myTrainerID, 7, 1, 0, "Squirtle", 1, 72, 1, 0, 0, 0, 0, 'n');
	}
	if ($previousLevel < 3 && $newLevel >= 3) {
		add_To_Pickup_3($myTrainerID, 179, 1, 0, "Mareep", 1, 73, 1, 0, 0, 0, 0, 'n');
	}
	if ($previousLevel < 4 && $newLevel >= 4) {
		add_To_Pickup_3($myTrainerID, 1, 1, 0, "Bulbasaur", 1, 74, 1, 0, 0, 0, 0, 'n');
	}
	if ($previousLevel < 5 && $newLevel >= 5) {
		add_To_Pickup_3($myTrainerID, 13, 1, 0, "Weedle", 1, 80, 8, 7, 0, 0, 0, 'n');
	}
	if ($previousLevel < 6 && $newLevel >= 6) {
		add_To_Pickup_3($myTrainerID, 150, 1, 0, "Mewtwo", -1, 81, 20, 49, 197, 0, 0, 'n');
	}
	if ($previousLevel < 7 && $newLevel >= 7) {
		add_To_Pickup_3($myTrainerID, 4, 1, 0, "Charmander", 1, 82, 6, 5, 0, 0, 0, 'n');
	}
	if ($previousLevel < 8 && $newLevel >= 8) {
		add_To_Pickup_3($myTrainerID, 443, 1, 0, "Gible", 1, 83, 1, 0, 0, 0, 0, 'n');
	}
}
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
//REWARD GYM
function add_To_Pickup_3($myTrainerID, $pokeNum, $pokeLevel, $pokeShiny, $pokeNickname, $pokeGender, $pokeItem, $m1, $m2, $m3, $m4, $originalTrainer, $pokeTag) {
	$db_New = connect_To_ptd2_Trading();
	$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
	$result = $db_New->prepare($query);
	$result->bind_param("iiisiiiiiiiis", $pokeNum, $pokeLevel, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $pokeItem, $myTrainerID, $originalTrainer, $pokeGender, $pokeTag);
	$result->execute();
	$result->execute();
	$result->execute();
	$result->close();
}
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
 function whole_String_To_Num($wordOriginal) {
	return (int)whole_String_To_NumString($wordOriginal);
 }
 function whole_String_To_NumString($wordOriginal) {
	$word = "";
	$converted = 0;
	for ($i = 0; $i<strlen($wordOriginal); $i++) {
		$converted = string_To_Num(substr($wordOriginal, $i, 1));
		if ($converted == -1) {
			return "0";
		}
		$word = $word.$converted;
	}
	return $word;
 }
 function string_To_Num($letter) {
	 if ($letter == "m") {
		return 0;
	}
	if ($letter == "y") {
		return 1;
	}
	if ($letter == "w") {
		return 2;
	}
	if ($letter == "c") {
		return 3;
	}
	if ($letter == "q") {
		return 4;
	}
	if ($letter == "a") {
		return 5;
	}
	if ($letter == "p") {
		return 6;
	}
	if ($letter == "r") {
		return 7;
	}
	if ($letter == "e") {
		return 8;
	}
	if ($letter == "o") {
		return 9;
	}
	if ($letter == "n" || $letter == "h") {
		return 0;
	}
	return -1;
 }
 function get_Length($firstLength, $constantLength) {
	 $total = $constantLength + 1 + $firstLength;
	 $newFirst = strlen($total);	 
	 if ($newFirst != $firstLength) {
	 	return get_Length($newFirst, $constantLength);
	 }
 	return $newFirst.$total;
 }
  function whole_Num_To_String($whichNum) {
	$currentNum = "".$whichNum;
	$word = "";
	for ($i = 0; $i<strlen($currentNum); $i++) {
		$word = $word.num_To_String(substr($currentNum, $i, 1));
	}
	return $word;
 }
 function num_To_String($whichInt) {
 	if ($whichInt == "0") {
		return "m";
	}
	if ($whichInt == "1") {
		return "y";
	}
	if ($whichInt == "2") {
		return "w";
	}
	if ($whichInt == "3") {
		return "c";
	}
	if ($whichInt == "4") {
		return "q";
	}
	if ($whichInt == "5") {
		return "a";
	}
	if ($whichInt == "6") {
		return "p";
	}
	if ($whichInt == "7") {
		return "r";
	}
	if ($whichInt == "8") {
		return "e";
	}
	if ($whichInt == "9") {
		return "o";
	}
	return $whichInt;
 }
 function check_Sum($myString) {
	$myCount = 15;
	for ($i = 0; $i<strlen($myString); $i++) {
		$myCount += convert_String_To_Num_Base_26(substr($myString, $i, 1));
	}
	$myCount = $myCount*3;
	return $myCount;
 }
 function convert_String_To_Num_Base_26($myChar) {
 	if ($myChar == "a") {
		return 1;
	}else if ($myChar == "b") {
		return 2;
	}else if ($myChar == "c") {
		return 3;
	}else if ($myChar == "d") {
		return 4;
	}else if ($myChar == "e") {
		return 5;
	}else if ($myChar == "f") {
		return 6;
	}else if ($myChar == "g") {
		return 7;
	}else if ($myChar == "h") {
		return 8;
	}else if ($myChar == "i") {
		return 9;
	}else if ($myChar == "j") {
		return 10;
	}else if ($myChar == "k") {
		return 11;
	}else if ($myChar == "l") {
		return 12;
	}else if ($myChar == "m") {
		return 13;
	}else if ($myChar == "n") {
		return 14;
	}else if ($myChar == "o") {
		return 15;
	}else if ($myChar == "p") {
		return 16;
	}else if ($myChar == "q") {
		return 17;
	}else if ($myChar == "r") {
		return 18;
	}else if ($myChar == "s") {
		return 19;
	}else if ($myChar == "t") {
		return 20;
	}else if ($myChar == "u") {
		return 21;
	}else if ($myChar == "v") {
		return 22;
	}else if ($myChar == "w") {
		return 23;
	}else if ($myChar == "x") {
		return 24;
	}else if ($myChar == "y") {
		return 25;
	}else if ($myChar == "z") {
		return 26;
	}else{
		return $myChar;
	}
 }
			?>