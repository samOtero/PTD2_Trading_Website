<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Accept Trade Offer";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	require 'trade_To_Pickup_By_ID.php';
	//$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	$whichDB = $profileInfo[5];
	$urlValidation = "whichProfile=".$whichProfile;
	$offerID = $_REQUEST['offerID'];
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
					<div class="title"><p>Accept Trade Offer - <a href="yourTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
	function do_Stuff() {
		global $id, $urlValidation, $offerID, $whichProfile, $currentSave, $whichDB;
		//$db = connect_To_Database();
		//$updateResult = update_Current_Save($db, $id, $currentSave);
		//$reason = $updateResult[0];
		//$currentSave = $updateResult[1];
		//$db->close();
		//if ($reason == "error") {
			//echo '<div class="content"><p>Error in the database. <a href="trading.php">Click here to go back.</a></p></div></div>';
			//return;
		//}
		//if (empty($offerID)) {
			//echo '<div class="content"><p>Error, press the back link to fix this error.</p></div></div>';
			//return;
		//}
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT requestPokeID, tradePokeID FROM trade_request WHERE offerID = ? ";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $offerID);
		$result->execute();
		$result->store_result();
		$result->bind_result($requestPokeID, $tradePokeID);
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo '<div class="content"><p>This offer is no longer available.</p></div></div>';
			return;
		}
		$firstCheck = false;
		//$dbActual = get_PTD2_Pokemon_Database($whichDB);
		$doChangeTo = false;
		$db_New->autocommit(false);
		$transactionFlag = true;
		for ($i=1; $i<=$hmp; $i++) {//2
			$result->fetch();
			if ($firstCheck == false) {//3
				$firstCheck = true;
				$query2 = "SELECT num FROM trainer_trades WHERE currentTrainer = ? AND uniqueID = ?";
				$result2 = $db_New->prepare($query2);
				$result2->bind_param("is", $id, $tradePokeID);
				$result2->execute();
				$result2->store_result();
				$result2->bind_result($pokeNum2);
				if ($result2->affected_rows == 0) {//4
					$result->free_result();
					$result->close();
					$result2->free_result();
					$result2->close();
					$db_New->close();
					echo '<div class="content"><p>You cannot accept this trade offer, you do not own this pokemon.</p></div></div>';
					return;
				}//4
				$result2->fetch();
				$result2->free_result();
				$result2->close();
			}//3
			$query2 = "SELECT num, item, currentTrainer FROM trainer_trades WHERE uniqueID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("s", $requestPokeID);
			if (!$result2->execute()) {//3
				$transactionFlag = false;
			}//3
			$result2->store_result();
			$result2->bind_result($pokeNum, $item, $otherTrainer);
			$result2->fetch();
			$result2->free_result();
			$result2->close();
			//$query2 = "DELETE FROM trainer_trades WHERE uniqueID = ?";
//			$result2 = $db_New->prepare($query2);
//			$result2->bind_param("s", $requestPokeID);
//			if (!$result2->execute()) {
//				$transactionFlag = false;
//			}
//			$result2->close();
			include 'tradeEvolution2.php'; //handles the evolutions	
			if ($item != 13) {//Everstone //3		
				if ($pokeNum == 588) {//4
					if ($pokeNum2 == 616) {//5
						$pokeNickname = "Escavalier";
						$pokeNum = 589;
						$doChangeTo = true;
					}//5
				}else if ($pokeNum == 616) {//4
					if ($pokeNum2 == 588) {//5
						$pokeNickname = "Accelgor";
						$pokeNum = 617;
						$doChangeTo = true;
					}//5			
				}//4
			}//3
			$transactionFlag = trade_To_Pickup2($db_New, $requestPokeID, $id, $doChangeTo, $transactionFlag); 
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $tradePokeID, $requestPokeID);
			if (!$result2->execute()) {//3
				$transactionFlag = false;
			}//3
			$result2->close();
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $requestPokeID, $tradePokeID);
			if (!$result2->execute()) {//3
				$transactionFlag = false;
			}//3
			$result2->close();
			$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ? OR tradePokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $requestPokeID, $tradePokeID);
			if (!$result2->execute()) {//3
				$transactionFlag = false;
			}//3
			$result2->close();
			//$query2 = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalOwner, trainerID, whichProfile, myTag, gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
//			$result2 = $dbActual->prepare($query2);
//			$result2->bind_param("iiiisiiiiiiiisi", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $whichProfile, $myTag, $pokeGender);
//			$result2->execute();
//			$result2->close();
		}//2
		//$dbActual->close();
		
		$transactionFlag = trade_To_Pickup2($db_New, $tradePokeID, $otherTrainer, $doChangeTo, $transactionFlag);
		$transactionFlag = trade_Successful($db_New, $id, $otherTrainer, $transactionFlag);
		if ($transactionFlag == true) {
			$db_New->commit();
			echo '<div class="content"><p>Trade Completed! You can find your new Pokemon at the Pickup Area. Click Home to get to the Pickup Area.</p></div></div>';
		}else{
			$db_New->rollback();
			echo '<div class="content"><p>An Error has Occurred. Please Try Again.</p></div></div>';
		}
		$db_New->autocommit(true);
		$db_New->close();
	}
	include 'template/footer.php';
?>
</body>
</html>