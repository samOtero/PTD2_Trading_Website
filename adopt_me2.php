<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Adopt Me";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	//$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
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
if ($reason != "savedOutside") {
	$db = connect_To_Database();
	$whoAdopting = $_REQUEST['who'];
	$isDaily = $_REQUEST['daily'];
	$query = "select extraInfo, category from sndCoins_inventory WHERE invID = ? AND whichGame = 'ptd2'";
	$result = $db->prepare($query);
	$result->bind_param("i", $whoAdopting);
	$result->execute();
	$result->store_result();
	$result->bind_result($extraInfo, $categoryNum);
	if ($result->affected_rows == 0) {
		$result->free_result();
		$result->close();
		$db->close();
		$reason = "notFound";
	}else{
		$result->fetch();
		$result->free_result();
		$result->close();
		if ($isDaily == "1") {
			$query = "select dailyCost from sndCoins_category WHERE catID = ?";
			$result = $db->prepare($query);
			$result->bind_param("i", $categoryNum);
			$result->execute();
			$result->store_result();
			$result->bind_result($cost);
			$result->fetch();
			$result->free_result();
			$result->close();
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
			$db2->close();
		}else{
			$query = "select costInCoins from sndCoins_category WHERE catID = ?";
			$result = $db->prepare($query);
			$result->bind_param("i", $categoryNum);
			$result->execute();
			$result->store_result();
			$result->bind_result($cost);
			$result->fetch();
			$result->free_result();
			$result->close();
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
		}
		if ($howManyCoins < $cost) {
			$reason = "notEnoughCoins";
			$db->close();
		}else{
			$transactionFlag = true;
			$howManyCoins -= $cost;
			$infoList = explode("|", $extraInfo);
			$who = $infoList[0];
			$isShiny = $infoList[1];
			$myLevel = $infoList[2];
			$move1 = $infoList[3];
			$move2 = $infoList[4];
			$move3 = $infoList[5];
			$move4 = $infoList[6];
			$ability = $infoList[7];
			$item = $infoList[8];
			$nickname = $infoList[9];
			$allow = $infoList[10];
			$gender = $infoList[11];
			$item = $infoList[12];
			if ($isDaily == "1") {
				$db2 = connect_To_ptd2_Story_Database();
				$db2->autocommit(false);
				$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
				$result = $db2->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$transactionFlag = false;
				}
				$result->close();
				$query = "INSERT INTO dailyCoinUsage (trainerID, usedOn) VALUES (?,?)";
				$result = $db2->prepare($query);
				$result->bind_param("ii", $id, $whoAdopting);
				if (!$result->execute()) {
					$transactionFlag = false;
				}
				$result->close();
			}else{
				$db->autocommit(false);
				$query = "UPDATE sndCoins SET howManyCoins = ? WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$transactionFlag = false;
				}
				$result->close();
				$query = "INSERT INTO sndCoins_usage (trainerID, usedCoins, usedOn) VALUES (?,?,?)";
				$result = $db->prepare($query);
				$result->bind_param("iii", $id, $cost, $whoAdopting);
				if (!$result->execute()) {
					$transactionFlag = false;
				}
				$result->close();
			}
			$db_New = connect_To_ptd2_Trading();
			$db_New->autocommit(false);
			$myTag = "n";
			$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
			$result = $db_New->prepare($query);
			$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $id, $gender, $myTag);
			if (!$result->execute()) {
				$transactionFlag = false;
			}
			$result->close();
			//DONE!
			if ($transactionFlag == true) {
				if ($isDaily == "1") {
					$db2->commit();
				}else{
					$db->commit();
				}
				$db_New->commit();
				$db->autocommit(true);
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
				$db_New->rollback();
			}
			if ($isDaily == "1") {
				$db2->autocommit(true);
				$db2->close();
			}
			$db->autocommit(true);
			$db_New->autocommit(true);
			$db_New->close();
			$db->close();				
				
		}
	}
}
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Adopt Me - <a href="adoption2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>No pokemon found for that id number. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to make this adoption. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Congratulations! You have adopted the following pokemon:</p>
            <?php } ?>
          </div>
        </div>
        <?php 
		if ( $reason == "success") {
			pokeBox2($nickname, $myLevel, $isShiny, $move1, $move2, $move3, $move4, $who, '&nbsp;', $gender, $item);
		?>
        <div class="block">
          <div class="content">
            <p>This pokemon seems to like you a lot! Take care of it! You can pick it up at the Pokémon Center Home Page.</p>
          </div>
        </div>
        <?php } ?>
        </td>
    </tr>
  </table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>