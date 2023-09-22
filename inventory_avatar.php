<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Inventory - Avatars";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	
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
					<div class="title"><p>Avatar - <a href="inventory.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                     <?php if ($reason == "savedOutside") { ?>
                     <div class="content">
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    </div>
					</div>
                    <?php }else { ?>
					<div class="content">
						<p>Here is a list of your avatars from this profile, you can change this profile's avatar by clicking on the avatar.</p>
					</div>
				</div>
                <?php 
				$db = connect_To_Database();
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
	if ($currentAvatar == "none") {
		?>
        <div class="block">
			<div class="content">
				<p>You have not unlocked avatars yet, to unlock them beat the Vermillion Gym Level in story mode.</p>
			</div>
		</div>
        <?php
	}else{
		?>
        <div class="avatars">
        <?php
		show_Avatar("b", 1, "Regular Boy", $urlValidation);
		show_Avatar("g", 1, "Regular Girl", $urlValidation);
	if ($challenge > 4) {
		show_Avatar("b", 2, "Raikou Hat Boy", $urlValidation);
		show_Avatar("g", 2, "Raikou Hat Girl", $urlValidation);
	}
	if ($avatar3 == 1) {
		show_Avatar("b", 3, "Entei Hat Boy", $urlValidation);
		show_Avatar("g", 3, "Entei Hat Girl", $urlValidation);
	}
	if ($avatar4 == 1) {
		show_Avatar("b", 4, "Suicune Hat Boy", $urlValidation);
		show_Avatar("g", 4, "Suicune Hat Girl", $urlValidation);
	}
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
	if ($have5Avatar) {
		show_Avatar("b", 5, "Shiny Geodude Hat Boy", $urlValidation);
		show_Avatar("g", 5, "Shiny Geodude Hat Girl", $urlValidation);
	}
	if ($have6Avatar) {
		show_Avatar("b", 6, "Bug Catcher Boy", $urlValidation);
		show_Avatar("g", 6, "Bug Catcher Girl", $urlValidation);
	}
	if ($have7Avatar) {
		show_Avatar("b", 7, "Team Rocket Boy", $urlValidation);
		show_Avatar("g", 7, "Team Rocket Girl", $urlValidation);
	}
	if ($have8Avatar) {
		show_Avatar("b", 8, "Camper Boy", $urlValidation);
		show_Avatar("g", 8, "Picknicker Girl", $urlValidation);
	}
	if ($have9Avatar) {
		show_Avatar("b", 9, "Celebi Hoodie", $urlValidation);
	}
	if ($have10Avatar) {
		show_Avatar("b", 10, "Cubone Boy", $urlValidation);
		show_Avatar("g", 10, "Cubone Girl", $urlValidation);
	}
	if ($have14Avatar) {
		show_Avatar("b", 14, "Gastly Boy", $urlValidation);
		show_Avatar("g", 14, "Gastly Girl", $urlValidation);
	}
	if ($have11Avatar) {
		show_Avatar("b", 11, "Spiritomb Boy", $urlValidation);
		show_Avatar("g", 11, "Spiritomb Girl", $urlValidation);
	}
	if ($have12Avatar) {
		show_Avatar("b", 12, "Haunter Boy", $urlValidation);
	}
	if ($have13Avatar) {
		show_Avatar("b", 13, "Gengar Boy", $urlValidation);
		show_Avatar("g", 13, "Gengar Girl", $urlValidation);
	}
	if ($have15Avatar) {
		show_Avatar("b", 15, "Duskull Boy", $urlValidation);
	}
	if ($have16Avatar) {
		show_Avatar("b", 16, "Alakazam Boy", $urlValidation);
		show_Avatar("g", 16, "Alakazam Girl", $urlValidation);
	}
	if ($have17Avatar) {
		show_Avatar("b", 17, "Delibird Boy", $urlValidation);
		show_Avatar("g", 17, "Delibird Girl", $urlValidation);
	}
	if ($have18Avatar) {
		show_Avatar("b", 18, "Elf Boy", $urlValidation);
		show_Avatar("g", 18, "Elf Girl", $urlValidation);
	}
	if ($have19Avatar) {
		show_Avatar("b", 19, "Bulbasaur Hat Boy", $urlValidation);
		show_Avatar("g", 19, "Bulbasaur Hat Girl", $urlValidation);
	}
	?>
    </div>
    <?php
	}
} ?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
	function show_Avatar($whichGender, $whichNum, $whichName, $urlValidation) {
		?>
		<a href="<?php echo 'inventory_avatarChange.php?'.$urlValidation.'&gender='.$whichGender.'&type='.$whichNum?>">
			<div class="block avatar">
				<div class="image_holder">
					<div class="image_center">
						<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $whichGender ?>_<?php echo $whichNum ?>.png" alt="" class="image" />
					</div>
				</div>
				<span class="name"><?php echo $whichName ?></span>
			</div>
		</a>
	<?php
    }
?>
</body>
</html>