<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Daily Activity";
	$pageMenuset = "extended";
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
				$amountNeed = 5;
				$prize = 10;
				if ($reason != "savedOutside") {
					$myAction = $_REQUEST['action'];
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
					
					$query = "select usedOn from dailyCoinRewards WHERE trainerID = ? AND activityID = 1";
					$result = $db2->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$result->bind_result($lastTimeUsed);			
					if ($result->affected_rows) {
						$result->fetch();
					}else{
						$lastTimeUsed = -1;
					}
					$result->free_result();
					$result->close();
					$rightNow = strtotime(date( 'Y-m-d'));
					$yourLastTimeUsed = strtotime($lastTimeUsed);
					if ($lastTimeUsed != -1) {
						if ($yourLastTimeUsed >= $rightNow) {
							$prize = 1;
						}
					}
					
					$db_New = connect_To_ptd2_Trading();
					$query = "select traderID from tradeRecords WHERE trainerID = ? AND used = 0";
					$result = $db_New->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$howManyTrades = $result->affected_rows;		
					$result->free_result();
					$result->close();
					$gotPrize = false;
					if ($myAction == "1") {
						if ($howManyTrades >= $amountNeed) {
							$db_New->autocommit(false);
							$db2->autocommit(false);
							$transactionResult = true;
							$query = "UPDATE tradeRecords SET used = 1  WHERE trainerID = ? AND used = 0 LIMIT ".$amountNeed;
							$result = $db_New->prepare($query);
							$result->bind_param("i",$id);
							if (!$result->execute()) {
								$transactionResult = false;
							}
							$result->close();
							$howManyTrades -= $amountNeed;
							$gotPrize = true;
							$howManyDailyCoins += $prize;
							
							$newTime = date( 'Y-m-d');
							$activityID = 1;
							if ($lastTimeUsed == -1) {
								$query = "INSERT INTO dailyCoinRewards (trainerID, usedOn, activityID) VALUES (?,?,?)";
								$result = $db2->prepare($query);
								$result->bind_param("isi", $id, $newTime, $activityID);
								if (!$result->execute()) {
									$transactionResult = false;
								}
								$result->close();
							}else{
								$query = "UPDATE dailyCoinRewards SET usedOn = ? WHERE trainerID = ? AND activityID = ?";
								$result = $db2->prepare($query);
								$result->bind_param("sii", $newTime, $id, $activityID);
								if (!$result->execute()) {
									$transactionResult = false;
								}
								$result->close();
							}
							$query = "select howMany from dailyCoins WHERE trainerID = ?";
							$result = $db2->prepare($query);
							$result->bind_param("i", $id);
							$result->execute();		
							if ($result->affected_rows) {
								$result->close();
								$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
								$result = $db2->prepare($query);
								$result->bind_param("ii", $howManyDailyCoins, $id);
								if (!$result->execute()) {
									$transactionResult = false;
								}
								$result->close();
							}else{
								$result->close();
								$query = "INSERT INTO dailyCoins (trainerID, howMany) VALUES (?,?)";
								$result = $db2->prepare($query);
								$result->bind_param("ii", $id, $howManyDailyCoins);
								if (!$result->execute()) {
									$transactionResult = false;
								}
								$result->close();
							}
							if ($transactionResult == true) {
								$db2->commit();
								$db_New->commit();
							}else{
								$db2->rollback();
								$db_New->rollback();
								$reason = "error";
							}
							$db_New->autocommit(true);
							$db2->autocommit(true);
						}
					}
					$db_New->close();
					$db2->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Daily Activity - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
				  <div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                     <?php }else if ($reason == "error") { ?>
           				<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
                    <?php }else { ?>
					<p>You have (<?php echo $howManyDailyCoins ?>) Daily Coins to use in adopting a pokémon and buying items.
					  <p><img src="images/old_front.png" width="34" height="48"></p>
					<p>Hello trainer! Welcome to the Old Man's Daily Activity!</p>
					<p> Every day you will earn 10 Daily Coins the first time you do each activity that day, after the first time you will get 1 Daily Coin for completing the activity.<br>
					  Below are the activities you can do today!</p>
					<p><strong>Activity #1:</strong> Trade with <?php echo $amountNeed ?> different trainers in the PTD2 Trading Center <strong>in one day</strong>. <br>
				    So far you have traded with (<?php echo $howManyTrades ?>) trainers since your last reward. Once you reach <?php echo $amountNeed ?> come back here and claim your reward!</p>
                    <?php 
					if ($howManyTrades >= $amountNeed && $gotPrize == false) { ?>
					<p><strong>You did it! Your prize is (<?php echo $prize ?>) Daily Coin(s)! <a href="dailyActivity2.php?<?php echo $urlValidation ?>&action=1">Click here to collect it!</a></strong>
                    <?php 
					}else if ($gotPrize == true) { ?>
                    <p><strong>You earned (<?php echo $prize ?>) Daily Coin(s)! Do it again to earn more Daily Coins!</strong>
                     <?php 
					} ?>
                    <p><strong>Activity #2:</strong> Breed pokémon in the Breeding Center. <br>Each time you breed a new pokémon you will earn 10 Daily Coins.<br></p>
					  <?php } ?>
				    </p>
				  </div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>