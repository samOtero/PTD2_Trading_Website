<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Adoption";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php

	//$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	 $urlValidation = "whichProfile=".$whichProfile;
	include 'template/header.php';
?>
<div id="content">
	<?php
		include 'template/navbar2.php';
	?>
	<table id="content_table">
		<tr>
			<?php
				include 'template/sidebar2.php';
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Pokémon Adoption - <a href="adoption2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason != "savedOutside") { 
					$db = connect_To_Database();
						$query = "select howManyCoins from sndCoins WHERE trainerID = ?";
						$result = $db->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($howManyCoins);			
						if ($result->affected_rows) {
							$result->fetch();
						}else{
							$howManyCoins = 0;
						}
						$result->free_result();
						$result->close();
						
						$db2 = connect_To_ptd2_Story_Database();
						$query = "select howMany from dailyCoins WHERE trainerID = ?";
						$result = $db2->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($howManyDailyCoins);			
						if ($result->affected_rows) {
							$result->fetch();
						}else{
							$howManyDailyCoins = 0;
						}
						$result->free_result();
						$result->close();
						$db2->close();
					?>
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins and (<?php echo $howManyDailyCoins ?>) Daily Coins to use in adopting a pokémon. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
                        <?php } else { ?>
                        <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                        <?php } ?>
					</div>
				</div>
				<?php
				if ($reason != "savedOutside") { 
$whichList = $_REQUEST['list'];
if ($whichList <= 5) {
	$whichList = 10000;
}
//*
if ($whichList == 10) { //show user created adoptions
//*
	$db->close();
	$costType = $_REQUEST['costType'];
	$costTypeSQL = "";
	if ($costType == 1) {
		$costTypeSQL = " AND sndCost = 1";
	}else if ($costType == 2) {
		$costTypeSQL = " AND sndCost >= 5 AND sndCost <= 9";
	}else if ($costType == 3) {
		$costTypeSQL = " AND sndCost >= 10 AND sndCost <= 14";
	}else if ($costType == 4) {
		$costTypeSQL = " AND sndCost >= 15 AND sndCost <= 19";
	}else if ($costType == 5) {
		$costTypeSQL = " AND sndCost = 20";
	}else if ($costType == 7) {
		$costTypeSQL = " AND sndCost = 2";
	}else {//6
		$costType = 6;
		$costTypeSQL = " AND sndCost >= 3 AND sndCost <= 4";
	}
	$db_New = connect_To_ptd2_Trading();
	
	$limit = 100;
$page = mysql_escape_string($_GET['page']);
if ($page) {
	$start = ($page -1) * $limit;
}else{
	$page = 1;
	$start = 0;
}

	$queryTotal = "SELECT COUNT(*) FROM trainer_trades WHERE sndCost > 0".$costTypeSQL;
	$resultTotal = $db_New->prepare($queryTotal);
	$resultTotal->execute();
	$resultTotal->store_result();
	$resultTotal->bind_result($totalPickups);
	$resultTotal->fetch();
	$resultTotal->free_result();
	$resultTotal->close();
	
	if ($start > 0) {
		if ($totalPickups < $start) {
			$start = 0;
			$page = 1;
		}
	}
	if ($page == 0) {$page = 1;};
	$lastpage = ceil($totalPickups/$limit);
	$paginateText = 'Total: '.$totalPickups;
	$didFirst = false;
	$whichURL = "adoption_list2.php?list=10&costType=$costType";
	
	if ($lastpage > 1) {
	$paginateText .= ' - Pages: ';
	if ($page > 1) {
		$paginateText .= '<a href="'.$whichURL.'&page='.($page -1).'&'.$urlValidation.'">Prev</a>';
		$didFirst = true;
	}
	for ($counter = 1; $counter <= $lastpage; $counter++) {
		if ($didFirst == true) {
			$paginateText .= ' - ';
		}
		if ($counter == $page) {
			$paginateText .= $counter;
		}else{
			$paginateText .= '<a href="'.$whichURL.'&page='.$counter.'&'.$urlValidation.'">'.$counter.'</a>';
		}
		$didFirst = true;
	}
	if ($page < $lastpage) {
		$paginateText .= ' - ';
		$paginateText .= '<a href="'.$whichURL.'&page='.($page +1).'&'.$urlValidation.'">Next</a>';
		$didFirst = true;
	}
}
	
	echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
	$query = "SELECT num, lvl, shiny, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, sndCost FROM trainer_trades WHERE sndCost > 0".$costTypeSQL." ORDER BY sndCost, num LIMIT $start, $limit";
	$result = $db_New->prepare($query);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $sndCost);
	//**
	if ($hmp == 0) {
		echo '<div class = "block"><div class="content"><p>No Pokemon found in this category.</p></div></div>';
	//**
	}else{
		for ($i=0; $i<$hmp; $i++) {
			$result->fetch();
			$isHacked = "";
			$pokeNickname = stripslashes($pokeNickname);
			if ($myTag == "h") {
				$isHacked = " - <b>(Hacked Version)</b>";
			}
			$pokeNickname = $pokeNickname.$isHacked;
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, 'Adopt me for <a href="adoptTrade2.php?'.$urlValidation.'&tradeID='.$tradeID.'&IDCheck='.$id.'">('.$sndCost.') SnD Coins</a>', $pokeGender, $pokeItem, $myTag);
		}
	//**
	}
	$result->free_result();
	$result->close();
	$db_New->close();
	echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';

}else{
$query = "select costInCoins, dailyCost from sndCoins_category WHERE catID = ?";
$result = $db->prepare($query);
$result->bind_param("i", $whichList);
$result->execute();
$result->store_result();
$result->bind_result($cost, $dailyCost);
$result->fetch();
$result->free_result();
$result->close();
$query = "select extraInfo, invID from sndCoins_inventory WHERE category = ? AND whichGame = 'ptd2'";
	$result = $db->prepare($query);
	$result->bind_param("i", $whichList);
	$result->execute();
	$result->store_result();
	$result->bind_result($extraInfo, $invID);
	$hm = $result->affected_rows;
	if ($hm == 0) {
		$result->free_result();
		$result->close();
		$db->close();
		?>
        <div class="block">
        <div class="content">
        <p>No pokemon found in this category.</p>
        </div>
        </div>
        <?php
	}else{
		for ($i=0; $i<$hm; $i++) {
			$result->fetch();
			$infoList = explode("|", $extraInfo);
			$who = $infoList[0];
			$isShiny = $infoList[1];
			$myLevel = $infoList[2];
			$move1 = $infoList[3];
			$move2 = $infoList[4];
			$move3 = $infoList[5];
			$move4 = $infoList[6];
			$nickname = $infoList[9];
			$gender = $infoList[11];
			$item = $infoList[12];
			pokeBox2($nickname, $myLevel, $isShiny, $move1, $move2, $move3, $move4, $who, 'Adopt me for <a href="adopt_me2.php?'.$urlValidation.'&who='.$invID.'">('.$cost.') SnD Coins</a> or <a href="adopt_me2.php?'.$urlValidation.'&who='.$invID.'&daily=1">('.$dailyCost.') Daily Coins</a>', $gender, $item);
		}
		$result->free_result();
		$result->close();
		$db->close();
	}
				}
}
//*
?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>