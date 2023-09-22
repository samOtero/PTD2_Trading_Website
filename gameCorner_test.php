<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Game Corner";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "go";
$query = "select a_story_".$whichProfile." from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($levels);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
	if ($reason == "go") {
		if ($levels < 18) {
			$reason = "notMember";
		}
	}
	if ($reason == "go") {
		$needCoins = false;
 		$totalMaxPlays = 50;
 		$query = "select howManyCoins, howManyTimesUsed, lastTimeUsed from gameCorner WHERE trainerID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($howManyCoins, $timesUsed, $lastTimeUsed);			
		if ($result->affected_rows) {
			$result->fetch();
		}else{
			$needCoins = true;
			$howManyCoins = 100;
		}
		if ($needCoins == true) {
			$lastTimeUsed = date( 'Y-m-d');
			$query = "INSERT INTO gameCorner (trainerID, howManyCoins, lastTimeUsed) VALUES (?,?,?)";
			$result = $db->prepare($query);
			$result->bind_param("iis", $id, $howManyCoins, $lastTimeUsed);
			$result->execute();
			$result->close();
		}else if ($howManyCoins == 0) {
			$howManyCoins = 100;
			$needCoins = true;
			$query = "UPDATE gameCorner SET howManyCoins = ? WHERE trainerID = ?";
			$result = $db->prepare($query);
			$result->bind_param("ii", $howManyCoins, $id);
			$result->execute();
			if ($result->sqlstate=="00000") {
				$result->close();
			}else{
				$result->close();
				$reason = "error";
				//echo "Error in the database. ";
				//echo '<a href="trading.php">Click here to go back.</a>';
				//exit;
			}
		}else if ($timesUsed >= $totalMaxPlays) {
			$today = date('Y-m-d');
			$todayStr = strtotime($today);
			$lastTimeStr = strtotime($lastTimeUsed);
			if ($lastTimeStr < $todayStr) {
				$timesUsed = 0;
				$query = "UPDATE gameCorner SET lastTimeUsed = ?, howManyTimesUsed = 0 WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("si", $today, $id);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$result->close();
				}else{
					$result->close();
					$reason = "error";
					//echo "Error in the database. ";
					//echo '<a href="trading.php">Click here to go back.</a>';
					//exit;
				}
			}
		}
	}
if ($reason == "go") {
	$whichMachine = $_REQUEST['whichMachine'];
	$usedMachine = false;
	if ($timesUsed < $totalMaxPlays) {
		if (!empty($whichMachine)) {
			$usedMachine = true;
			$wonCoins = 0;
			$winChance = 30;
			$cherryWin = 60;
			$arbokWin = 90;
			$barWin = 99;
			$sevenWin = 100;
			if ($whichMachine == 7) {
				$winChance = 20;
				$cherryWin = 30;
				$arbokWin = 60;
				$barWin = 90;
				$sevenWin = 100;
			}else if ($whichMachine == 6) {
			}else if ($whichMachine == 5) {
			}else if ($whichMachine == 4) {
				$winChance = 35;
			}else if ($whichMachine == 3) {
			}else if ($whichMachine == 2) {
				$winChance = 40;
			}else if ($whichMachine == 1) {
			}
			for ($i=$timesUsed; $i<=$totalMaxPlays; $i++) {
				$ranNum = rand(1,100);
				$ranWinNum = rand(1,100);
				if ($ranNum <= $winChance) {
					if ($ranWinNum <= $cherryWin) {
						$wonCoins += 80;
					}else if ($ranWinNum <= $arbokWin) {
						$wonCoins += 150;
					}else if ($ranWinNum <= $barWin) {
						$wonCoins += 1000;
					}else if ($ranWinNum <= $sevenWin) {
						$wonCoins += 3000;
					}
				}
				$howManyCoins-=5;
				$timesUsed++;
			}
			$timesUsed = $totalMaxPlays;
			$howManyCoins += $wonCoins;
			$query = "UPDATE gameCorner SET howManyCoins = ?, howManyTimesUsed = ? WHERE trainerID = ?";
			$result = $db->prepare($query);
			$result->bind_param("iii", $howManyCoins, $timesUsed, $id);
			$result->execute();
			if ($result->sqlstate=="00000") {
				$result->close();
			}else{
				$result->close();
				$reason = "error";
				//echo "Error in the database. ";
				//echo '<a href="trading.php">Click here to go back.</a>';
				//exit;
			}
		}
	}
}
	$urlValidation = "whichProfile=".$whichProfile;
	include 'template/header.php';
