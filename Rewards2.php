<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Funding Rewards";
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
				if ($reason != "savedOutside") {
					$myAction = $_REQUEST['action'];
					$myCode = $_REQUEST['myCode'];
					$db = connect_To_Cosmoids();
					//
					if ($myAction == "submit") {
						///
						if (empty($myCode)) {
							$reason = "codeWrong";
						}else{
							$addNewCodeResult = add_New_Code($db, $id, $myCode);
							////
							if ($addNewCodeResult == -1) {
								$reason = "codeWrong";
							}else if ($addNewCodeResult == -2) {
								$reason = "codeUsed";
							}else{
								$reason = "codeAdded";
							}
							////
						}
						///
					}
					//
					$haveCosmoidsAlphaAccess = false;
					$haveRayquazaAccess = false;
					$haveCosmoidStarterAccess = false;
					$haveHeatranAccess = false;
					$haveXYLegendaryAccess = false;
					$haveXYLegendary2Access = false;
					$usedCosmoidStarter = false;
					$usedRayquaza = false;
					$usedHeatran = false;
					$usedXYLegendary = false;
					$usedXYLegendary2 = false;
					$codeIDList = get_code_ID($db, $id);
					$fundingAmount = 0;
					$fundingUserList = array();
					//
					if ($codeIDList[0] != -1) {
						///
						for ($i=0; $i<count($codeIDList); $i++) {
							$fundingUserID = get_UserID_From_CodeID($db, $codeIDList[$i]);
							////
							$continue = false;
							if ($fundingUserID != -1) {
								for ($b=0; $b<count($fundingUserList); $b++) {
									if ($fundingUserID == $fundingUserList[$b]) {
										$continue = true;
										break;
									}
								}
								if ($continue == true) {
									continue;
								}
								array_push($fundingUserList, $fundingUserID);
								$haveCosmoidsAlphaAccess = true;
								$fundingAmount += get_Funding_Amount($db, $fundingUserID);
								if ($fundingAmount >= 5) {
									$haveRayquazaAccess = true;
									if ($usedRayquaza == false && used_Reward($db, $fundingUserID, 1) != 0) {
										$usedRayquaza = true;
									}
								}
								if ($fundingAmount >= 9.99 || $id === 1) {
									$haveCosmoidStarterAccess = true;
									if ($usedCosmoidStarter == false && used_Reward($db, $fundingUserID, 2) != 0) {
										$usedCosmoidStarter = true;
									}
									$haveHeatranAccess = true;
									if ($usedHeatran == false && used_Reward($db, $fundingUserID, 3) != 0) {
										$usedHeatran = true;
									}
								}
								if ($fundingAmount >= 19.99 || $id === 1) {
									$haveXYLegendaryAccess = true;
									if ($usedXYLegendary == false && used_Reward($db, $fundingUserID, 4) != 0) {
										$usedXYLegendary = true;
									}
								}
								if ($fundingAmount >= 24.99 || $id === 1) {
									$haveXYLegendary2Access = true;
									if ($usedXYLegendary2 == false && used_Reward($db, $fundingUserID, 5) != 0) {
										$usedXYLegendary2 = true;
									}
								}
							}
							////
						}
						///
						///
						$db_New = connect_To_ptd2_Trading();
						$db->autocommit(false);
						$db_New->autocommit(false);
						$transactionFlag = true;
						if ($haveRayquazaAccess == true) {
							if ($usedRayquaza == false) {
								if ($myAction == "prize1") {
									$reason = "prize1Redeem";
									$transactionFlag = redeem_Reward($db, $fundingUserID, 1, $transactionFlag);
									$transactionFlag = insert_Rayquaza($db_New, $id, $transactionFlag);
									$usedRayquaza = $transactionFlag;
								}
							}
						}
						if ($haveHeatranAccess == true) {
							if ($usedHeatran == false) {
								if ($myAction == "prize2") {
									$reason = "prize2Redeem";
									$transactionFlag = redeem_Reward($db, $fundingUserID, 3, $transactionFlag);
									$transactionFlag = insert_Heatran($db_New, $id, $transactionFlag);
									$usedHeatran = $transactionFlag;
								}
							}
						}
						if ($haveCosmoidStarterAccess == true) {
							if ($usedCosmoidStarter == false) {
								if ($myAction == "prize3") {
									$reason = "prize3Redeem";
									$transactionFlag = redeem_Reward($db, $fundingUserID, 2, $transactionFlag);
									$myChoice = $_REQUEST['choice'];
									$transactionFlag = insert_Cosmoid($db_New, $id, $transactionFlag, $myChoice);
									$usedCosmoidStarter = $transactionFlag;
								}
							}
						}
						if ($haveXYLegendaryAccess == true) {
							if ($usedXYLegendary == false) {
								if ($myAction == "prize4") {
									$reason = "prize4Redeem";
									$transactionFlag = redeem_Reward($db, $fundingUserID, 4, $transactionFlag);
									$myChoice = $_REQUEST['choice'];
									$transactionFlag = insert_XY($db_New, $id, $transactionFlag, $myChoice);
									$usedXYLegendary = $transactionFlag;
								}
							}
						}
						if ($haveXYLegendary2Access == true) {
							if ($usedXYLegendary2 == false) {
								if ($myAction == "prize5") {
									$reason = "prize4Redeem";
									$transactionFlag = redeem_Reward($db, $fundingUserID, 5, $transactionFlag);
									$myChoice = $_REQUEST['choice'];
									$transactionFlag = insert_XY($db_New, $id, $transactionFlag, $myChoice);
									$usedXYLegendary2 = $transactionFlag;
								}
							}
						}
						if ($transactionFlag == true) {
							$db->commit();
							$db_New->commit();
						}else{
							$db->rollback();
							$db_New->rollback();
							$reason = "error";
						}
						$db->autocommit(true);
						$db_New->autocommit(true);
						$db_New->close();
						///
					}
					//
					$db->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Funding Rewards - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
				  <div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else if ($reason == "error") { ?>
                    <p>An error has occured. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
					<p>Enter your code to receive your rewards!</p>
					<form name="form1" method="post" action="Rewards2.php?<?php echo $urlValidation ?>&action=submit">
					  <input type="text" name="myCode" id="MyCode">
				      <input type="submit" name="button" id="button" value="Submit">
					</form>
                    <?php if ($reason == "codeWrong") {
							echo '<p><strong>Error: The code you entered is not valid.</strong></p>';
						}else if ($reason == "codeUsed")  {
							echo '<p><strong>Error: The code you entered is already used.</strong></p>';
						}else if ($reason == "codeAdded")  {
							echo '<p><strong>Your code was added!</strong></p>';
						}else if ($reason == "prize1Redeem") {
							echo '<p><strong>Shiny Rayquaza Added to your Pickup area! Go to Home to transfer it to your account.</strong></p>';
						}else if ($reason == "prize2Redeem") {
							echo '<p><strong>Shiny Heatran Added to your Pickup area! Go to Home to transfer it to your account.</strong></p>';
						}else if ($reason == "prize3Redeem") {
							echo '<p><strong>The Cosmoid Starter has been Added to your Pickup area! Go to Home to transfer it to your account.</strong></p>';
						}else if ($reason == "prize4Redeem") {
							echo '<p><strong>The Legendary Pokemon has been Added to your Pickup area! Go to Home to transfer it to your account.</strong></p>';
						}
						echo '<p>You have donated ($'.$fundingAmount.') to help us keep making you games. Thanks!';
						echo '<p><a href="http://samdangames.blogspot.com/p/funding.html">Learn more about our Fundraiser, including what prizes you can get!</a></p>';
						echo '<p>You have the following rewards:</p>';
						
						$rewardCount = 0;
						if ($haveCosmoidsAlphaAccess == true) {
							$rewardCount++;
							echo '<p><strong>Early Alpha Access to Cosmoids TD</strong>: <a href="RewardsCosmoids.php?'.$urlValidation.'"> Play now!</a></p>';
						}else{
							echo '<p><strong>Early Alpha Access to Cosmoids TD</strong>: Locked (Donate any amount to Unlock)</p>';
						}
						if ($haveRayquazaAccess == true) {
							$rewardCount++;
							echo '<p><strong>Shiny Rayquaza</strong>:';
							if ($usedRayquaza == true) {
								echo ' Already Redeemed</p>';
							}else{
								echo ' <a href="Rewards2.php?'.$urlValidation.'&action=prize1">Redeem Prize Now!</a></p>';
							}
						}else{
							echo '<p><strong>Shiny Rayquaza</strong>: Locked (Donate $5 or more to Unlock)';
						}
						if ($haveCosmoidStarterAccess == true) {
							$rewardCount++;
							echo '<p><strong>Cosmoid Starter for PTD2</strong>:';
							if ($usedCosmoidStarter == true) {
								echo ' Already Redeemed</p>';
							}else{
								echo ' <a href="Rewards2.php?'.$urlValidation.'&action=prize3&choice=1">Chameleaf (Grass Chameleon)</a> |  <a href="Rewards2.php?'.$urlValidation.'&action=prize3&choice=2">Coalla (Fire Koala)</a> |  <a href="Rewards2.php?'.$urlValidation.'&action=prize3&choice=3">Bubbull (Water Buffalo)</a></p>';
							}
						}else{
							echo '<p><strong>Cosmoid Starter for PTD2</strong>: Locked (Donate $10 or more to Unlock)';
						}
						if ($haveHeatranAccess == true) {
							$rewardCount++;
							echo '<p><strong>Shiny Heatran</strong>:';
							if ($usedHeatran == true) {
								echo ' Already Redeemed</p>';
							}else{
								echo ' <a href="Rewards2.php?'.$urlValidation.'&action=prize2">Redeem Prize Now!</a></p>';
							}
						}else{
							echo '<p><strong>Shiny Heatran</strong>: Locked (Donate $10 or more to Unlock)';
						}
						if ($haveXYLegendaryAccess == true) {
							$rewardCount++;
							echo '<p><strong>Pokemon X and Y Legendary</strong>:';
							if ($usedXYLegendary == true) {
								echo ' Already Redeemed</p>';
							}else{
								echo ' <a href="Rewards2.php?'.$urlValidation.'&action=prize4&choice=1">Xerneas</a> |  <a href="Rewards2.php?'.$urlValidation.'&action=prize4&choice=2">Yveltal</a></p>';
							}
						}else{
							echo '<p><strong>Pokemon X and Y Legendary</strong>: Locked (Donate $20 or more to Unlock)';
						}
						if ($haveXYLegendary2Access == true) {
							$rewardCount++;
							echo '<p><strong>Pokemon X and Y Legendary</strong>:';
							if ($usedXYLegendary2 == true) {
								echo ' Already Redeemed</p>';
							}else{
								echo ' <a href="Rewards2.php?'.$urlValidation.'&action=prize5&choice=1">Xerneas</a> |  <a href="Rewards2.php?'.$urlValidation.'&action=prize5&choice=2">Yveltal</a></p>';
							}
						}else{
							echo '<p><strong>Pokemon X and Y Legendary</strong>: Locked (Donate $25 or more to Unlock)';
						}
						if ($rewardCount == 0) {
							echo '<p>You have no rewards on this account. Donate to our Fundraiser to receive different rewards like Shiny Rayquaza. Read more about it in our blog. You can also watch a video to donate to us without using a credit card. See below.</p>';
						}
						//////////////////////////////////////////////////////////////////////////////////////////////////
						//////////////////////////////////////////////////////////////////////////////////////////////////
							echo "<p><strong>Find other great ways to donate below!</strong><br><strong>Note:</strong> 10 Funding MicroCents = 1 Cent Donated and 1,000 Funding MicroCents = 1 Dollar Donated</p>";
							echo '<p><strong>Watch a video and get rewards!</strong> A great way to fund us without using a credit card!</p>'; 
                        	echo '<p><div class="btn" id="ssaBrandConnectBtn">Looking for Sponsor Videos...</div></p>';
							//echo '<p>&nbsp;</p>';
							echo '<p><strong>Complete any of the offers below and get rewards!</strong> Free offers and Surveys are available, along with pay with your phone for those looking for other options to pay with.</p>'; 
							echo '<p>&nbsp;</p>';
                            ?>

					<p>&nbsp;</p>
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
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function insert_Rayquaza($db_New, $id, $transactionFlag) {
		$who = 384;
		$nickname = "Rayquaza";
		$move1 = 77;
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
		$myLevel = 1;
		$isShiny = 1;
		$item = 0;
		$gender = -1;
		$myTag = "n";
		$originalTrainer = -$id;
		//$db_New = connect_To_ptd2_Trading();
		$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
		$result = $db_New->prepare($query);
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		//$db_New->close();
		return $transactionFlag;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function insert_Heatran($db_New, $id, $transactionFlag) {
		$who = 485;
		$nickname = "Heatran";
		$move1 = 296;
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
		$myLevel = 1;
		$isShiny = 1;
		$item = 0;
		$gender = 1;
		$myTag = "n";
		$originalTrainer = -$id;
		//$db_New = connect_To_ptd2_Trading();
		$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
		$result = $db_New->prepare($query);
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$gender = 2;
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		//$db_New->close();
		return $transactionFlag;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function insert_XY($db_New, $id, $transactionFlag, $myChoice) {
		if ($myChoice == 2) {
			$who = 717;
			$nickname = "Yveltal";
			$move1 = 318;
		}else{
			$who = 716;
			$nickname = "Xerneas";
			$move1 = 27;
		}
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
		$myLevel = 70;
		$isShiny = 1;
		$item = 0;
		$gender = -1;
		$myTag = "n";
		$originalTrainer = -$id;
		$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
		$result = $db_New->prepare($query);
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		return $transactionFlag;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function insert_Cosmoid($db_New, $id, $transactionFlag, $myChoice) {
		if ($myChoice == 2) {
			$who = 2503;
			$nickname = "Coalla";
			$move1 = 6;
		}else if ($myChoice == 3) {
			$who = 2506;
			$nickname = "Bubbull";
			$move1 = 11;
		}else{
			$who = 2500;
			$nickname = "Chameleaf";
			$move1 = 119;
		}
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
		$myLevel = 1;
		$isShiny = 0;
		$item = 0;
		$gender = 1;
		$myTag = "n";
		$originalTrainer = -$id;
		//$db_New = connect_To_ptd2_Trading();
		$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
		$result = $db_New->prepare($query);
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$gender = 2;
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		//$db_New->close();
		return $transactionFlag;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function redeem_Reward($db, $userID, $rewardID, $transactionFlag) {
		$query = "INSERT INTO rewardsRecords (userID, rewardID) VALUES (?, ?)";
		$result = $db->prepare($query);
		$result->bind_param("ii", $userID, $rewardID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		return $transactionFlag;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
	function used_Reward($db, $userID, $rewardID) {
		$query = "SELECT rewardID FROM rewardsRecords WHERE userID = ? AND rewardID = ?";
		$result = $db->prepare($query);
		$result->bind_param("ii", $userID, $rewardID);
		$result->execute();
		$result->store_result();
		$result->bind_result($temp);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return 0;
		}
		$result->close();
		return 1;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
	function get_Funding_Amount($db, $userID) {
		$query = "SELECT amountFunded FROM fundingAmount WHERE userID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $userID);
		$result->execute();
		$result->store_result();
		$result->bind_result($fundingAmount);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return 0;
		}
		$result->fetch();
		$result->close();
		return $fundingAmount;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
	function add_New_Code($db, $id, $myCode) {
		$query = "SELECT codeID, codeUsed FROM accessCode WHERE actualCode = ?";
		$result = $db->prepare($query);
		$result->bind_param("s", $myCode);
		$result->execute();
		$result->store_result();
		$result->bind_result($codeID, $codeUsed);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return -1;
		}
		$result->fetch();
		$result->close();
		if ($codeUsed != 0) {
			return -2;
		}
		$query = "INSERT INTO ptd2Emails (codeID, ptd2ID) VALUES (?, ?)";
		$result = $db->prepare($query);
		$result->bind_param("ii", $codeID, $id);
		$result->execute();
		$result->close();
		$codeUsed = 1;
		$query = "UPDATE accessCode SET codeUsed = ? WHERE codeID = ?";
		$result = $db->prepare($query);
		$result->bind_param("ii", $codeUsed, $codeID);
		$result->execute();
		$result->close();
		return 1;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function get_code_ID($db, $id) {
		$codeIDList = array();
		$query = "SELECT codeID FROM ptd2Emails WHERE ptd2ID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($codeID);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			array_push($codeIDList, -1);
			return $codeIDList;
		}
		for ($i=0; $i<$hmp; $i++) {
			$result->fetch();
			array_push($codeIDList, $codeID);
		}
		$result->close();
		return $codeIDList;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
 function get_UserID_From_CodeID($db, $codeID) {
	 $query = "SELECT userID FROM accessCode WHERE codeID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $codeID);
		$result->execute();
		$result->store_result();
		$result->bind_result($userID);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return -1;
		}
		$result->fetch();
		$result->close();
		return $userID;
 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
?>
</body>
</html>