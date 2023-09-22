<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Change Pokemon Nickname";
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
	$pokeID = $_REQUEST['pokeID'];
$action = $_REQUEST['Action'];
	$urlValidation = "whichProfile=".$whichProfile."&pokeID=".$pokeID;
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
					<div class="title"><p>Change Pokémon Nickname - <a href="createTrade.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                    <?php } else { ?>
                    <?php 
$db = get_Pokemon_Database($whichDB, $db);
	//$query = "SELECT num, lvl, shiny, nickname FROM trainer_pokemons WHERE trainerID = ? AND (originalOwner = ? OR nickname = 'bob') AND uniqueID = ?";
	$query = "SELECT num, lvl, shiny, nickname, myTag FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $db->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $pokeNickname, $myTag);
	if ($result->affected_rows == 0) { ?>
    	<div class="content">
			<p>You cannot change this pokemon's nickname.</p>
		</div>
	<?php }else{
	$result->fetch();
	if ($action == "change") {
		$result->close();
		$newNickname = $_REQUEST['newNickname'];
		$newNickname = strip_tags($newNickname);
		if (!ctype_alpha($newNickname)) {
			?>
			<div class="content">
			<p>You cannot use numbers or symbols for your pokemon's nickname. <a href="changePokeNickname.php?<?php echo $urlValidation ?>">Please try again.</a></p>
			</div>
        <?php
		}else{
			$query = "UPDATE trainer_pokemons SET nickname = ? WHERE uniqueID = ?";
			$result = $db->prepare($query);
			$result->bind_param("si", $newNickname, $pokeID);
			$result->execute();
			if ($result->sqlstate=="00000") {
				?>
			<div class="content">
				<p>Your pokemon's nickname has been changed. <a href="createTrade.php?<?php echo $urlValidation ?>">Go back to your pokemon list.</a></p>
			</div>
		<?php
			$result->close();
			}else {
			$result->close();
			?>
			<div class="content">
				<p>Could not change your pokemon's nickname at this time. Please try again later. <a href="createTrade.php?<?php $urlValidation ?>">Go back to your pokemon list.</a></p>
			</div>
		<?php
		}
		}
	}else{
		if ($pokeShiny == 0) {
			$isShiny = "Regular";
		}else if ($pokeShiny == 2) {
			$isShiny = "<b>Shadow</b>";
		}else {
			$isShiny = "<b>Shiny</b>";
		}
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = stripslashes($pokeNickname);
	$result->close();
?>
					<div class="content middle">
						<form action="changePokeNickname.php?<?php echo $urlValidation?>&Action=change" method="post" name="form1" id="form1">
							<p>Change your pokemon's nickname and press submit.</p>
							<p><img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum?>_0.png"> <?php echo $pokeNickname?> - Lvl (<?php echo $pokeLevel?>) <b><?php echo $isShiny.$isHacked?></b></p>
							<p><span class="formLabel">New Nickname:</span><input type="text" maxlength="19" id="newNickname" name="newNickname"></p>
							<p><input type="submit" value="Submit" id="submit" name="submit"></p>
						</form>
				</div>
                 <?php 
					}
	}
				 }?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>