?>
<div id="content">
	<?php
		include 'template/navbar.php';
	?>
	<table id="content_table">
		<tr>
			<?php
				include 'template/sidebar.php';
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Game Corner - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else if ($reason == "error") { ?>
					<div class="content">
						 <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
					</div>
                    </div>
                 <?php }else if ($reason == "notMember") { ?>
					<div class="content">
						 <p>Only Team Rocket Members allowed! Pass Giovanni's Test in Chapter 4's Lavender Town to join Team Rocket! Press back on your browser to continue.</p>
					</div>
                    </div>
                 <?php }else{ ?>
                    <div class="content">
						<p>Welcome to the Game Corner! Now that you are part of Team Rocket you can access the fun of the slots and play to get Team Rocket Exclusive pokemon!</p>
						<?php if ($needCoins == true) { ?>
<p><b>It seems you don't have any coins so we have given you a complimentary 100 coins to start. Compliments of Giovanni.</b></p>
<?php } ?>
                        <p>You have <?php echo $howManyCoins ?> Casino Coins. All Slot Machines cost 5 Casino Coin and you can play once a day. Each play will count as 50 tries. You have played <?php echo $timesUsed ?> times since your last reset on <?php echo $lastTimeUsed ?>.</p>
						<?php if ($timesUsed >= $totalMaxPlays && $usedMachine == false) { ?>
<p><b>Okay kid, you have had enough for today. Come back tomorrow.</b></p>
<?php } ?>
<?php if ($usedMachine == true) { 
			if ($wonCoins == 0) {
				?>
                <p><b>You played Slot Machine <?php echo $whichMachine ?> and didn't win any coins! </b></p>
                <?php }else{ ?>
<p><b>You played Slot Machine <?php echo $whichMachine ?> and won <?php echo $wonCoins ?> coins! </b></p>
<?php }
} ?>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=1">Play Slot Machine 1</a> </p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=2">Play Slot Machine 2</a></p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=3">Play Slot Machine 3</a></p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=4">Play Slot Machine 4</a></p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=5">Play Slot Machine 5</a></p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=6">Play Slot Machine 6</a></p>
<p><a href="gameCorner_test.php?<?php echo $urlValidation ?>&whichMachine=7">Play Slot Machine 7</a></p>
					</div>
				</div>
				<div class="block">
					<div class="title thin"><p>Game Rewards - Normal pokemon have a 1% chance to be shiny.</p></div>
				</div>
                <div class="block">
					<div class="title thin"><p>Random Non-Evolved Shadow Pokemon - 300,000 Casino Coins - <a href="gameCorner_buy.php?<?php echo $urlValidation ?>&amp;who=13">Buy</a></div>
				</div>
						<?php
pokeBox("Abra", 1, 0, 116, 0, 0, 0, 63, '120 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=1">Buy</a>');
pokeBox("Clefairy", 1, 0, 48, 5, 0, 0, 35, '500 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=2">Buy</a>');
pokeBox("Pinsir", 1, 0, 269, 12, 0, 0, 127, '2,500 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=3">Buy</a>');
pokeBox("Dratini", 1, 0, 70, 43, 0, 0, 147, '2,800 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=4">Buy</a>');
pokeBox("Scyther", 1, 0, 4, 43, 0, 0, 123, '5,500 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=5">Buy</a>');
pokeBox("Porygon", 1, 0, 1, 263, 0, 0, 137, '6,500 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=6">Buy</a>');
pokeBox("Abra", 1, 1, 116, 0, 0, 0, 63, '9,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=7">Buy</a>');
pokeBox("Clefairy", 1, 1, 48, 5, 0, 0, 35, '15,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=8">Buy</a>');
pokeBox("Pinsir", 1, 1, 269, 12, 0, 0, 127, '50,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=9">Buy</a>');
pokeBox("Dratini", 1, 1, 70, 43, 0, 0, 147, '100,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=10">Buy</a>');
pokeBox("Scyther", 1, 1, 4, 43, 0, 0, 123, '120,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=11">Buy</a>');
pokeBox("Porygon", 1, 1, 1, 263, 0, 0, 137, '150,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=12">Buy</a>');
?>		  
				<div class="avatars">
					<?php avatarBox("Duskull Costume", "b_15", "no", '150,000 Casino Coins - <a href="gameCorner_buy.php?'.$urlValidation.'&who=14">Buy</a>'); ?>
				</div>
            
                <?php } ?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>