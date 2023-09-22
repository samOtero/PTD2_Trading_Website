<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Remove Hacked Tag";
	$pageMenuset = "extended";
	require 'moveList.php';
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
					$backURL = "checkPokemon.php";
				?>
					<div class="title"><p>Remove Hacked Tag - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
		global $id, $db, $urlValidation, $whichProfile, $currentSave, $whichDB;
		echo '<div class="content"><p>Here you can remove the "Hacked" tag from you pokemon. You can spend 1 SnD coin or 500,000 Casino Coins to remove the tag from one Pokemon. You can also use 10 SnD Coins to remove the tag from all your profile pokemon. You may only use SnD Coins to remove the tag from all pokemon at once.</p><p><b>Note: Only pokemon in your profile will show up here. Any pokemon up for trade will not be changed. Call them back to your profile to remove their tags. Once you remove a Hacked Tag there will be no refunds to your coins.</b></p>';
		$query = "select howManyCoins from sndCoins WHERE trainerID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($howManyCoins);			
		if ($result->affected_rows) {
			$result->fetch();
		}else{
			$howManyCoins = 0;
		}
		$result->close();
		$query = "select howManyCoins from gameCorner WHERE trainerID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($howManyCasinoCoins);			
		if ($result->affected_rows) {
			$result->fetch();
		}else{
			$howManyCasinoCoins = 0;
		}
		$result->close();
		$whoRemove = $_REQUEST['who'];
		$method = $_REQUEST['method'];
		$cost = 0;
		if ($whoRemove == "all") {
			$cost = 10;
			$method = "snd";
		}else if ($method == "snd") {
			$cost = 1;
		}else if ($method == "casino") {
			$cost = 500000;
		}
		function remove_Hack_Tag($dbActual, $pokeID) {
			global $id, $whichProfile;
			$whichTag = "h";
			$query = "SELECT uniqueID FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND myTag = ? AND uniqueID = ?";
			$result = $dbActual->prepare($query);
			$result->bind_param("iisi", $id, $whichProfile, $whichTag, $pokeID);
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($tempID);
			if ($hmp == 0) {
				echo '<p>Error this pokemon is not eligible</p></div></div>';
				$result->close();
				return false;
			}
			$newTag = "n";
			$query = "UPDATE trainer_pokemons SET myTag = ? WHERE uniqueID =?";
			$result = $dbActual->prepare($query);
			$result->bind_param("si", $newTag, $pokeID);
			$result->execute();
			if ($result->sqlstate=="00000") {
				$result->close();
				return true;
			}else{
				$result->close();
				echo '<p>Error Removing Tag from Pokemon.</p></div></div>';
				return false;
			}
		}
		if ($method == "snd") {
			if ($howManyCoins < $cost) {
				echo '<p><b>You do not have enough SnD Coins to remove the Hacked Tag(s).</b></p>';
			}else{
				$dbActual = get_Pokemon_Database($whichDB, $db);
				if ($whoRemove == "all") {
					$whichTag = "h";
					$query = "SELECT uniqueID FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND myTag = ? ORDER BY num, lvl";
					$result = $dbActual->prepare($query);
					$result->bind_param("iis", $id, $whichProfile, $whichTag);
					$result->execute();
					$result->store_result();
					$hmp = $result->affected_rows;
					$result->bind_result($pokeID);
					if ($hmp == 0) {
						echo '<p>You have no pokemon in this profile with the Hacked Tag.</p></div></div>';
						$result->close();
						return;
					}
					for ($i=1; $i<=$hmp; $i++) {
						$result->fetch();
						if (remove_Hack_Tag($dbActual, $pokeID) == false) {
							$result->close();
							return;
						}
					}
				}else{
					if (remove_Hack_Tag($dbActual, $whoRemove) == false) {
						return;
					}
				}
				$howManyCoins -= $cost;
				$query = "UPDATE sndCoins SET howManyCoins = ? WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$result->close();
					$newCurrentSave = uniqid(true);
					$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
					$result = $db->prepare($query);
					$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
					$result->execute();
					if ($result->sqlstate=="00000") {
						$currentSave = $newCurrentSave;
						$_SESSION['currentSave'] = $currentSave;
						$result->close();
						echo '<p><b>Success! You have remove the Hacked Tag(s) from your pokemon.</b></p>';
					}else{
						$result->close();
						echo '<p><b>Error updating CurrentSave.</b></p></div></div>';
						return;
					}
				}else{
					$result->close();
					echo '<p><b>Error updating SnD Coins Database.</b></p></div></div>';
					return;
				}
			}
		}else if ($method == "casino"){
			if ($howManyCasinoCoins < $cost) {
				echo '<p><b>You do not have enough Casino Coins to remove the Hacked Tag.</b></p>';
			}else{
				$dbActual = get_Pokemon_Database($whichDB, $db);
				if (remove_Hack_Tag($dbActual, $whoRemove) == false) {
					return;
				}
				$howManyCasinoCoins -= $cost;
				$query = "UPDATE gameCorner SET howManyCoins = ? WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("ii", $howManyCasinoCoins, $id);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$result->close();
					$newCurrentSave = uniqid(true);
					$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
					$result = $db->prepare($query);
					$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
					$result->execute();
					if ($result->sqlstate=="00000") {
						$currentSave = $newCurrentSave;
						$_SESSION['currentSave'] = $currentSave;
						$result->close();
						echo '<p><b>Success! You have remove the Hacked Tag from your pokemon.</b></p>';
					}else{
						$result->close();
						echo '<p><b>Error updating CurrentSave.</b></p></div></div>';
						return;
					}
				}else{
					$result->close();
					echo '<p><b>Error updating Casino Coins Database.</b></p></div></div>';
					return;
				}
			}
		}
		echo '<p>You have ('.$howManyCoins.') SnD Coins to use. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more SnD Coins.</a></p>';
		echo '<p>You have ('.$howManyCasinoCoins.') Casino Coins to use. <a href="gameCorner_test.php?'.$urlValidation.'">Click here to get more Casino Coins.</a></p>';
		if ($howManyCoins == 0 && $howManyCasinoCoins < 500000) {
			echo '<p>You do not have enough SnD Coins or Casino Coins to remove the Hacked Tag from any of your pokemon. Click on one of the links above to get more coins.</p>';
			echo '</div></div>';
			return;
		}
		echo '<p>Here is a list of the pokemon in your profile that have the hacked tag:</p>';
		echo '</div></div>';
		$dbActual = get_Pokemon_Database($whichDB, $db);
		$whichTag = "h";
		$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND myTag = ? ORDER BY num, lvl";
		$result = $dbActual->prepare($query);
		$result->bind_param("iis", $id, $whichProfile, $whichTag);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4);
		if ($hmp == 0) {
			echo '<div class="block"><div class="content"><p>You have no pokemon in this profile with the Hacked Tag.</p></div></div>';
			$result->close();
			return;
		}else{
			echo '<div class="block"><div class="content"><p>Remove the hacked tag from all the pokemon below. <a href="removeHack.php?'.$urlValidation.'&who=all">Do it for (10) SnD Coins.</a></p></div></div>';
			for ($i=1; $i<=$hmp; $i++) {
				$result->fetch();
				$isHacked = "";
				if ($myTag == "h") {
					$isHacked = " (Hacked Version)";
				}
				$changeNick = ' <a href="changePokeNickname.php?'.$urlValidation.'&pokeID='.$pokeID.'">Change Nickname</a>';
				$pokeNickname = stripslashes($pokeNickname).$isHacked;
				$extra = 'Remove Hacked Tag for <a href="removeHack.php?'.$urlValidation.'&who='.$pokeID.'&method=snd">(1) SnD Coin</a> or <a href="removeHack.php?'.$urlValidation.'&who='.$pokeID.'&method=casino">(500,000) Casino Coins</a>';
				pokeBox($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra);
			}
		}
		$result->close();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include 'template/footer.php';
?>
</body>
</html>