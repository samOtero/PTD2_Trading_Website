<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Avatar Store";
	require 'moveList.php';
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$query = "select c_story_".$whichProfile."_a from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($avatarList);			
	if ($result->affected_rows) {
		$result->fetch();
	}else{
		$reason = "savedOutside";
	}
	$result->close();
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
					<div class="title"><p>Avatar Store - <a href="avatarStore.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php
if ($reason != "savedOutside") {
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
				$boyLink = $infoList[0];
				$girlLink = $infoList[1];
				$type = $infoList[2];
				$name = $infoList[3];
				$query = "INSERT INTO sndCoins_usage (trainerID, usedCoins, usedOn) VALUES (?,?,?)";
				$result = $db->prepare($query);
				$result->bind_param("iii", $id, $cost, $whoAdopting);
				$result->execute();
				$result->close();
				if ($avatarList == "") {
					$avatarList = $type;
				}else{
					$avatarList = $avatarList."|".$type;
				}
 				$newCurrentSave = uniqid(true);
				$query = "UPDATE poke_accounts SET currentSave = ?, c_story_".$whichProfile."_a = ? WHERE trainerID = ? AND currentSave = ?";
				$result = $db->prepare($query);
				$result->bind_param("ssis", $newCurrentSave, $avatarList, $id, $currentSave);
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
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins to use in the avatar store. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
					</div>
				</div>
				<div class="block">
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>No avatar found for that id number. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to buy this avatar. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Congratulations! You have purchased the following Avatar:</p>
            <?php } ?>
                    </div>
				</div>
                 <?php 
		if ( $reason == "success") {
			?>
            <div class="avatars">
            <?php
			avatarBox($name, $boyLink, $girlLink, '&nbsp;');
		?>
        </div>
        <div class="block">
          <div class="content">
            <p>To use this avatar go to the Inventory page in the Pokemon Center.</p>
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