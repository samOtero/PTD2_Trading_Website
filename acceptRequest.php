<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Accept Trade Offer";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	require 'trade_To_Pickup_By_ID.php';
	$db = connect_To_Database();
$reason = "go";
	$urlValidation = "whichProfile=".$whichProfile;
	$offerID = $_REQUEST['offerID'];
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
					<div class="title"><p>Accept Trade Offer - <a href="yourTrades.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
		global $id, $db, $urlValidation, $offerID, $whichProfile, $currentSave;
		$newCurrentSave = uniqid(true);
		$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
		$result = $db->prepare($query);
		$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
		$result->execute();
		if ($result->sqlstate=="00000") {
			$currentSave = $newCurrentSave;
			$_SESSION['currentSave'] = $currentSave;
			$result->close();
		}else{
			$result->close();
			echo '<div class="content"><p>Error in the database. <a href="trading.php">Click here to go back.</a></p></div></div>';
			return;
		}
		if (empty($offerID)) {
			echo '<div class="content"><p>Error, press the back link to fix this error.</p></div></div>';
			return;
		}
		$db_New = connect_To_Database_New();
		$query = "SELECT requestPokeID, tradePokeID FROM trade_request WHERE offerID = ? ";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $offerID);
		$result->execute();
		$result->store_result();
		$result->bind_result($requestPokeID, $tradePokeID);
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			echo '<div class="content"><p>This offer is no longer available.</p></div></div>';
			return;
		}
		$firstCheck = false;
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
			if ($firstCheck == false) {
				$firstCheck = true;
				$query2 = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalTrainer, myTag FROM trainer_trades WHERE currentTrainer = ? AND uniqueID = ?";
				$result2 = $db_New->prepare($query2);
				$result2->bind_param("is", $id, $tradePokeID);
				$result2->execute();
				$result2->store_result();
				$result2->bind_result($pokeNum2, $pokeLevel2, $pokeExp2, $pokeShiny2, $pokeNickname2, $m12, $m22, $m32, $m42, $ability2, $mSel2, $item2, $originalOwner2, $myTag2);
				if ($result2->affected_rows == 0) {
					$result2->close();
					echo '<div class="content"><p>You cannot accept this trade offer, you do not own this pokemon.</p></div></div>';
					return;
				}
				$result2->fetch();
				$result2->close();
			}
			$query2 = "SELECT currentTrainer FROM trainer_trades WHERE uniqueID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("s", $requestPokeID);
			$result2->execute();
			$result2->store_result();
			$result2->bind_result($otherTrainer);
			$result2->fetch();
			trade_To_Pickup($db_New, $requestPokeID, $id);
			//$query2 = "DELETE FROM trainer_trades WHERE uniqueID = ?";
//			$result2 = $db_New->prepare($query2);
//			$result2->bind_param("s", $requestPokeID);
//			$result2->execute();
//			$result2->close();
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $tradePokeID, $requestPokeID);
			$result2->execute();
			$result2->close();
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $requestPokeID, $tradePokeID);
			$result2->execute();
			$result2->close();
			$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ? OR tradePokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $requestPokeID, $tradePokeID);
			$result2->execute();
			$result2->close();
			//if ($pokeNum == 64) {
//				if ($pokeNickname == "Kadabra") {
//					$pokeNickname = "Alakazam";
//				}
//				$pokeNum = 65;
//			}else if ($pokeNum == 67) {
//				if ($pokeNickname == "Machoke") {
//					$pokeNickname = "Machamp";
//				}
//				$pokeNum = 68;
//			}else if ($pokeNum == 75) {
//				if ($pokeNickname == "Graveler") {
//					$pokeNickname = "Golem";
//				}
//				$pokeNum = 76;
//			}else if ($pokeNum == 93) {
//				if ($pokeNickname == "Haunter") {
//					$pokeNickname = "Gengar";
//				}
//				$pokeNum = 94;
//			}
			//$dbActual = get_Pokemon_Database($whichDB, $db);
			//$query2 = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalOwner, trainerID, whichProfile, myTag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			//$result2 = $dbActual->prepare($query2);
			//$result2->bind_param("iiiisiiiiiiiiiis", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $ability, $mSel, $item, $originalOwner, $id, $whichProfile, $myTag);
			//$result2->execute();
		}
		trade_To_Pickup($db_New, $tradePokeID, $otherTrainer);
		$result->close;
		echo '<div class="content"><p>Trade Completed!</p></div></div>';
	}
	include 'template/footer.php';
?>
</body>
</html>