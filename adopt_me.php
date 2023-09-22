<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Adopt Me";
	$pageMenuset = "extended";
	require 'moveList.php';
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
if ($reason != "savedOutside") {
	$db = connect_To_Database();
	$whoAdopting = $_REQUEST['who'];
	$query = "select extraInfo, category from sndCoins_inventory WHERE invID = ? AND whichGame = 'ptd'";
	$result = $db->prepare($query);
	$result->bind_param("i", $whoAdopting);
	$result->execute();
	$result->store_result();
	$result->bind_result($extraInfo, $categoryNum);
	if ($result->affected_rows == 0) {
		$result->close();
		$reason = "notFound";
	}else{
		$result->fetch();
		$result->close();
		$query = "select costInCoins from sndCoins_category WHERE catID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $categoryNum);
		$result->execute();
		$result->store_result();
		$result->bind_result($cost);
		$result->fetch();
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
		$result->close();
		if ($howManyCoins < $cost) {
			$reason = "notEnoughCoins";
		}else{
			$howManyCoins -= $cost;
			$query = "UPDATE sndCoins SET howManyCoins = ? WHERE trainerID = ?";
			$result = $db->prepare($query);
			$result->bind_param("ii", $howManyCoins, $id);
			$result->execute();
			if ($result->sqlstate=="00000") {
				$result->close();
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
				$query = "INSERT INTO sndCoins_usage (trainerID, usedCoins, usedOn) VALUES (?,?,?)";
				$result = $db->prepare($query);
				$result->bind_param("iii", $id, $cost, $whoAdopting);
				$result->execute();
				$result->close();
				if ($allow == 1) {
					$query = "INSERT INTO allowedList (originalTrainer, allowed) VALUES (?,?)";
					$result = $db->prepare($query);
					$result->bind_param("ii", $id, $who);
					$result->execute();
					$result->close();
				}
				$db_New = connect_To_Database_New();
				$query = "INSERT INTO trainer_pickup (pickup, num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, item, ability, uniqueID, currentTrainer, originalTrainer) VALUES (1,?, ?, 0, ?, ?, ?, ?, ?, ?, 1, 0, 0, ?, ?, ?)";
				$result = $db_New->prepare($query);
				$uniqueID = uniqid(true);
				$result->bind_param("iiisiiiisii", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $uniqueID, $id, $id);
				$result->execute();
				$result->close();
 				$newCurrentSave = uniqid(true);
				$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
				$result = $db->prepare($query);
				$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$currentSave = $newCurrentSave;
					$_SESSION['currentSave'] = $currentSave;
					$result->close();
				}else{
					$result->close();
					$reason = "error";
				}
			}else{
				$result->close();
				$reason = "error";
			}
				
		}
	}
}
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Adopt Me - <a href="adoption.php?<?php echo $urlValidation ?>">Go Back</a></p>
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
			pokeBox($nickname, $myLevel, $infoList[1], $move1, $move2, $move3, $move4, $who, '&nbsp;');
		?>
        <div class="block">
          <div class="content">
            <p>This pokemon seems to like you a lot! Take care of it! You can pick it up at the Pokémon Center.</p>
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