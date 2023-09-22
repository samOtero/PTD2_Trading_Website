<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Transfer to PTD3";
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
					<div class="title"><p>Transfer to PTD3 - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Here is a list of your pokemon from this profile that are eligible to be transfered to PTD3, click on the gender you want it to be to transfer it to the PTD3 Pickup area.</p>
						<p>NOTE: This will remove the pokemon from your profile. <strong>YOU CANNOT TRANSFER THEM BACK!</strong> Also their moves will reset when transfered to PTD3 to the moves they would get at level 1 of their stage 1 evolution.</p>
					</div>
				</div>
                <?php 
$dbActual = get_Pokemon_Database($whichDB, $db);
	$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND ((num >= 1 AND num <= 151) OR (num >= 243 AND num <= 245) OR num = 494 OR num = 1010) ORDER BY num, lvl";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $whichProfile);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4);
	if ($hmp == 0) {
		?>
        <div class="block">
        <div class="content">
		<p>You have no eligible pokemon in this profile.</p>
		</div>
		</div>
        <?php
	}else{
	for ($i=1; $i<=$hmp; $i++) {
		$result->fetch();
		if ($pokeShiny == 0) {
			$isShiny = "Regular";
		}else{
			$isShiny = "<b>Shiny</b>";
		}
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " (Hacked Version)";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		//if ($pokeNum >= 29 && $pokeNum <= 31) {
		//}
		$extra = 'Transfer <a href="transferTo3actual.php?'.$urlValidation.'&pokeGender=1&pokeID='.$pokeID.'">Male</a> | <a href="transferTo3actual.php?'.$urlValidation.'&pokeGender=2&pokeID='.$pokeID.'">Female</a>';
		if (($pokeNum >= 29 && $pokeNum <= 31) || $pokeNum == 124 || $pokeNum == 113 || $pokeNum == 115) {
			$extra = 'Transfer <a href="transferTo3actual.php?'.$urlValidation.'&pokeGender=2&pokeID='.$pokeID.'">Female</a>';
		}else if (($pokeNum >= 32 && $pokeNum <= 34) || ($pokeNum >= 106 && $pokeNum <= 107) || $pokeNum == 128) {
			$extra = 'Transfer <a href="transferTo3actual.php?'.$urlValidation.'&pokeGender=1&pokeID='.$pokeID.'">Male</a>';
		}else if (($pokeNum >= 81 && $pokeNum <= 82) || $pokeNum == 132 || ($pokeNum >= 120 && $pokeNum <= 121) || ($pokeNum >= 144 && $pokeNum <= 146) || ($pokeNum >= 150 && $pokeNum <= 151) || ($pokeNum >= 243 && $pokeNum <= 245) || $pokeNum == 494 || ($pokeNum >= 100 && $pokeNum <= 101) || $pokeNum == 1010 || $pokeNum == 137) {
			$extra = 'Transfer <a href="transferTo3actual.php?'.$urlValidation.'&pokeGender=-1&pokeID='.$pokeID.'">Genderless</a>';
		}
		pokeBoxTransferTo2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeID, $whichProfile);
	}
	}
	$result->close();
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