<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Latest Trades";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	//require 'trade_To_Pickup_By_ID.php';
	include 'template/head.php';
?>
<body>
<?php
$reason = "go";
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
                <?php
					$backURL = "checkPokemon.php";
				?>
					<div class="title"><p>Latest Trades - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
					do_Stuff();
				}
	 			?>
			</td>
		</tr>
	</table>
</div>
<?php
	function do_Stuff() {
		global $id, $db, $urlValidation, $whichProfile, $currentSave;
		$action = $_REQUEST['Action'];
		$expireDate = date( 'Y-m-d', strtotime('-10 days'));
		//if ($action == "search") {
			//$whichPoke = $_REQUEST['pokeList'];
			//$whichPokeWant = $_REQUEST['pokeList2'];
			//$typeWant = $_REQUEST['typeWant'];
			//$type = $_REQUEST['type'];
			//$haveRequest = $_REQUEST['haveRequest'];
			//$specificID = $_REQUEST['specificTradeID'];
			//$specificTrainer = $_REQUEST['trainerName'];
			
			$db_New = connect_To_Database_New();
			
			/////////////////RETURN TO PICKUP A POKEMON THAT HAS BEEN UP FOR MORE THAN 10 DAYS WITH INACTIVITY IN THE USER/////////////////////////////
			/*$query = "SELECT uniqueID, currentTrainer FROM trainer_trades WHERE pickup = 0 AND lastTimeUsed <= ? LIMIT 1";
			$result = $db_New->prepare($query);
			$result->bind_param("s", $expireDate);
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($tradeIDPickup, $currentTrainerPickup);
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$query3 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
			$result3 = $db_New->prepare($query3);
			for ($i=0; $i<$hmp; $i++) {
				$result->fetch();
				//echo $tradeID;
				$result2->bind_param("ss", $tradeIDPickup, $tradeIDPickup);
				$result2->execute();
				$result3->bind_param("s", $tradeIDPickup);
				$result3->execute();
				trade_To_Pickup($db_New, $tradeIDPickup, $currentTrainerPickup);
			}
			$result2->close();
			$result3->close();
			$result->close();*/
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
			//if ($whichPokeWant != 0) {
				
			//}else if ($haveRequest == "1") {		
				
			//}else {
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE lastTimeUsed > ? ORDER BY tradeUniqueID DESC limit 250";
				$result = $db_New->prepare($query);
				$result->bind_param("s",$expireDate);		
			//}
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4);
			if ($hmp == 0) {
				echo '<div class="content"><p>No Pokemon have been found that match your criteria.</p></div></div>';
				return;
			}
			echo '</div>';
			//$db = connect_To_Database();
			for ($i=1; $i<=$hmp; $i++) {
				$result->fetch();
				$pokeNickname = stripslashes($pokeNickname);
				$isHacked = "";
				if ($myTag == "h") {
					$isHacked = " - <b>(Hacked Version)</b>";
				}
				$pokeNickname = $pokeNickname.$isHacked;
				//$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
//				$result2 = $db->prepare($query);
//				$result2->bind_param("i", $currentTrainer);
//				$result2->execute();
//				$result2->store_result();
//				$result2->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
//				$result2->fetch();
//				$result2->close();
				$accNickname = "";
				$avatar1 = "";
				$avatar2 = "";
				$avatar3 = "";
				$whichAvatar = "1";
				pokeBox_Search($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, '<a href="requestTrade.php?'.$urlValidation.'&tradeID='.$tradeID.'">Request Trade</a>', ${avatar.$whichAvatar}, $accNickname, $tradeID);
			}
			$result->close();
		//}else{
		//echo '<div class="content">';	
		
	}
	include 'template/footer.php';
?>
</body>
</html>