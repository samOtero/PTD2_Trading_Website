<?php
	$playingGame = 0;
	$cookieOutcome = "";
	if (isset($_COOKIE["ver"])) {
		$cookieOutcome .= "Have Cookie!";
		$playingGame = $_COOKIE["ver"];
		if ($playingGame != 1 || $_COOKIE["cProf"] != $whichProfile) {
			$cookieOutcome .= "Cookie Outdated!";
			$playingGame = 0;
		}else{
			$cookieOutcome .= "Cookie Good!";
			$avatar1 = $_COOKIE["avatar1_1"];
			$avatar2 = $_COOKIE["avatar1_2"];
			$avatar3 = $_COOKIE["avatar1_3"];
			$nickname1 = $_COOKIE["nickname1_1"];
			$nickname2 = $_COOKIE["nickname1_2"];
			$nickname3 = $_COOKIE["nickname1_3"];
			$badges = $_COOKIE["badges1"];
			$money = $_COOKIE["moneys1"];
		}
	}else{
		$cookieOutcome .= "No Cookie!";
	}
	if ($playingGame == 0) {
		$db = connect_To_Database();
		$query = "select  avatar1, avatar2, avatar3, Nickname_1, Nickname_2, Nickname_3, Badge_".$whichProfile.", Money_".$whichProfile." from poke_accounts WHERE trainerID = ? AND currentSave = ?";
		$result = $db->prepare($query);
		$result->bind_param("is", $id, $currentSave);
		$result->execute();
		$result->store_result();
		$result->bind_result($avatar1, $avatar2, $avatar3, $nickname1, $nickname2, $nickname3, $badges, $money);			
		if ($result->affected_rows) {
			$result->fetch();
			$cookieOutcome .= "Setting Cookie!";
			$playingGame = 1;
			$expireTime = time()+60*60*24;
			setcookie("ver", 1, $expireTime);
			setcookie("cProf", $whichProfile, $expireTime);
			setcookie("avatar1_1", $avatar1, $expireTime);
			setcookie("avatar1_2", $avatar2, $expireTime);
			setcookie("avatar1_3", $avatar3, $expireTime);
			setcookie("nickname1_1", $nickname1, $expireTime);
			setcookie("nickname1_2", $nickname2, $expireTime);
			setcookie("nickname1_3", $nickname3, $expireTime);
			setcookie("badges1", $badges, $expireTime);
			setcookie("moneys1", $money, $expireTime);
		}else{
			$reason = "savedOutside";
		}
		$result->close();
		$db->close();
	}
?>