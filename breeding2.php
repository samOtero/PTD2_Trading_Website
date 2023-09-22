<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Breeding Center";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'breedingList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
//echo "temporarily closed";
//exit;
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
					<div class="title"><p>Breeding Center - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") {//0 ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else {//0 ?>
					<div class="content">
						<p>Thanks for visiting the Breeding Center!</p>
						<p>Here you can leave two compatible Pokemon and they will leave you an egg! Visit the egg once a day, every day, until it hatches!</p>
                        <p>Having the parents be shiny/shadow and using certain items you can buy in the item store will increase the chance that the egg is shiny/shadow depending on the combination that you use.<br>
<strong>(Note: Items that increase shadow/shiny chances will be consumed in the breeding process.)</strong></p>
                        
                        <?php
						
						
						function get_Trainer_Pass($myTrainerID, $dbNew) {
							$returnValue = 0;
							$query = "SELECT trainerID FROM  trainerPass WHERE trainerID = ?";
							$result = $dbNew->prepare($query);
							$result->bind_param("i", $myTrainerID);
							$result->execute();
							$result->store_result();
							$result->bind_result($oldID);	
							$hmp = $result->affected_rows;
							$result->close();
							if ($hmp == 0) {
								//do nothing! this trainer is not trainerPass
							}else{
								$returnValue = 1;
							}
							return $returnValue;
						 }
						
						
						$db2 = connect_To_ptd2_Story_Database();
						$query = "select usedOn, howMany from breeding_stable WHERE trainerID = ?";
						$result = $db2->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($lastTimeUsed, $howManyVisits);
						$howManyDaysToWait = 3;
						if ($id === 1) {
							$howManyDaysToWait = 1;
						}else if (get_Trainer_Pass($id, $db2) == 1) {
							$howManyDaysToWait = 2; 
						}
						$moveOn = false;		
						if ($result->affected_rows) {
							$moveOn = true;
							$result->fetch();
							$rightNow = strtotime(date( 'Y-m-d'));
							$yourLastTimeUsed = strtotime($lastTimeUsed);
							$result->close();
							if ($yourLastTimeUsed < $rightNow) {
								$howManyVisits++;
								$newTime = date( 'Y-m-d');
								$query = "UPDATE breeding_stable SET howMany = ?, usedOn = ?  WHERE trainerID = ?";
								$result = $db2->prepare($query);
								$result->bind_param("isi",$howManyVisits, $newTime, $id);
								$result->execute();
								$result->close();
							}
						}else{
							$result->free_result();
							$result->close();
							echo '<p><strong>Stable Status:</strong> Empty. <a href="breeding_setup2.php?'.$urlValidation.'">Click here to start the breeding!</a></p></div></div>';							
						}
                        if ($moveOn == true) { //1
							$message = "<strong>Your egg hatched!!! Go pick up the parents and new baby in the Pokemon Center Home Page!<p>You also have earned (10) Daily Coins for Breeding your Pokemon! Horray!</strong><br>(You can use Daily Coins to adopt Pokemon and buy Items.)</p>";
							if ($howManyVisits < $howManyDaysToWait) { //2
								$message = '<strong>You need ('.($howManyDaysToWait-$howManyVisits).') more visits for this egg to hatch! Come back again tomorrow!</strong></p><p><img src="'.get_Graphic_Url().'/images/egg.png">';
							}//2
							echo '<p><strong>Stable Status:</strong> Full! Thanks for visiting your egg today! '.$message.'</p></div></div>';
							$gender = 2;
							$whichOne = 1;
							$query = "SELECT num, lvl, shiny, originalTrainer, nickname, myTag, m1, m2, m3, m4, gender, item, exp, happy FROM breeding_poke WHERE currentTrainer = ? AND ((gender = ? AND whichOne = -1) OR whichOne = ?)";
							$result = $db2->prepare($query);
							$result->bind_param("iii", $id, $gender, $whichOne);
							$result->execute();
							$result->store_result();
							$hmp = $result->affected_rows;
							$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeExp, $pokeHoF);
							if ($hmp == 0) {//2
								echo '<div class="block"><div class="content"><p>Error this should never happen. Contact Sam at sotero86@gmail.com</p></div></div>';
								exit;	
							}else{//2
								echo '<div class="block"><div class="content"><p>For the first Pokemon you picked:</p></div></div>';
								$result->fetch();
								$isHacked = "";
								if ($myTag == "h") {//3
									$isHacked = " (Hacked Version)";
								}//3
								$pokeNicknameNew = stripslashes($pokeNickname).$isHacked;
								$extra = '';
								pokeBox2($pokeNicknameNew, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeGender, $pokeItem, $pokeHoF);
							}//2
							$gender = 1;
							$whichOne = 2;
							$result->bind_param("iii", $id, $gender, $whichOne);
							$result->execute();
							$result->store_result();
							$hmp = $result->affected_rows;
							$result->bind_result($pokeNumM, $pokeLevelM,$pokeShinyM, $originalOwnerM, $pokeNicknameM, $myTagM, $move1M, $move2M, $move3M, $move4M, $pokeGenderM, $pokeItemM, $pokeExpM, $pokeHoFM);
							if ($hmp == 0) {//2
								echo '<div class="block"><div class="content"><p>Error this should never happen. Contact Sam at sotero86@gmail.com</p></div></div>';	
								exit;
							}else{//2
								echo '<div class="block"><div class="content"><p>For the second Pokemon you picked:</p></div></div>';
								$result->fetch();
								$isHacked = "";
								if ($myTagM == "h") {//3
									$isHacked = " (Hacked Version)";
								}//3
								$pokeNicknameNew = stripslashes($pokeNicknameM).$isHacked;
								$extra = '';
								pokeBox2($pokeNicknameNew, $pokeLevelM, $pokeShinyM, $move1M, $move2M, $move3M, $move4M, $pokeNumM, $extra, $pokeGenderM, $pokeItemM, $pokeHoFM);
							}//2
							$result->free_result();
							$result->close();
							if ($howManyVisits >= $howManyDaysToWait) {//2
								$eggInfo = get_Egg_Info($pokeNumM, $pokeShinyM, $pokeItemM, $pokeNum, $pokeShiny, $pokeItem, true, $move1M, $move2M, $move3M, $move4M, $move1, $move2, $move3, $move4);
								$eggTag = "n";
								$eggLevel = 1;
								$eggItem = 100;
								$eggShiny = $eggInfo[1];
								$eggNum = $eggInfo[0];
								$eggGender = $eggInfo[2];
								$eggMove1 = $eggInfo[3];
								$eggMove2 = $eggInfo[4];
								$eggMove3 = $eggInfo[5];
								$eggMove4 = $eggInfo[6];
								$eggNickname = "Eggy";
								if ($myTag == "h" || $myTagM == "h") {//3
									$eggTag = "h";
								}//3
								echo '<div class="block"><div class="content"><p>Your Egg hatched into:</p></div></div>';
								$isHacked = "";
								if ($eggTag == "h") {//3
									$isHacked = " (Hacked Version)";
								}//3
								$pokeNicknameNew = $eggNickname.$isHacked;
								$extra = '';
								pokeBox2($pokeNicknameNew, $eggLevel, $eggShiny, $eggMove1, $eggMove2, $eggMove3, $eggMove4, $eggNum, $extra, $eggGender, $eggItem);
								$db2->autocommit(false);
								$transactionFlag = true;
								$query = "DELETE FROM breeding_stable WHERE trainerID = ?";
								$result = $db2->prepare($query);
								$result->bind_param("i", $id);
								if (!$result->execute()) {//3
									$transactionFlag = false;
								}//3
								$result->close();
								$query = "DELETE FROM breeding_poke WHERE currentTrainer = ?";
								$result = $db2->prepare($query);
								$result->bind_param("i", $id);
								if (!$result->execute()) {//3
									$transactionFlag = false;
								}//3
								$result->close();
								//$db2->close();
								$db_New = connect_To_ptd2_Trading();
								$db_New->autocommit(false);
								$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
								$result = $db_New->prepare($query);
								if ($pokeItem == 15 || $pokeItem == 16) {//3
									$pokeItem = 0;
								}
								$tookItem = false;
								if ($pokeItem == 61) {//3
									if ($pokeNum == 315 || $pokeNum == 407) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 60) {//3
									if ($pokeNum == 185) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 59) {//3
									if ($pokeNum == 122) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 58) {//3
									if ($pokeNum == 242 || $pokeNum == 113) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 57) {//3
									if ($pokeNum == 143) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 56) {//3
									if ($pokeNum == 202) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 55) {//3
									if ($pokeNum == 183 || $pokeNum == 184) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 54) {//3
									if ($pokeNum == 226) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}else if ($pokeItem == 79) {//3
									if ($pokeNum == 358) {//4
										$pokeItem = 0;
										$tookItem = true;
									}//4
								}//3
								$result->bind_param("iiiisiiiiiiisii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $move1, $move2, $move3, $move4, $pokeItem, $originalOwner, $id, $myTag, $pokeGender, $pokeHoF);
								if (!$result->execute()) {//3
									$transactionFlag = false;
								}//3
								if ($pokeItemM == 15 || $pokeItemM == 16) {//3
									$pokeItemM = 0;
								}//3
								if ($tookItem == false) {//3
									if ($pokeItemM == 61) {//4
										if ($pokeNumM == 315 || $pokeNumM == 407) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 60) {//4
										if ($pokeNumM == 185) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 59) {//4
										if ($pokeNumM == 122) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 58) {//4
										if ($pokeNumM == 242 || $pokeNumM == 113) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 57) {//4
										if ($pokeNumM == 143) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 56) {//4
										if ($pokeNumM == 202) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 55) {//4
										if ($pokeNumM == 184 || $pokeNumM == 183) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 54) {//4
										if ($pokeNumM == 226) {//5
											$pokeItemM = 0;
										}//5
									}else if ($pokeItemM == 79) {//4
										if ($pokeNumM == 358) {//5
											$pokeItemM = 0;
										}//5
									}//4
								}//3
								$result->bind_param("iiiisiiiiiiisii", $pokeNumM, $pokeLevelM, $pokeExpM, $pokeShinyM, $pokeNicknameM, $move1M, $move2M, $move3M, $move4M, $pokeItemM, $originalOwnerM, $id, $myTagM, $pokeGenderM, $pokeHoFM);
								if (!$result->execute()) {//3
									$transactionFlag = false;
								}//3
								$eggExp = 0;
								$eggOriginalOwner = -$id;
								$eggHoF = 0;
								$result->bind_param("iiiisiiiiiiisii", $eggNum, $eggLevel, $eggExp, $eggShiny, $eggNickname, $eggMove1, $eggMove2, $eggMove3, $eggMove4, $eggItem, $eggOriginalOwner, $id, $eggTag, $eggGender, $eggHoF);
								if (!$result->execute()) {//3
									$transactionFlag = false;
								}//3
								$result->close();
								//$db_New->close();
								///add daily coins
								$query = "select howMany from dailyCoins WHERE trainerID = ?";
								$result = $db2->prepare($query);
								$result->bind_param("i", $id);
								$result->execute();
								$result->store_result();
								$result->bind_result($howManyDailyCoins);			
								if ($result->affected_rows) {//3
									$result->fetch();
									$result->close();
									$howManyDailyCoins += 10;
									$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
									$result = $db2->prepare($query);
									$result->bind_param("ii", $howManyDailyCoins, $id);
									if (!$result->execute()) {//4
										$transactionFlag = false;
									}//4
									$result->close();
								}else{//3
									$howManyDailyCoins = 10;
									$result->close();
									$query = "INSERT INTO dailyCoins (trainerID, howMany) VALUES (?,?)";
									$result = $db2->prepare($query);
									$result->bind_param("ii", $id, $howManyDailyCoins);
									if (!$result->execute()) {//4
										$transactionFlag = false;
									}//4
									$result->close();
								}//3
								if ($transactionFlag == true) {
									$db2->commit();
									$db_New->commit();
								}else{
									$db2->rollback();
									$db_New->rollback();
									echo '<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>';
								}
								$db2->autocommit(true);
								$db_New->autocommit(true);
								$db_New->close();
							}//2
						}//1
						$db2->close();
					}//0?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>