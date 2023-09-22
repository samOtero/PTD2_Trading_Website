<?php
	$playingGame = 0;
	$cookieOutcome = "";
	if (isset($_COOKIE["ver"])) {
		$cookieOutcome .= "Have Cookie!";
		$playingGame = $_COOKIE["ver"];
		if ($playingGame != 2 || $_COOKIE["cProf"] != $whichProfile) {
			$cookieOutcome .= "Cookie Outdated!";
			$playingGame = 0;
		}else{
			$cookieOutcome .= "Cookie Good!";
			$profileInfo = array($_COOKIE["vName"], $_COOKIE["nickname2"], $_COOKIE["badges2"], $_COOKIE["moneys2"], $_COOKIE["avatar2"], $_COOKIE["db2"]);
		}
	}else{
		$cookieOutcome .= "No Cookie!";
	}
	if ($playingGame == 0) {
		$cookieOutcome .= "Setting Cookie!";
		$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
		$expireTime = time()+60*60*24;
		setcookie("ver", 2, $expireTime);
		setcookie("cProf", $whichProfile, $expireTime);
		setcookie("vName", $profileInfo[0], $expireTime);
		setcookie("nickname2", $profileInfo[1], $expireTime);
		setcookie("badges2", $profileInfo[2], $expireTime);
		setcookie("moneys2", $profileInfo[3], $expireTime);
		setcookie("avatar2", $profileInfo[4], $expireTime);
		setcookie("db2", $profileInfo[5], $expireTime);			
	}
?>