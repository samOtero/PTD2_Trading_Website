<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Create Trade";
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
					<div class="title"><p>Create Trade - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Here is a list of your pokemon from this profile, click on Trade to create a new Trade.</p>
						<p>NOTE: This will remove the pokemon from your profile. To get him back to your profile go back to the "Your Trade Request" section and call your pokemon back.</p>
					</div>
				</div>
                <?php 
$dbActual = get_Pokemon_Database($whichDB, $db);
	$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? ORDER BY num, lvl";
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
		<p>You have no pokemon in this profile.</p>
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
		$changeNick = ' | <a href="changePokeNickname.php?'.$urlValidation.'&pokeID='.$pokeID.'">Change Nickname</a>';
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		$extra = '<a href="tradeMeSetup.php?'.$urlValidation.'&pokeID='.$pokeID.'">Trade</a>'.$changeNick;
		pokeBoxCreateTrade($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeID, $whichProfile);
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