<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Remove Hacked Tag";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	$whichDB = $profileInfo[5];
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
					<div class="title"><p>Remove Hacked Tag - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else {
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
					<div class="content">
                    <p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins and (<?php echo $howManyDailyCoins ?>) Daily Coins to use in Removing a hacked tag from your pokemon. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more Sam and Dan coins.</a></p>
						<p>Here you can remove the "Hacked" tag from you pokemon. You can spend 1 SnD coin or 50 Coins to remove the tag from one Pokemon. You can also use 10 SnD Coins to remove the tag from all your profile pokemon. You may only use SnD Coins to remove the tag from all pokemon at once.</p>
					</div>
				</div>
                <?php 
$dbActual = get_PTD2_Pokemon_Database($whichDB);

$limit = 250;
$page = mysql_escape_string($_GET['page']);
if ($page) {
	$start = ($page -1) * $limit;
}else{
	$page = 1;
	$start = 0;
}

	$queryTotal = "SELECT COUNT(*) FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND myTag = 'h'";
	$resultTotal = $dbActual->prepare($queryTotal);
	$resultTotal->bind_param("ii", $id, $whichProfile);
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
		$whichURL = "removeHack2.php?";
		
		if ($lastpage > 1) {
		$paginateText .= ' - Pages: ';
		if ($page > 1) {
			$paginateText .= '<a href="'.$whichURL.'page='.($page -1).'&'.$urlValidation.'">Prev</a>';
			$didFirst = true;
		}
		for ($counter = 1; $counter <= $lastpage; $counter++) {
			if ($didFirst == true) {
				$paginateText .= ' - ';
			}
			if ($counter == $page) {
				$paginateText .= $counter;
			}else{
				$paginateText .= '<a href="'.$whichURL.'page='.$counter.'&'.$urlValidation.'">'.$counter.'</a>';
			}
			$didFirst = true;
		}
		if ($page < $lastpage) {
			$paginateText .= ' - ';
			$paginateText .= '<a href="'.$whichURL.'page='.($page +1).'&'.$urlValidation.'">Next</a>';
			$didFirst = true;
		}
	}


	$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND myTag = 'h' ORDER BY num, lvl LIMIT $start, $limit";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $whichProfile);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeHoF);
	if ($hmp == 0) {
		?>
        <div class="block">
        <div class="content">
		<p>You have no pokemon with a "Hacked Tag" in this profile.</p>
		</div>
		</div>
        <?php
	}else{
		if ($totalPickups < 10) {
			echo '<div class = "block"><div class="content"><p>You have less than 10 pokemon with a hacked tag. So the option to remove all of them with 10 SnD Coins does not help you.</p></div></div>';
		}else{
			echo '<div class = "block"><div class="content"><p><a href="removeHack2ActualAll.php?'.$urlValidation.'">Click here to remove all your current hacked tags for (10) SnD Coins.</a></p></div></div>';
		}
		echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
	for ($i=1; $i<=$hmp; $i++) {
		$result->fetch();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " (Hacked Version)";
		}
		//$changeNick = ' | <a href="changePokeNickname2.php?'.$urlValidation.'&pokeID='.$pokeID.'">Change Nickname</a>';
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		//$extra = '<a href="tradeMeSetup2.php?'.$urlValidation.'&pokeID='.$pokeID.'">Trade</a>'.$changeNick;
		$extra = 'Remove Hack Tag: <a href="removeHack2Actual.php?'.$urlValidation.'&pokeID='.$pokeID.'">(1) Snd Coins</a> or <a href="removeHack2Actual.php?'.$urlValidation.'&pokeID='.$pokeID.'&daily=1">(50) Daily Coins</a>';
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra,$pokeGender, $pokeItem, $pokeHoF);
	}
	echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
	}
	$result->free_result();
	$result->close();
	$dbActual->close();
?>
				<?php }?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>