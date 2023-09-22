<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Call Back Pokémon";
	$pageMenuset = "extended";
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
	$tradeID = $_REQUEST['tradeID'];
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
			</td>
			<td id="main">
				<div class="block">
					<div class="title"><p>Call Back Pokémon - <a href="yourTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                    <?php }else{ 
							$db = connect_To_Database();
							$updateResult = update_Current_Save($db, $id, $currentSave);
							$reason = $updateResult[0];
							$currentSave = $updateResult[1];
							$db->close();
							if ($reason == "error") {
								echo '<div class="content">
									<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
								</div>';
							}else{
								do_Stuff();
							}
						}
					?>
               </div>
			</td>
		</tr>
	</table>
</div>
<?php
	function do_Stuff() {
		global $id, $tradeID, $whichDB, $whichProfile;
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, myTag, gender, happy FROM trainer_trades WHERE currentTrainer = ? AND uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $tradeID);
		$result->execute();
		$result->store_result();
		$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $myTag, $pokeGender, $pokeHoF);
		if ($result->affected_rows == 0) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo '<div class="content"><p>You cannot call back this Pokemon.</p></div>';
			return;
		}
		$result->fetch();
		$result->free_result();
		$result->close();
		$db_New->autocommit(false);
		$transactionFlag = true;
		$query = "DELETE FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		$query = "DELETE FROM trade_wants WHERE tradePokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		$query = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ss", $tradeID, $tradeID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		include 'tradeEvolution2.php'; //handles the evolutions
		$dbActual = get_PTD2_Pokemon_Database($whichDB);
		$dbActual->autocommit(false);
		$query = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalOwner, trainerID, whichProfile, myTag, gender, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $dbActual->prepare($query);
		$result->bind_param("iiiisiiiiiiiisii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $whichProfile, $myTag, $pokeGender, $pokeHoF);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		if ($transactionFlag == true) {
			$db_New->commit();
			$dbActual->commit();
			echo '<div class="content"><p>Called Pokemon back to this profile.</p></div>';
		}else{
			$db_New->rollback();
			$dbActual->rollback();
			echo '<div class="content"><p>Error occured. Please Try Again.</p></div>';
		}
		$db_New->autocommit(true);
		$dbActual->autocommit(true);
		$db_New->close();
		$dbActual->close();
	}
	include 'template/footer.php';
?>
</body>
</html>