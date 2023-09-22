<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Hall Of Fame - Submit";
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
				if ($reason != "savedOutside") {
					$totalRegularRegistered = 0;
					$totalShinyRegistered = 0;
					$totalShadowRegistered = 0;
					$db2 = connect_To_ptd2_Trading();
					$action = $_REQUEST['action'];
					$submitValue = false;
					if ($action == "submit") {
						$query = "select pokeTotal, ladderType from hof_ladder WHERE trainerID = ?";
						$result = $db2->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($totalPoke, $ladderType);			
						if ($result->affected_rows) {
							for ($i=0; $i<$result->affected_rows; $i++) {
								$result->fetch();
								if ($ladderType == 0) {
									$totalRegularRegistered = $totalPoke;
								}else if ($ladderType == 1) {
									$totalShinyRegistered = $totalPoke;
								}else if ($ladderType == 2) {
									$totalShadowRegistered = $totalPoke;
								}
							}
						}
						$submitValue = do_Submit($db2, $id, $whichDB, $whichProfile, $totalRegularRegistered, $totalShinyRegistered, $totalShadowRegistered);
					}
					$query = "select pokeTotal, ladderType from hof_ladder WHERE trainerID = ?";
					$result = $db2->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$result->bind_result($totalPoke, $ladderType);			
					if ($result->affected_rows) {
						for ($i=0; $i<$result->affected_rows; $i++) {
							$result->fetch();
							if ($ladderType == 0) {
								$totalRegularRegistered = $totalPoke;
							}else if ($ladderType == 1) {
								$totalShinyRegistered = $totalPoke;
							}else if ($ladderType == 2) {
								$totalShadowRegistered = $totalPoke;
							}
						}
					}else{
						$queryNew = "INSERT INTO hof_ladder (trainerID, ladderType, pokeTotal) VALUES (?,?,?)";
						$resultNew = $db2->prepare($queryNew);
						$ladderType = 0;
						$totals = 0;
						$resultNew->bind_param("iii", $id, $ladderType, $totals);
						$resultNew->execute();
						$ladderType = 1;
						$totals = 0;
						$resultNew->bind_param("iii", $id, $ladderType, $totals);
						$resultNew->execute();	
						$ladderType = 2;
						$totals = 0;
						$resultNew->bind_param("iii", $id, $ladderType, $totals);
						$resultNew->execute();						
					}
					$result->free_result();
					$result->close();
				}
				function do_Submit($dbHof, $id, $whichDB, $whichProfile, $totalRegularRegistered, $totalShinyRegistered, $totalShadowRegistered) {
					$extraQuery = '';
					$returnValue = false;
					$queryNew = "SELECT pokeNum, pokeType FROM hofRecords WHERE trainerID = ?";
					$result2 = $dbHof->prepare($queryNew);
					$result2->bind_param("i", $id);
					$result2->execute();
					$result2->store_result();
					$result2->bind_result($submitPoke, $submitPokeType);			
					if ($result2->affected_rows) {
						$extraQuery =' AND (';
						for ($i=0; $i<$result2->affected_rows; $i++) {
							$result2->fetch();
							if ($i != 0) {
								$extraQuery .= ' AND (';
							}
							$extraQuery .= "num != $submitPoke OR shiny != $submitPokeType)";
						}
					}
					$result2->free_result();
					$result2->close();
					$dbActual = get_PTD2_Pokemon_Database($whichDB);
					$query = "SELECT num, shiny, uniqueID FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND happy = 0".$extraQuery." GROUP BY num, shiny";
					$result = $dbActual->prepare($query);
					$result->bind_param("ii", $id, $whichProfile);
					$result->execute();
					$result->store_result();
					$hmp = $result->affected_rows;
					$result->bind_result($pokeNum,$pokeShiny, $pokeID);
					if ($hmp == 0) {
						$returnValue = false;
					}else{
						$shinyCount = 0;
						$shadowCount = 0;
						$regularCount = 0;
						$transactionFlag = true;
						$dbHof->autocommit(false);
						$dbActual->autocommit(false);
						$returnValue = true;
						$queryNew = "INSERT INTO hofRecords (trainerID, pokeNum, pokeType) VALUES (?,?,?)";
						$resultNew = $dbHof->prepare($queryNew);
						$queryUpdate = "UPDATE trainer_pokemons SET happy = 1 WHERE uniqueID = ?";
						$resultUpdate = $dbActual->prepare($queryUpdate);
						$queryTotal = "UPDATE hof_ladder SET pokeTotal = ?, lastTime = ? WHERE trainerID = ? AND ladderType = ?";
						$resultTotal = $dbHof->prepare($queryTotal); 
						for ($i=1; $i<=$hmp; $i++) {
							$result->fetch();
							if ($pokeShiny == 0) {
								$regularCount++;
							}else if ($pokeShiny == 2) {
								$shadowCount++;
							}else{
								$shinyCount++;
							}
							$resultNew->bind_param("iii", $id, $pokeNum, $pokeShiny);
							if (!$resultNew->execute()) {
								$transactionFlag = false;
								break;
							}
							$resultUpdate->bind_param("i", $pokeID);
							if (!$resultUpdate->execute() || $resultUpdate->sqlstate!="00000") {
								$transactionFlag = false;
								break;
							}
						}
						$newTime = date( 'Y-m-d h:i:s');
						if ($regularCount > 0) {
							$newTotal = $totalRegularRegistered + $regularCount;
							$whichLadder = 0;
							$resultTotal->bind_param("isii", $newTotal, $newTime, $id, $whichLadder);
							if (!$resultTotal->execute() || $resultTotal->sqlstate!="00000") {
								$transactionFlag = false;
							}
						}
						if ($shinyCount > 0) {
							$newTotal = $totalShinyRegistered + $shinyCount;
							$whichLadder = 1;
							$resultTotal->bind_param("isii", $newTotal, $newTime, $id, $whichLadder);
							if (!$resultTotal->execute() || $resultTotal->sqlstate!="00000") {
								$transactionFlag = false;
							}
						}
						if ($shadowCount > 0) {
							$newTotal = $totalShadowRegistered + $shadowCount;
							$whichLadder = 2;
							$resultTotal->bind_param("isii", $newTotal, $newTime, $id, $whichLadder);
							if (!$resultTotal->execute() || $resultTotal->sqlstate!="00000") {
								$transactionFlag = false;
							}
						}
						if ($transactionFlag == true) {
							$dbHof->commit();
							$dbActual->commit();
						}else{
							$retunValue = false;
							$dbHof->rollback();
							$dbActual->rollback();
						}
						$resultNew->close();
						$dbHof->autocommit(true);
						$dbActual->autocommit(true);
					}
					$result->free_result();
					$result->close();
					$dbActual->close();
					return $returnValue;
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Hall Of Fame - Submit - <a href="hof2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Here you will see a list of Pokémon in your profile that can be submitted to the Hall Of Fame. <br><b>Note: Pokémon that have already been submitted cannot be submitted again, also you can only submit one of each pokemon for each category.</b></p>
						<p>You have submitted (<?php echo $totalRegularRegistered ?>) Regular Pokémon, (<?php echo $totalShinyRegistered ?>) Shiny Pokémon and (<?php echo $totalShadowRegistered ?>) Shadow Pokémon to the Hall of Fame, so far.</p>
					</div>
				</div>
                <?php 
				if ($action == "submit") {
					if ($submitValue == true) {
						echo '<div class = "block"><div class="content"><p><b>Your Pokémon were successfully submitted to the Hall Of Fame.</b></p></div></div>';
					}else{
						echo '<div class = "block"><div class="content"><p><b>No Valid Pokémon to submit in this profile.</b></p></div></div>';
					}
				}
				$extraQuery = '';
				$query2 = "SELECT pokeNum, pokeType FROM hofRecords WHERE trainerID = ?";
				$result2 = $db2->prepare($query2);
				$result2->bind_param("i", $id);
				$result2->execute();
				$result2->store_result();
				$result2->bind_result($submitPoke, $submitPokeType);			
				if ($result2->affected_rows) {
					$extraQuery =' AND (';
					for ($i=0; $i<$result2->affected_rows; $i++) {
						$result2->fetch();
						if ($i != 0) {
							$extraQuery .= ' AND (';
						}
						$extraQuery .= "num != $submitPoke OR shiny != $submitPokeType)";
					}
				}
				$result2->free_result();
				$result2->close();
				$db2->close();
$dbActual = get_PTD2_Pokemon_Database($whichDB);
	$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND happy = 0".$extraQuery." GROUP BY num, shiny ORDER BY num, lvl";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $whichProfile);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeHoF);
	if ($hmp == 0) {
		echo '<div class = "block"><div class="content"><p>You have no valid Pokémon to submit in this profile.</p></div></div>';
	}else{
		echo '<div class = "block"><div class="content"><p><a href="hof_submit2.php?'.$urlValidation.'&action=submit">Click here to submit the Pokémon below to the Hall Of Fame</a></p></div></div>';
	for ($i=1; $i<=$hmp; $i++) {
		$result->fetch();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " (Hacked Version)";
		}
		$changeNick = ' | <a href="changePokeNickname2.php?'.$urlValidation.'&pokeID='.$pokeID.'">Change Nickname</a>';
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, '&nbsp;', $pokeGender, $pokeItem, $pokeHoF);
	}
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