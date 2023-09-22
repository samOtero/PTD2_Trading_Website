<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Request Trade";
	$pageMenuset = "extended";
	require 'trade_To_Pickup_By_ID.php';
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
                <?php
					$backURL = "searchTrades2.php";
				?>
					<div class="title"><p>Request Trade - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
					do_Stuff();
				}
	 			?>
			</td>
		</tr>
	</table>
</div>
<?php
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function do_Stuff() {//1
		global $id, $urlValidation, $whichProfile, $currentSave;
		$requestIDList = $_REQUEST['offer'];
		$tradeID = $_REQUEST['tradeID'];
		if (empty($requestIDList)) {//2
			echo '<div class="content"><p>Error, press the back link to fix this error.</p></div></div>';
			return;
		}//2
		$requestCount = count($requestIDList);
		$maxAmount = 10;
		if ($requestCount > $maxAmount) {//2
			echo '<div class="content"><p>You cannot offer more than '.$maxAmount.' pokemon.</p></div></div>';
			return;
		}//2
		for ($i=0; $i<$requestCount; $i++) {//2
			$requestID = $requestIDList[$i];
			if ($requestID == $tradeID) {//3
				echo '<div class="content"><p>You cannot trade a pokemon for itself.</p></div></div>';
				return;
			}//3
			for ($z=0; $z<$requestCount; $z++) {//3
				$otherRequest = $requestIDList[$z];
				if ($z != $i) {//4
					if ($requestID == $otherRequest) {//5
						echo '<div class="content"><p>Suspected of Cheating.</p></div></div>';
						return;
					}//5
				}//4
			}//3
		}//2
		$have588 = false;
		$have616 = false;
		$db_New = connect_To_ptd2_Trading();
		for ($i=0; $i<$requestCount; $i++) {//2
			$requestID = $requestIDList[$i];
			$query = "SELECT currentTrainer, num FROM trainer_trades WHERE uniqueID = ? AND currentTrainer = ?";
			$result = $db_New->prepare($query);
			$result->bind_param("si", $requestID, $id);
			$result->execute();
			$result->store_result();
			$result->bind_result($tempCurrentT, $tempNum);
			if (!$result->affected_rows) {//3
				$result->free_result();
				$result->close();
				$db_New->close();
				echo '<div class="content"><p>You cannot offer a pokemon that does not belong to you.</p></div></div>';
				return;
			}//3
			$result->fetch();
			$result->free_result();
			$result->close;
			if ($tempNum == 588) {//3
				$have588 = true;
			}else if ($tempNum == 616) {//3
				$have616 = true;
			}//3
			$query = "SELECT tradePokeID FROM trade_request WHERE tradePokeID = ? AND requestPokeID = ?";
			$result = $db_New->prepare($query);
			$result->bind_param("ss", $tradeID, $requestID);
			$result->execute();
			$result->store_result();
			if ($result->affected_rows) {//3
				$result->free_result();
				$result->close();
				$db_New->close();
				echo '<div class="content"><p>You have already offered this pokemon for this trade.</p></div></div>';
				return;
			}//3
			$result->free_result();
			$result->close();
		}//2
		$totalPossibleRequest = 3;
		$insertIntoRequest = true;
		for ($z=1; $z<=$totalPossibleRequest; $z++) {//2
			$wantsList = array();
			$query = "SELECT num, level, levelComparison, shiny, gender FROM trade_wants WHERE tradePokeID = ? AND whichRequest = ?";
			$result = $db_New->prepare($query);
			$result->bind_param("si", $tradeID , $z);
			$result->execute();
			$result->store_result();
			$hmw = $result->affected_rows;
			$result->bind_result($wantNum, $wantLevel, $wantLevelComparison, $wantShiny, $wantGender);
			if ($hmw > 0) {//3
				for ($i=1; $i<=$hmw; $i++) {//4
					$result->fetch();
					array_push($wantsList, array($wantNum, $wantLevel, $wantLevelComparison, $wantShiny, $wantGender));
				}//4
				$result->free_result();
				$result->close();
				if ($hmw <= $requestCount) {//4
					for ($i=0; $i<$requestCount; $i++) {//5
						$requestID = $requestIDList[$i];
						$query = "SELECT num, lvl, shiny, myTag, gender FROM trainer_trades WHERE uniqueID = ?";
						$result = $db_New->prepare($query);
						$result->bind_param("s", $requestID);
						$result->execute();
						$result->store_result();
						$hmr = $result->affected_rows;
						$result->bind_result($requestNum, $requestLvl, $requestShiny, $requestTag, $requestGender);
						$result->fetch();
						$result->free_result();
						$result->close();
						if ($hmr == 0) {//6
							break;
						}//6
						if ($requestTag == "h") {//6
							break;
						}//6
						for ($b=0; $b<count($wantsList); $b++) {//6
							$currentWantPoke = $wantsList[$b];
							if ($currentWantPoke[0] != -2 && $requestNum != $currentWantPoke[0]){//7
								continue;
							}//7
							if ($currentWantPoke[3] != -1 && $requestShiny != $currentWantPoke[3]) {//7
								continue;
							}//7
							if ($currentWantPoke[4] != -1 && $requestGender != $currentWantPoke[4]) {//7
								continue;
							}//7
							if ($currentWantPoke[1] != 0) {//7
								if ($currentWantPoke[2] == 5) {//8
									if ($requestLvl <= $currentWantPoke[1]) {//9
										continue;
									}//9
								}else if ($currentWantPoke[2] == 4) {//8
									if ($requestLvl >= $currentWantPoke[1]) {//9
										continue;
									}//9
								}else if ($currentWantPoke[2] == 3) {//8
									if ($requestLvl < $currentWantPoke[1]) {//9
										continue;
									}//9
								}else if ($currentWantPoke[2] == 2) {//8
									if ($requestLvl > $currentWantPoke[1]) {//9
										continue;
									}//9
								}else if ($currentWantPoke[2] == 1) {//8
									if ($requestLvl != $currentWantPoke[1]) {//9
										continue;
									}//9
								}//8
							}//7
							array_splice($wantsList, $b, 1);
							break;
						}//6
					}//5
				}//4
				if (count($wantsList) == 0) {//4
					$query = "SELECT currentTrainer, num FROM trainer_trades WHERE uniqueID = ?";
					$result = $db_New->prepare($query);
					$result->bind_param("s", $tradeID);
					$result->execute();
					$result->store_result();
					$result->bind_result($otherTrainer, $otherNum);
					$result->fetch();
					$doShelmet = false;
					if ($otherNum == 588) {//5
						if ($have616 == true) {//6
							$doShelmet = true;
						}//6
					}else if ($otherNum == 616) {//5
						if ($have588 == true) {//6
							$doShelmet = true;
						}//6
					}//5
					$db_New->autocommit(false);
					$transactionFlag = true;
					$transactionFlag = trade_To_Pickup2($db_New, $tradeID, $id, $doShelmet, $transactionFlag);
					$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
					$result2 = $db_New->prepare($query2);
					$result2->bind_param("ss", $tradeID, $tradeID);
					if (!$result2->execute()) {//5
						$transactionFlag = false;
					}//5
					$result2->close();
					$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
					$result2 = $db_New->prepare($query2);
					$result2->bind_param("s", $tradeID);
					if (!$result2->execute()) {//5
						$transactionFlag = false;
					}//5
					$result2->close();
					for ($i=0; $i<$requestCount; $i++) {//5
						$requestID = $requestIDList[$i];
						$transactionFlag = trade_To_Pickup2($db_New, $requestID, $otherTrainer, $doShelmet, $transactionFlag);
						$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
						$result2 = $db_New->prepare($query2);
						$result2->bind_param("ss", $requestID, $requestID);
						if (!$result2->execute()) {//6
							$transactionFlag = false;
						}//6
						$result2->close();
						$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
						$result2 = $db_New->prepare($query2);
						$result2->bind_param("s", $requestID);
						if (!$result2->execute()) {//6
							$transactionFlag = false;
						}//6
						$result2->close();
					}//5
					$insertIntoRequest = false;
					$result->free_result();
					$result->close();
					$transactionFlag = trade_Successful($db_New, $id, $otherTrainer, $transactionFlag);
					if ($transactionFlag == true) {//5
						$db_New->commit();
						echo '<div class="content"><p>You matched up all the request! Trade completed! You can pick up your pokemon by pressing Home and looking at the pickup area in the bottom of the page.</p></div></div>';
					}else{//5
						$db_New->rollback();
						echo '<div class="content"><p>An Error has occurred. Please Try Again.</p></div></div>';
					}//5
					$db_New->autocommit(true);
					break;
				}//4
			}else{//3
				$result->free_result();
				$result->close();	
			}//3
		}//2
		if ($insertIntoRequest == true) {//2
			$newOfferID = uniqid(true);
			$db_New->autocommit(false);
			$transactionFlag = true;
			$query = "INSERT INTO trade_request (tradePokeID, requestPokeID, offerID) VALUES (?,?, ?)";
			$result = $db_New->prepare($query);
			for ($i=0; $i<$requestCount; $i++) {//3
				$requestID = $requestIDList[$i];
				$result->bind_param("sss", $tradeID, $requestID, $newOfferID);
				if (!$result->execute()) {
					$transactionFlag = false;
				}
			}//3
			if ($transactionFlag == true) {
				$db_New->commit();
				echo '<div class="content"><p>Your offer has been sent. You must now wait until the other trainer decides to accept or deny your offer. You can remove this offer by going to the Your Trade Request Page.</p></div></div>';
			}else{
				$db_New->rollback();
				echo '<div class="content"><p>An Error has occurred. Please Try Again.</p></div></div>';
			}
		}//2
		$db_New->close();
		
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include 'template/footer.php';
?>
</body>
</html>