<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Inventory - Avatar Change";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	
	if ($reason != "savedOutside") {
		$db = connect_To_Database(); 
	 $newCurrentSave = uniqid(true);
$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
$result = $db->prepare($query);
$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
$result->execute();
if ($result->sqlstate=="00000") {
	$currentSave = $newCurrentSave;
	$result->close();
}else{
	$result->close();
	$reason = "error";
	//echo "Error in the database. ";
	//echo '<a href="trading.php">Click here to go back.</a>';
}
$_SESSION['currentSave'] = $currentSave;
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
					<div class="title"><p>Inventory - Change Avatar - <a href="inventory_avatar.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
                     <div class="content">
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    </div>
					</div>
                    <?php }else if ($reason == "error") { ?>
                     <div class="content">
                    <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
                    </div>
					</div>
                    <?php }else {
						
					$whichGender = $_REQUEST['gender'];
$whichAvatar = $_REQUEST['type'];
	$query = "SELECT avatar".$whichProfile.", challenge_".$whichProfile.", c_story_".$whichProfile."_a FROM poke_accounts WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("i", $id);
	$result->execute();
	$result->store_result();
	$result->bind_result($currentAvatar, $challenge, $extraInfo);
	$result->fetch();
	$result->close();
	$query = "SELECT ach_four, ach_five FROM trainer_achievements WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("i", $id);
	$result->execute();
	$result->store_result();
	$result->bind_result($avatar3, $avatar4);
	$result->fetch();
	$result->close();
	if ($whichAvatar == 2 && $challenge < 5) {
		$reason = "no";
	}else if ($whichAvatar == 3 && $avatar3 == 0) {
		$reason = "no";
	}else if ($whichAvatar == 4 && $avatar4 == 0) {
		$reason = "no";
	}else if ($whichAvatar == 0) {
		//echo 'You are not eligible for that avatar.';
		$reason = "no";
	}else{
	$infoArray = explode("|", $extraInfo);
	$have5Avatar = false;
	for ($i=0; $i<count($infoArray); $i++) {
		if ($infoArray[$i] == "1") {
			$have5Avatar = true;
		}else if ($infoArray[$i] == "3") {
			$have6Avatar = true;
		}else if ($infoArray[$i] == "4") {
			$have7Avatar = true;
		}else if ($infoArray[$i] == "5") {
			$have8Avatar = true;
		}else if ($infoArray[$i] == "6") {
			$have9Avatar = true;
		}else if ($infoArray[$i] == "7") {
			$have10Avatar = true;
		}else if ($infoArray[$i] == "8") {
			$have14Avatar = true;
		}else if ($infoArray[$i] == "9") {
			$have11Avatar = true;
		}else if ($infoArray[$i] == "10") {
			$have12Avatar = true;
		}else if ($infoArray[$i] == "11") {
			$have13Avatar = true;
		}else if ($infoArray[$i] == "12") {
			$have15Avatar = true;
		}else if ($infoArray[$i] == "13") {
			$have16Avatar = true;
		}else if ($infoArray[$i] == "14") {
			$have17Avatar = true;
		}else if ($infoArray[$i] == "15") {
			$have18Avatar = true;
		}else if ($infoArray[$i] == "16") {
			$have19Avatar = true;
		}
	}
	if ($whichAvatar == 5 && !$have5Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 6 && !$have6Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 7 && !$have7Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 8 && !$have8Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 9 && !$have9Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 10 && !$have10Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 11 && !$have11Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 12 && !$have12Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 13 && !$have13Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 14 && !$have14Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 15 && !$have15Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 16 && !$have16Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 17 && !$have17Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 18 && !$have18Avatar) {
		$reason = "no";
	}else if ($whichAvatar == 19 && !$have19Avatar) {
		$reason = "no";
	}else if ($whichAvatar <= 0) {
		$reason = "no";
	}else if ($whichAvatar > 19) {
		$reason = "no";
	}else{
	$newAvatar = $whichGender."_".$whichAvatar;
	$query = "UPDATE poke_accounts SET avatar".$whichProfile." = ? WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("si", $newAvatar, $id);
	$result->execute();
	if ($result->sqlstate=="00000") {
		$result->close();
		//echo 'You avatar has been changed.';
	}else{
		$result->close();
		$reason = "database";
		//echo 'Could not change your avatar due to a Database issue, please try again later.';
	}
	}
	}
					?>
					<div class="content">
                    <?php
                    	if ($reason == "database") { ?>
						<p>Here is a list of your avatars from this profile, you can change this profile's avatar by clicking on the avatar.</p>
                        <?php }else if ($reason == "no") {?>
                        <p>You are not eligible for that avatar.</p>
                         <?php }else {?>
                         <p>You avatar has been changed.</p>
                          <?php }?>
					</div>
					</div>
                    <?php
					}
					?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>