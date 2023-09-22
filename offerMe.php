<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Request Trade";
	$pageMenuset = "extended";
	require 'trade_To_Pickup_By_ID.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "go";
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
                <?php
					$backURL = "searchTrades.php";
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
	function do_Stuff() {
		global $id, $db, $urlValidation, $whichProfile, $currentSave;
		$requestIDList = $_REQUEST['offer'];
		$tradeID = $_REQUEST['tradeID'];
		if (empty($requestIDList)) {
			echo '<div class="content"><p>Error, press the back link to fix this error.</p></div></div>';
			return;
		}
		$requestCount = count($requestIDList);
		if ($requestCount > 6) {
			echo '<div class="content"><p>You cannot offer more than 6 pokemon.</p></div></div>';
			return;
		}
		for ($i=0; $i<$requestCount; $i++) {
			$requestID = $requestIDList[$i];
			if ($requestID == $tradeID) {
				echo '<div class="content"><p>You cannot trade a pokemon for itself.</p></div></div>';
				return;
			}
			for ($z=0; $z<$requestCount; $z++) {
				$otherRequest = $requestIDList[$z];
				if ($z != $i) {
					if ($requestID == $otherRequest) {
						echo '<div class="content"><p>Suspected of Cheating.</p></div></div>';
						return;
					}
				}
			}
		}
		$db_New = connect_To_Database_New();
		for ($i=0; $i<$requestCount; $i++) {
			$requestID = $requestIDList[$i];
			$query = "SELECT currentTrainer FROM trainer_trades WHERE uniqueID = ? AND currentTrainer = ?";
			$result = $db_New->prepare($query);
			$result->bind_param("si", $requestID, $id);
		
		$result->execute();
		$result->store_result();
		if (!$result->affected_rows) {
			$result->close();
			echo '<div class="content"><p>You cannot offer a pokemon that does not belong to you.</p></div></div>';
			return;
		}
		$result->close;
		$query = "SELECT tradePokeID FROM trade_request WHERE tradePokeID = ? AND requestPokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ss", $tradeID, $requestID);
		$result->execute();
		$result->store_result();
		if ($result->affected_rows) {
			$result->close();
			echo '<div class="content"><p>You have already offered this pokemon for this trade.</p></div></div>';
			return;
		}
		$result->close();
		}
		$wantsList = array();
		$insertIntoRequest = true;
		$query = "SELECT num, level, levelComparison, shiny FROM trade_wants WHERE tradePokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmw = $result->affected_rows;
		$result->bind_result($wantNum, $wantLevel, $wantLevelComparison, $wantShiny);
		if ($hmw > 0) {
			for ($i=1; $i<=$hmw; $i++) {
				$result->fetch();
				array_push($wantsList, array($wantNum, $wantLevel, $wantLevelComparison, $wantShiny));
			}
			$result->close();
			if ($hmw == $requestCount) {
				for ($i=0; $i<$requestCount; $i++) {
					$requestID = $requestIDList[$i];
					$query = "SELECT num, lvl, shiny, myTag FROM trainer_trades WHERE uniqueID = ?";
					$result = $db_New->prepare($query);
					$result->bind_param("s", $requestID);
					$result->execute();
					$result->store_result();
					$hmr = $result->affected_rows;
					$result->bind_result($requestNum, $requestLvl, $requestShiny, $requestTag);
					$result->fetch();
					$result->close();
					if ($hmr == 0) {
						break;
					}
					if ($requestTag == "h") {
						break;
					}
					for ($b=0; $b<count($wantsList); $b++) {
						$currentWantPoke = $wantsList[$b];
						if ($currentWantPoke[0] != 0 && $requestNum != $currentWantPoke[0]){
							continue;
						}
						if ($requestShiny == 0 && ($currentWantPoke[3] == 1 || $currentWantPoke[3] == 2)) {
							continue;
						}else if ($requestShiny == 1 && ($currentWantPoke[3] == 0 || $currentWantPoke[3] == 2)) {
							continue;
						}else if ($requestShiny == 2 && ($currentWantPoke[3] == 0 || $currentWantPoke[3] == 1)) {
							continue;
						}
						if ($currentWantPoke[1] != 0) {
							if ($currentWantPoke[2] == 5) {
								if ($requestLvl <= $currentWantPoke[1]) {
									continue;
								}
							}else if ($currentWantPoke[2] == 4) {
								if ($requestLvl >= $currentWantPoke[1]) {
									continue;
								}
							}else if ($currentWantPoke[2] == 3) {
								if ($requestLvl < $currentWantPoke[1]) {
									continue;
								}
							}else if ($currentWantPoke[2] == 2) {
								if ($requestLvl > $currentWantPoke[1]) {
									continue;
								}
							}else if ($currentWantPoke[2] == 1) {
								if ($requestLvl != $currentWantPoke[1]) {
									continue;
								}
							}
						}
						array_splice($wantsList, $b, 1);
						break;
					}
				}
			}
			if (count($wantsList) == 0) {
				echo '<div class="content"><p>You matched up all the request! Trade completed! You can pick up your pokemon by going all the way back to the main menu of the Pokemon Center.</p></div></div>';
				$query = "SELECT currentTrainer FROM trainer_trades WHERE uniqueID = ?";
				$result = $db_New->prepare($query);
				$result->bind_param("s", $tradeID);
				$result->execute();
				$result->store_result();
				$result->bind_result($otherTrainer);
				$result->fetch();
				$result->close();
				trade_To_Pickup($db_New, $tradeID, $id);
				$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
				$result2 = $db_New->prepare($query2);
				$result2->bind_param("ss", $tradeID, $tradeID);
				$result2->execute();
				$result2->close();
				$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
				$result2 = $db_New->prepare($query2);
				$result2->bind_param("s", $tradeID);
				$result2->execute();
				$result2->close();
				for ($i=0; $i<$requestCount; $i++) {
					$requestID = $requestIDList[$i];
					trade_To_Pickup($db_New, $requestID, $otherTrainer);
					$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
					$result2 = $db_New->prepare($query2);
					$result2->bind_param("ss", $requestID, $requestID);
					$result2->execute();
					$result2->close();
					$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
					$result2 = $db_New->prepare($query2);
					$result2->bind_param("s", $requestID);
					$result2->execute();
					$result2->close();
				}
				$insertIntoRequest = false;
			}
		}else{
			$result->close();	
		}
		if ($insertIntoRequest == true) {
			$newOfferID = uniqid(true);
			$query = "INSERT INTO trade_request (tradePokeID, requestPokeID, offerID) VALUES (?,?, ?)";
			$result = $db_New->prepare($query);
			for ($i=0; $i<$requestCount; $i++) {
				$requestID = $requestIDList[$i];
				$result->bind_param("sss", $tradeID, $requestID, $newOfferID);
				$result->execute();
			}
			echo '<div class="content"><p>Your offer has been sent. Go to the Your Trade Request to see if this offer has been accepted.</p></div></div>';
		}
		
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include 'template/footer.php';
?>
</body>
</html>