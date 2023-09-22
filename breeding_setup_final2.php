<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Breeding Center Setup";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'breedingList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$reason = "good";//get_Current_Save_Status($id, $currentSave);
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
					<div class="title"><p>Breeding Center Setup - <a href="breeding2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Thanks for visiting the Breeding Center!</p>
						<p>Here you can leave two compatible Pokemon and they will leave you an egg! Visit the egg once a day, every day, until it hatches!</p>
                        <p>Having the parents be shiny/shadow and using certain items you can buy in the item store will increase the chance that the egg is shiny/shadow depending on the combination that you use.<br>
<strong>(Note: Items that increase shadow/shiny chances will be consumed in the breeding process.)</strong></p>
                        
                        <?php
						//$db = connect_To_Database();
						//$updateResult = update_Current_Save($db, $id, $currentSave);
						//$reason = $updateResult[0];
						//$currentSave = $updateResult[1];
						//$db->close();
						//$reason = "error";
						if ($reason == "error") {
							?>
							 <div class="content">
							<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
							</div>
						 </div>
                         
						<?php
							exit;
						}
						//$_SESSION['currentSave2'] = $currentSave;
						$db2 = connect_To_ptd2_Story_Database();
						$query = "select usedOn, howMany from breeding_stable WHERE trainerID = ?";
						$result = $db2->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($lastTimeUsed, $howManyVisits);
						$moveOn = false;		
						if ($result->affected_rows) {
							echo '<p><strong>Stable Status:</strong> Full! You cannot start breeding a new egg at this time. Go back.</p>';
							$moveOn = true;
							$result->fetch();
						}
						$result->free_result();
						$result->close();
						$db2->close();
                        if ($moveOn == false) {
							$femaleID = $_REQUEST['femaleID'];
							$dbActual = get_PTD2_Pokemon_Database($whichDB);
							$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, exp, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND ((gender = 2 ".get_Legal_Query().") OR num = 132) AND uniqueID = ?";
							$result = $dbActual->prepare($query);
							$result->bind_param("iii", $id, $whichProfile, $femaleID);
							$result->execute();
							$result->store_result();
							$hmp = $result->affected_rows;
							$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeExp, $pokeHoF);
							if ($hmp == 0) {
								echo '<p>Error. Your first Pokemon is not valid. Go back and try again.</p>';	
							}else{
								$result->fetch();
								echo '<p>For the first Pokemon you picked:</p></div></div>';
								$isHacked = "";
								if ($myTag == "h") {
									$isHacked = " (Hacked Version)";
								}
								$pokeNicknameNew = stripslashes($pokeNickname).$isHacked;
								$extra = '';
								pokeBox2($pokeNicknameNew, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeGender, $pokeItem, $pokeHoF);
								$maleID = $_REQUEST['pokeID'];
								$genderQuery = "gender = 1";
								if ($pokeNum == 132) {
									$genderQuery = "1 = 1";
								}
								$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, exp, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND uniqueID = ? AND ".$genderQuery." ".get_Egg_Group_Query($pokeNum)." ORDER BY num, lvl";
								$result = $dbActual->prepare($query);
								$result->bind_param("iii", $id, $whichProfile, $maleID);
								$result->execute();
								$result->store_result();
								$hmp = $result->affected_rows;
								$result->bind_result($pokeNumM, $pokeLevelM,$pokeShinyM, $originalOwnerM, $pokeIDM, $pokeNicknameM, $myTagM, $move1M, $move2M, $move3M, $move4M, $pokeGenderM, $pokeItemM, $pokeExpM, $pokeHoFM);
								if ($hmp == 0) {
									echo '<div class="block"><div class="content"><p>Error. Your second Pokemon is not valid. Go back and try again.</p></div></div>';	
								}else{
									$result->fetch();
									echo '<div class="block"><div class="content"><p>For the second Pokemon you picked:</p></div></div>';
									$isHacked = "";
									if ($myTagM == "h") {
										$isHacked = " (Hacked Version)";
									}
									$pokeNicknameNew = stripslashes($pokeNicknameM).$isHacked;
									$extra = '';
									pokeBox2($pokeNicknameNew, $pokeLevelM, $pokeShinyM, $move1M, $move2M, $move3M, $move4M, $pokeNumM, $extra, $pokeGenderM, $pokeItemM, $pokeHoFM);
									$db2 = connect_To_ptd2_Story_Database();
									$db2->autocommit(false);
									$dbActual->autocommit(false);
									$transactionFlag = true;
									$whichOne = 1;
									$whichOneM = 2;
									$query = "INSERT INTO breeding_poke (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender, whichOne, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
									$result = $db2->prepare($query);
									$result->bind_param("iiiisiiiiiiisiii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $move1, $move2, $move3, $move4, $pokeItem, $originalOwner, $id, $myTag, $pokeGender, $whichOne, $pokeHoF);
									if (!$result->execute()) {
										$transactionFlag = false;
									}
									$result->bind_param("iiiisiiiiiiisiii", $pokeNumM, $pokeLevelM, $pokeExpM, $pokeShinyM, $pokeNicknameM, $move1M, $move2M, $move3M, $move4M, $pokeItemM, $originalOwnerM, $id, $myTagM, $pokeGenderM, $whichOneM, $pokeHoFM);
									if (!$result->execute()) {
										$transactionFlag = false;
									}
									$result->close();
									$today = date( 'Y-m-d');
									$howMany = 1;
									$query = "INSERT INTO breeding_stable (trainerID, usedOn, howMany) VALUES (?, ?, ?)";
									$result = $db2->prepare($query);
									$result->bind_param("isi", $id, $today, $howMany);
									if (!$result->execute()) {
										$transactionFlag = false;
									}
									$result->close();
									$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ? OR uniqueID = ?";
									$result = $dbActual->prepare($query);
									$result->bind_param("ii", $maleID, $femaleID);
									if (!$result->execute()) {
										$transactionFlag = false;
									}
									if ($transactionFlag == true) {
										$db2->commit();
										$dbActual->commit();
										echo '<div class="block"><div class="content"><p><strong>Congratulations!</strong> You have bred an egg! Visit it more times for it to hatch!</p></div></div>';
									}else{
										$db2->rollback();
										$dbActual->rollback();
										echo '<div class="block"><div class="content"><p>Error in the database. <a href="trading.php">Click here to go back.</a></p></div></div>';
									}
									$db2->autocommit(true);
									$dbActual->autocommit(true);
									$db2->close();
								}
							}
							$dbActual->close();
						}
                        ?>
                        
					</div>
				</div>
                <?php 
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