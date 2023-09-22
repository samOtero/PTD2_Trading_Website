<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Remove Hacked Tag";
	$pageMenuset = "extended";
	require 'moveList.php';
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
	$isDaily = $_REQUEST['daily'];
	$howManyCoins = 0;
	$dailyCoinTotal = 50;
	$sndCoinTotal = 1;
	$dbActual = get_PTD2_Pokemon_Database($whichDB);
	$query = "SELECT num, lvl, nickname, m1, m2, m3, m4, item, gender, shiny, happy FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ? AND myTag = 'h'";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeNickname, $m1, $m2, $m3, $m4, $item, $pokeGender, $pokeShiny, $pokeHoF);
	if ($result->affected_rows == 0) {
		$reason = "notFound";
	}else{
		$result->fetch();
	}
	$result->close();
	$db = connect_To_Database();
	if ($reason != "notFound") {///
		if ($isDaily == "1") {////
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
			if ($howManyCoins < $dailyCoinTotal) {
				$reason = "notEnoughCoins";
			}
		}else{////
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
			$result->free_result();
			$result->close();
			if ($howManyCoins < $sndCoinTotal) {
				$reason = "notEnoughCoins";
			}
		}////
		if ($reason != "notEnoughCoins") {////
			if ($reason != "error") {/////
				$transactionFlag = true;
				$dbActual->autocommit(false);
				$query = 'UPDATE trainer_pokemons SET myTag = "n" WHERE uniqueID = ?';
				$result = $dbActual->prepare($query);
				$result->bind_param("i", $pokeID);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$reason = "error";
					$transactionFlag = false;
				}
				$result->close();
				if ($isDaily == "1") {
					$howManyCoins = $howManyCoins - $dailyCoinTotal;
					$db2->autocommit(false);
					$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
					$result = $db2->prepare($query);
					$result->bind_param("ii", $howManyCoins, $id);
					if (!$result->execute() || $result->sqlstate!="00000") {
						$transactionFlag = false;
					}
					$result->close();
				}else{
					$howManyCoins = $howManyCoins - $sndCoinTotal;
					$db->autocommit(false);
					$query = "UPDATE sndCoins SET howManyCoins = ? WHERE trainerID = ?";
					$result = $db->prepare($query);
					$result->bind_param("ii", $howManyCoins, $id);
					if (!$result->execute() || $result->sqlstate!="00000") {
						$transactionFlag = false;
					}
					$result->close();
				}
				if ($transactionFlag == true) {
					if ($isDaily == "1") {
						$db2->commit();
					}else{
						$db->commit();
					}
					$dbActual->commit();
					$db->autocommit(true);
					$dbActual->autocommit(true);
					$updateResult = update_Current_Save($db, $id, $currentSave);
					$reason = $updateResult[0];
					$currentSave = $updateResult[1];
				}
				if ($transactionFlag == false) {
					$reason = "error";
					if ($isDaily == "1") {
						$db2->rollback();
					}else{
						$db->rollback();
					}
					$dbActual->rollback();
				}
				if ($isDaily == "1") {
					$db2->autocommit(true);
					$db2->close();
				}
				$db->autocommit(true);
				$dbActual->autocommit(true);
				$dbActual->close();
				$db->close();
			}/////
		}////
	}///
}//
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Remove Hacked Tag - <a href="removeHack2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>You don't own the pokemon found for that id number. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to remove the hacked tag. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Congratulations! You have removed the hack tag on your pokemon:</p>
            <?php } ?>
          </div>
        </div>
        <?php 
		if ( $reason == "success") {
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, '&nbsp;', $pokeGender, $item, $pokeHoF);
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