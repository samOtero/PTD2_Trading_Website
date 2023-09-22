<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Deevolution Chamber";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'breedingList.php';
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
if ($reason != "savedOutside") {//
	$pokeID = $_REQUEST['pokeID'];
	$howManyCoins = 0;
	$dbActual = get_PTD2_Pokemon_Database($whichDB);
	$query = "SELECT num, lvl, nickname, m1, m2, m3, m4, item, gender, happy, shiny FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";//.get_Legal_Query();
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeNickname, $m1, $m2, $m3, $m4, $item, $pokeGender, $pokeHoF, $pokeShiny);
	if ($result->affected_rows == 0) {
		$reason = "notFound";
	}else{
		$result->fetch();
	}
	$result->close();
	$dailyCost = 15;
	if ($reason != "notFound") {///
		$db2 = connect_To_ptd2_Story_Database();
		$query = "select howMany from dailyCoins WHERE trainerID = ?";
		$result = $db2->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($howManyCoins);			
		if ($result->affected_rows) {
			$result->fetch();
		}else{
			$howManyCoins = 0;
		}
		$result->free_result();
		$result->close();
		if ($howManyCoins < $dailyCost) {
			$reason = "notEnoughCoins";
		}
		if ($reason != "notEnoughCoins") {////
			$eggInfo = get_Egg_Info($pokeNum, 0, 0, $pokeNum, 0, 0, false);
			$newPokeNum = $eggInfo[0];
			if ($pokeNum == 298 || $pokeNum == 360 || $pokeNum == 406 || $pokeNum == 433 || $pokeNum == 438 || $pokeNum == 439 || $pokeNum == 440 || $pokeNum == 446 || $pokeNum == 458) {//breeding with item pokemon (would otherwise turn into a higher evolution)
				$newPokeNum = $pokeNum;
			}
			if (empty($newPokeNum)) {/////
				$reason = "error";
			}else if ($newPokeNum == $pokeNum && $pokeLevel == 1 && $pokeHoF == 0){/////
				$reason = "notNeeded";
			}/////
			if ($reason != "error" && $reason != "notNeeded") {/////
				$transactionFlag = true;
				$dbActual->autocommit(false);
				$query = 'UPDATE trainer_pokemons SET lvl = 1, happy = 0, exp = 0, num = ? WHERE uniqueID = ?';
				$result = $dbActual->prepare($query);
				$result->bind_param("ii", $newPokeNum, $pokeID);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$reason = "error";
					$transactionFlag = false;
				}
				$result->close();
				$howManyCoins = $howManyCoins - $dailyCost;
				$db2->autocommit(false);
				$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
				$result = $db2->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$transactionFlag = false;
				}
				$result->close();
				if ($transactionFlag == true) {
					$db2->commit();
					$dbActual->commit();
					$dbActual->autocommit(true);
					$db = connect_To_Database();
					$updateResult = update_Current_Save($db, $id, $currentSave);
					$reason = $updateResult[0];
					$currentSave = $updateResult[1];
					$db->close();
				}
				if ($transactionFlag == false) {
					$reason = "error";
					$db2->rollback();
					$dbActual->rollback();
				}
				$db2->autocommit(true);
				$db2->close();
				$dbActual->autocommit(true);
				$dbActual->close();
			}/////
		}////
	}///
}//
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Deevolution Chamber - <a href="deevolve2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
          <p><img src="images/elm_front.png" width="34"></p>
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>The Pokemon you are referring to doesn't qualify. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to make this deevolution. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php }else if ($reason == "notNeeded") { ?>
           <p>The Beam had no effect on this Pokemon... so I won't charge you... Press back to continue.</p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Congratulations! You have deevolved the following pokemon, how CUTE!:</p>
            <?php } ?>
          </div>
        </div>
        <?php 
		if ( $reason == "success") {
			pokeBox2($pokeNickname, 1, $pokeShiny, $m1, $m2, $m3, $m4, $newPokeNum, '&nbsp;', $pokeGender, $item, 0);
		} ?>
        </td>
    </tr>
  </table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>