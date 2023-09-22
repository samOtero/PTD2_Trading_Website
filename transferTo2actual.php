<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Transfer to PTD2 Completed";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'breedingList.php';
	include 'template/ptd1_cookies.php';
	$pokeID = $_REQUEST['pokeID'];
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
					<div class="title"><p>Transfer to PTD2 Completed - <a href="transferTo2.php?<?= $urlValidation?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
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
	?>
    <div class="content">
		<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
	</div>
 </div>
    <?php
	$reason = "error";
}
if ($reason == "go") {
$_SESSION['currentSave'] = $currentSave;
$dbActual = get_Pokemon_Database($whichDB, $db);
$newGender = 2;
$pokeGenderRequest = $_REQUEST['pokeGender'];
if ($pokeGenderRequest == 1 || $pokeGenderRequest == 2) {
	$newGender = $pokeGenderRequest;
}

 ?>
 <?php 
	$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, ability, item, originalOwner, myTag FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ? AND ((num >= 1 AND num <= 151) OR (num >= 243 AND num <= 245) OR num = 494 OR num = 1010)";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $mSel, $ability, $item, $originalOwner, $myTag);
	if ($result->affected_rows == 0) {
		$result->close();
		?>
    <div class="content">
		<p>You cannot transfer this pokemon. <a href="trading.php">Click here to go back.</a></p>
	</div>
 </div>
    <?php
	}else{
	$result->fetch();
	$pokeNickname = strip_tags($pokeNickname);
	$result->close();
	$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("i", $pokeID);
	$result->execute();
	if ($result->affected_rows) {
		$result->close();
		$dbActual->close();
		///////new
		$db_New = connect_To_ptd2_Trading();
		if ($pokeLevel < 1) {
			$pokeLevel = 1;
		}else if ($pokeLevel > 100) {
			$pokeLevel = 100;
		}
		$item = 0;
		if ($pokeNum >= 29 && $pokeNum <= 31) {
			$newGender = 2;
		}else if ($pokeNum >= 32 && $pokeNum <= 34) {
			$newGender = 1;
		}else if ($pokeNum >= 120 && $pokeNum <= 121) {
			$newGender = -1;
		}else if ($pokeNum == 132) {
			$newGender = -1;
		}else if ($pokeNum == 124) {
			$newGender = 2;
		}else if ($pokeNum == 113) {
			$newGender = 2;
		}else if ($pokeNum >= 81 && $pokeNum <= 82) {
			$newGender = -1;
		}else if ($pokeNum >= 144 && $pokeNum <= 146) {
			$newGender = -1;
		}else if ($pokeNum >= 150 && $pokeNum <= 151) {
			$newGender = -1;
		}else if ($pokeNum >= 243 && $pokeNum <= 245) {
			$newGender = -1;
		}else if ($pokeNum == 494) {
			$newGender = -1;
		}else if ($pokeNum == 1010) {//Missing No.
			$newGender = -1;
		}else if ($pokeNum == $pokeNum >= 100 && $pokeNum <= 101) {//Voltorb
			$newGender = -1;
		}else if ($pokeNum >= 106 && $pokeNum <= 107) {//Hitmonlee
			$newGender = 1;
		}else if ($pokeNum == 137) {//Porygon
			$newGender = -1;
		}else if ($pokeNum == 128) {//Tauros
			$newGender = 1;
		}else if ($pokeNum == 115) {//Kangaskhan
			$newGender = 2;
		}
		$pokeGender = $newGender;
		$eggInfo = get_Egg_Info($pokeNum, 0, 0, $pokeNum, 0, 0, false);
		$m1 = $eggInfo[3];
		$m2 = $eggInfo[4];
		$m3 = $eggInfo[5];
		$m4 = $eggInfo[6];
		$query2 = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result2 = $db_New->prepare($query2);
		$result2->bind_param("iiiisiiiiiiisi", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $myTag, $pokeGender);
		$result2->execute();
		$result2->close();
		$db_New->close();

		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		?></div><?php
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", $pokeGender, $item);
		?>
        <div class="block">
        <div class="content">
		<p>Pokemon transfered to your PTD2 Pickup area! <a href="transferTo2.php?<?= $urlValidation?>">Click here to go back.</a></p>
	</div>
 </div>
        <?php
		/////////////////////////////////////////////////////////////
	}else {
		$result->close();
		?>
         <div class="content">
		<p>You cannot transfer this pokemon. <a href="trading.php">Click here to go back.</a></p>
	</div>
 </div>
        <?php
	}
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