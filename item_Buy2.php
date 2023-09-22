<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Buy Item";
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
	$itemNum = $_REQUEST['num'];
	$itemNumRecord = 1000+$itemNum;
	$itemQuantity = 1;
	$costType = $_REQUEST['type'];
		if ($costType == "daily") {
			$cost = 20;
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
			$itemQuantity = 10;
			$cost = 1;
			$db = connect_To_Database();
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
			$db->close();
		}
		///////////////////////////TEST ITEMS////////////////////////////////////////////
		if ($itemNum == 25) {
			$db_New = connect_To_ptd2_Story_Extra_Database();
			$whichExtra = "18";
			if (get_Extra_Value($db_New, $id, $whichProfile, $whichExtra) != "1") {
				$itemNum = 10000;
			}
			$db_New->close();
		}else if ($itemNum == 34) {
			$db_New = connect_To_ptd2_Story_Extra_Database();
			$whichExtra = "65";
			if (get_Extra_Value($db_New, $id, $whichProfile, $whichExtra) != "1") {
				$itemNum = 10000;
			}
			$db_New->close();
		}else if ($itemNum >= 54 && $itemNum <= 70) {
			$db_New = connect_To_ptd2_Story_Extra_Database();
			if ($itemNum == 54) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "105") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 55) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "106") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 56) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "107") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 57) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "108") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 58) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "109") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 59) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "110") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 60) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "111") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 61) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "112") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 62) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "113") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 64) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "114") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 65) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "115") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 66) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "116") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 67) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "117") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 68) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "118") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 69) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "119") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 70) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "120") != "1") {
					$itemNum = 10000;
				}
			}else if ($itemNum == 79) {
				if (get_Extra_Value($db_New, $id, $whichProfile, "122") != "1") {
					$itemNum = 10000;
				}
			}
			$db_New->close();
		}
		/////////////////////////////////////////////////////////////////////////////////
		if ($howManyCoins < $cost) {
			$reason = "notEnoughCoins";
		}else if ($itemNum < 13 || ($itemNum > 27 && $itemNum != 34 && $itemNum < 54) || ($itemNum > 70 && $itemNum != 79)) {
			$reason = "notFound";
		}else{
			$howManyCoins -= $cost;
			$transactionFlag = true;
			if ($costType == "daily") {
				$db2 = connect_To_ptd2_Story_Database();
				$db2->autocommit(false);
				//UPDATE YOUR DAILY COIN AMOUNT
				$query = "UPDATE dailyCoins SET howMany = ? WHERE trainerID = ?";
				$result = $db2->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				if (!$result->execute() || $result->sqlstate!="00000") {
					$transactionFlag = false;
				}
				$result->close();
				//RECORD YOUR DAILY COIN USAGE (THIS IS VERY POORLY IMPLEMENTED, NOT VERY DETAILED AT ALL)
				$query = "INSERT INTO dailyCoinUsage (trainerID, usedOn) VALUES (?,?)";
				$result = $db2->prepare($query);
				$result->bind_param("ii", $id, $itemNumRecord);
				if (!$result->execute()) {
					$transactionFlag = false;
				}
				$result->close();
			}else{
				$db = connect_To_Database();
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
				$result->bind_param("iii", $id, $cost, $itemNumRecord);
				if (!$result->execute()) {
					$transactionFlag = false;
				}
				$result->close();
			}
			if ($reason != "error") {
				$db_New = connect_To_ptd2_Story_Extra_Database();
				$db_New->autocommit(false);
				$query = "select whichItem, quantity from items WHERE trainerID = ? AND whichProfile = ? AND whichItem = ?";
				$result = $db_New->prepare($query);
				$result->bind_param("iii", $id, $whichProfile, $itemNum);
				$result->execute();
				$result->store_result();
				$result->bind_result($myItemTemp, $myItemQuantity);
				$totalValues = $result->affected_rows;
				$result->fetch();
				$result->close();
				if ($totalValues > 0) {
					$myItemQuantity += $itemQuantity;
					$query = "UPDATE items SET quantity = ? WHERE trainerID = ? AND whichProfile = ? AND whichItem = ?";
					$result = $db_New->prepare($query);
					$result->bind_param("iiii", $myItemQuantity, $id, $whichProfile, $itemNum);
					if (!$result->execute()) {
						$transactionFlag = false;
					}
					$result->close();
				}else{
					$query = "INSERT INTO items (whichItem, quantity, trainerID, whichProfile) VALUES (?, ?, ?, ?)";
					$result = $db_New->prepare($query);
					$result->bind_param("iiii", $itemNum, $itemQuantity, $id, $whichProfile);
					if (!$result->execute()) {
						$transactionFlag = false;
					}
					$result->close();
				}
				if ($transactionFlag == true) {
					$db_New->commit();
					if ($costType == "daily") {
						$db2->commit();
					}else{
						$db->commit();
						$db->autocommit(true);
						$db->close();
					}
					$db = connect_To_Database();
					$updateResult = update_Current_Save($db, $id, $currentSave);
					$reason = $updateResult[0];
					$currentSave = $updateResult[1];
					$db->close();
				}else{
					$reason = "error";
					$db_New->rollback();
					if ($costType == "daily") {
						$db2->rollback();
					}else{
						$db->rollback();
						$db->autocommit(true);
						$db->close();
					}
				}
				if ($costType == "daily") {
					$db2->autocommit(true);
					$db2->close();
				}
				$db_New->autocommit(true);
				$db_New->close();				
			}
				
		}
}
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Buy Item - <a href="itemStore2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>No item found for that id number. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to make this purchase. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Congratulations! You have purchased (<?php echo $itemQuantity ?>) of the following item(s):</p>
            <?php } ?>
          </div>
        </div>
        <?php 
		if ( $reason == "success") {
			itemBox_Simple2($itemNum);
		?>
        <div class="block">
          <div class="content">
            <p>You can give this item to your pokemon inside of the game itself. Remember to avoid play the game and the trading center at the same time.</p>
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