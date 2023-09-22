<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Call Back Pokémon";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "go";
$query = "select whichDB from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($whichDB);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
	$tradeID = $_REQUEST['tradeID'];
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
			</td>
			<td id="main">
				<div class="block">
					<div class="title"><p>Call Back Pokémon - <a href="yourTrades.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                    <?php }else{ 
								$newCurrentSave = uniqid(true);
								$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
								$result = $db->prepare($query);
								$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
								$result->execute();
								if ($result->sqlstate=="00000") {
									$currentSave = $newCurrentSave;
									$result->close();
									$_SESSION['currentSave'] = $currentSave;
									do_Stuff();
								}else{
									$result->close();
									?>
									<div class="content">
										<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
									</div>
								 	
									<?php
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
		global $id, $tradeID, $whichDB, $db, $whichProfile;
		$db_New = connect_To_Database_New();
		$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, ability, item, originalTrainer, myTag FROM trainer_trades WHERE currentTrainer = ? AND uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $tradeID);
		$result->execute();
		$result->store_result();
		$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $mSel, $ability, $item, $originalOwner, $myTag);
		if ($result->affected_rows == 0) {
			$result->close();?>
			<div class="content">
				<p>You cannot call back this Pokemon.</p>
			</div>
			<?php
			return;
		}
		$result->fetch();
		$result->close();
		$query = "DELETE FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->close();
		$query = "DELETE FROM trade_wants WHERE tradePokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->close();
		$query = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("ss", $tradeID, $tradeID);
		$result->execute();
		$result->close();
		if ($pokeNum == 64) {
			if ($pokeNickname == "Kadabra") {
				$pokeNickname = "Alakazam";
			}
			$pokeNum = 65;
		}else if ($pokeNum == 67) {
			if ($pokeNickname == "Machoke") {
				$pokeNickname = "Machamp";
			}
			$pokeNum = 68;
		}else if ($pokeNum == 75) {
			if ($pokeNickname == "Graveler") {
				$pokeNickname = "Golem";
			}
			$pokeNum = 76;
		}else if ($pokeNum == 93) {
			if ($pokeNickname == "Haunter") {
				$pokeNickname = "Gengar";
			}
			$pokeNum = 94;
		}
		$dbActual = get_Pokemon_Database($whichDB, $db);
		$query = "INSERT INTO trainer_pokemons (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, originalOwner, trainerID, whichProfile, myTag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $dbActual->prepare($query);
		$result->bind_param("iiiisiiiiiiiiiis", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $ability, $mSel, $item, $originalOwner, $id, $whichProfile, $myTag);
		$result->execute();
		?>
		<div class="content">
			<p>Called Pokemon back to this profile.</p>
		</div>
		<?php
	}
	include 'template/footer.php';
?>
</body>
</html>