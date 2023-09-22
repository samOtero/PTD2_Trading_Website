<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Adopt Now";
	$pageMenuset = "extended";
	require 'trade_To_Pickup_By_ID.php';
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
//*
if ($reason != "savedOutside") {
	$db = connect_To_Database();
	$tradeID = $_REQUEST['tradeID'];
	$idCheck = $_REQUEST['IDCheck'];
	if ($idCheck != $id) {
		echo 'This page is not available for you. You have been sent here by somebody trying to scam you.';
		exit;
	}
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
	$db_New = connect_To_ptd2_Trading();
	$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, sndCost FROM trainer_trades WHERE uniqueID = ?";
	$result = $db_New->prepare($query);
	$result->bind_param("s", $tradeID);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $sndCost);
	//**
	if ($hmp == 0) {
		$reason = "notFound";
	}else{
		$result->fetch();
		//***
		if ($sndCost == 0 || $sndCost < 0) {
			$reason = "notFound";
		}else if ($sndCost > $howManyCoins) {
			$reason = "notEnoughCoins";
		}else{
			$db->autocommit(false);
			$transactionFlag = true;
			$transactionFlag = change_Coins_Amount($currentTrainer, $sndCost, $db, $transactionFlag);
			$transactionFlag = change_Coins_Amount($id, -$sndCost, $db, $transactionFlag);
			$whoAdopting = -2;
			$query = "INSERT INTO sndCoins_usage (trainerID, usedCoins, usedOn) VALUES (?,?,?)";
			$result = $db->prepare($query);
			$result->bind_param("iii", $id, $sndCost, $whoAdopting);
			if (!$result->execute()) {
				$transactionFlag = false;
			}
			$result->close();
			$db_New->autocommit(false);
			$doShelmet = false;
			$transactionFlag = trade_To_Pickup2($db_New, $tradeID, $id, $doShelmet, $transactionFlag);
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("ss", $tradeID, $tradeID);
			if (!$result2->execute()) {
				$transactionFlag = false;
			}
			$result2->close();
			$query2 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("s", $tradeID);
			if (!$result2->execute()) {
				$transactionFlag = false;
			}
			$result2->close();
			if ($transactionFlag == true) {
				$db->commit();
				$db_New->commit();
			}else{
				$reason = "error";
				$db->rollback();
				$db_New->rollback();
			}
			$db->autocommit(true);
			$db_New->autocommit(true);
			$db_New->close();
			$db->close();
		}
		//***
	}
	//**
}
//*
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Adopt Now - <a href="searchTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="http://www.sndgames.com/games/ptd/trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>No pokemon up for adoption found for that id number. Press back to continue.</p>
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
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, '&nbsp;', $pokeGender, $pokeItem);
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
	//////////////////////////////////////////////
	function change_Coins_Amount($trainerID, $howMany, $db, $transactionFlag) {
		$query = "SELECT howManyCoins FROM  sndCoins WHERE trainerID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $trainerID);
		$result->execute();
		$result->store_result();
		$result->bind_result($howManyCoins);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			if ($howMany < 0) {
				$howMany = 0;
			}
			$query = "INSERT INTO sndCoins (trainerID, howManyCoins) VALUES (?,?)";
			$result = $db->prepare($query);
			$result->bind_param("ii", $trainerID, $howMany);
			if (!$result->execute()) {
				$transactionFlag = false;
			}
			$result->close();
		}else{
			$result->fetch();
			$result->close();
			$howManyCoins += $howMany;
			if ($howManyCoins < 0) {
				$howManyCoins = 0;
			}
			$query = "UPDATE sndCoins SET howManyCoins = ? WHERE trainerID = ?";
			$result = $db->prepare($query);
			$result->bind_param("ii", $howManyCoins, $trainerID);
			if (!$result->execute()) {
				$transactionFlag = false;
			}
			$result->close();
		}
		return $transactionFlag;
	}
	///////////////////////////////////////////////
?>
</body>
</html>