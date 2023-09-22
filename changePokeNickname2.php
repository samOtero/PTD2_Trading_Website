<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Change Pokemon Nickname";
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
	$pokeID = $_REQUEST['pokeID'];
	$action = $_REQUEST['Action'];
	$urlValidation = "whichProfile=".$whichProfile."&pokeID=".$pokeID;
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
					<div class="title"><p>Change Pokémon Nickname - <a href="createTrade2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                    <?php } else { ?>
                    <?php 
$db = get_PTD2_Pokemon_Database($whichDB);
	//$query = "SELECT num, lvl, shiny, nickname FROM trainer_pokemons WHERE trainerID = ? AND (originalOwner = ? OR nickname = 'bob') AND uniqueID = ?";
	$query = "SELECT num, lvl, shiny, nickname, myTag, gender, happy FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $db->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $pokeNickname, $myTag, $pokeGender, $pokeHoF);
	if ($result->affected_rows == 0) {
		$result->free_result();
		$result->close();
		$db->close();
		 ?>
    	<div class="content">
			<p>You cannot change this pokemon's nickname.</p>
		</div>
	<?php }else{
	$result->fetch();
	$result->free_result();
	$result->close();
	if ($action == "change") {
		$newNickname = $_REQUEST['newNickname'];
		$newNickname = strip_tags($newNickname);
		if (!ctype_alpha($newNickname)) {
			?>
			<div class="content">
			<p>You cannot use numbers or symbols for your pokemon's nickname. <a href="changePokeNickname2.php?<?php echo $urlValidation ?>">Please try again.</a></p>
		</div>
        <?php
		$db->close();
		}else{
			$query = "UPDATE trainer_pokemons SET nickname = ? WHERE uniqueID = ?";
			$result = $db->prepare($query);
			$result->bind_param("si", $newNickname, $pokeID);
			$result->execute();
			if ($result->sqlstate=="00000") {
			?>
    	<div class="content">
			<p>Your pokemon's nickname has been changed. <a href="createTrade2.php?<?php echo $urlValidation ?>">Go back to your pokemon list.</a></p>
		</div>
	<?php
				
			}else {
		?>
    	<div class="content">
			<p>Could not change your pokemon's nickname at this time. Please try again later. <a href="createTrade2.php?<?php $urlValidation ?>">Go back to your pokemon list.</a></p>
		</div>
	<?php
			}
			$result->close();
			$db->close();
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
		$genderName = get_Gender($pokeGender);
		$genderIcon = "";
		if ($genderName != "none") {
			$genderIcon = '<img src = "'.get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
		}
		$hallOfFame = '';
		if ($pokeHoF != 0) {
			$hallOfFame = ' <img src = "images/ribbon_smaller.png"/>HoF';
		}
		$pokeNickname = stripslashes($pokeNickname);
?>
					<div class="content middle">
						<form action="changePokeNickname2.php?<?php echo $urlValidation?>&Action=change" method="post" name="form1" id="form1">
							<p>Change your pokemon's nickname and press submit. Note: No numbers or symbols are allowed in the nickname.</p>
							<p><img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum?>_0.png"> <?php echo $pokeNickname.$genderIcon?> - Lvl (<?php echo $pokeLevel?>) <b><?php echo $isShiny.$isHacked.$hallOfFame?></b></p>
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