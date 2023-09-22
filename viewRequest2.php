<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Trade Request";
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
	$tradeID = $_REQUEST['tradeID'];
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
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Trade Request - <a href="yourTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
		global $tradeID, $id, $urlValidation;
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_trades WHERE currentTrainer = ? AND uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $pokeHoF);
		if ($hmp == 0) { 
			$result->free_result();
			$result->close();
			$db_New->close();
			?>
        	<div class="content">
			<p>You cannot view this pokemon's trade request.</p>
			</div>
            </div>
			<?php return;
		}
		$result->fetch();
		$result->free_result();
		$result->close();
		$pokeNickname = stripslashes($pokeNickname);
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = $pokeNickname.$isHacked;
		echo '</div>';
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", $pokeGender, $pokeItem, $pokeHoF);
		show_Trade_Wants($db_New, $tradeID);
		 echo '<div class="block"><div class="title thin"><p>These are the trade request for this pokemon:</p></div></div>';
		 $haveRequest = false;
		$offerIDList = array();
		$query2 = "SELECT offerID FROM trade_request WHERE tradePokeID = ? AND offerID != '-1'";
		$result2 = $db_New->prepare($query2);
		$result2->bind_param("s", $tradeID);
		$result2->execute();
		$result2->store_result();
		$hmr = $result2->affected_rows;
		$result2->bind_result($offerID);
		for ($b=1; $b<=$hmr; $b++) {
			$result2->fetch();
			$original = true;
			$haveRequest = true;
			for ($c=0; $c<count($offerIDList); $c++) {
				$oldOfferID = $offerIDList[$c];
				if ($oldOfferID == $offerID) {
					$original = false;
					break;
				}
			}
			if ($original == true) {
				array_push($offerIDList, $offerID);
			}
		}
		$result2->free_result();
		$result2->close();
		$db = connect_To_Database();
		for ($b=0; $b<count($offerIDList); $b++) {
			$offerID = $offerIDList[$b];
			show_Request($db_New, $db, $tradeID, $offerID, $urlValidation);
		}
		$db->close();
		$db_New->close();
		if ($haveRequest == false) {
			 echo '<div class="block"><div class="title thin"><p>You do not have any request for this pokemon.</p></div></div>';
		}
	}
	include 'template/footer.php';
	function show_Request($db_New, $db, $tradeID, $offerID, $urlValidation) {
		$query2 = "SELECT requestPokeID, requestID, offerID FROM trade_request WHERE tradePokeID = ? AND offerID = ?";
		$result2 = $db_New->prepare($query2);
		$result2->bind_param("ss", $tradeID, $offerID);
		$result2->execute();
		$result2->store_result();
		$hmr = $result2->affected_rows;
		$result2->bind_result($requestPokeID, $requestID, $offerID);
		$showName = false;
		for ($b=1; $b<=$hmr; $b++) {
			$haveRequest = true;
			$result2->fetch();
			$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_trades WHERE uniqueID = ?";
			$result = $db_New->prepare($query);
			$result->bind_param("s", $requestPokeID);
			$result->execute();
			$result->store_result();
			$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $pokeHoF);
			$result->fetch();
			$result->free_result();
			$result->close();
			$pokeNickname = stripslashes($pokeNickname);
			$isHacked = "";
			if ($myTag == "h") {
				$isHacked = " - <b>(Hacked Version)</b>";
			}
			$pokeNickname = $pokeNickname.$isHacked;
			$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
			$result3 = $db->prepare($query);
			$result3->bind_param("i", $currentTrainer);
			$result3->execute();
			$result3->store_result();
			$result3->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
			$result3->fetch();
			$result3->free_result();
			$result3->close();
			$responceText = ' - Status: Waiting on Your Response <a href="acceptRequest2.php?'.$urlValidation.'&requestID='.$requestID.'&offerID='.$offerID.'">Accept</a> or <a href="denyRequest2.php?'.$urlValidation.'&requestID='.$requestID.'&offerID='.$offerID.'">Deny</a>';
			if ($showName == false) {
				echo '<div class="block"><div class="title middle"><p><img src="'. get_Graphic_Url().'/trading_center/avatar/'.${avatar.$whichAvatar}.'.png"/><b> '.$accNickname.'</b> offered the following pokemon. '.$responceText.'</p></div></div>';
				$showName = true;
			}
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", $pokeGender, $pokeItem, $pokeHoF);
		}
		$result2->free_result();
		$result2->close();
	}
	function show_Trade_Wants($db, $tradeID) {
		 $madeRequest = false;
	 	$query = "SELECT num, level, levelComparison, shiny, gender, whichRequest FROM trade_wants WHERE tradePokeID = ? ORDER BY whichRequest";
		$result = $db->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($num, $level, $levelComparison, $shiny, $gender, $whichRequest);
		$currentRequest = 1;
		$currentCount = 1;
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
			$genderName = get_Gender($gender);
			$genderIcon = "";
			if ($genderName != "none") {
				$genderIcon = '<img src = "'. get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
			}
			$shinyType = 0;
			if ($shiny == 1) {
				$isShiny = "<b>Shiny</b>";
				$shinyType = 1;
			}else if ($shiny == -1) {
				$isShiny = "Regular or <b>Shiny</b> or <b>Shadow</b>";
			}else if ($shiny == 2) {
				$isShiny = "<b>Shadow</b>";
			}else{
				$isShiny = "Regular";
			}
			if ($levelComparison == 1) {
				$comp = "=";
			}else if ($levelComparison == 2) {
				$comp = "<=";
			}else if ($levelComparison == 3) {
				$comp = ">=";
			}else if ($levelComparison == 4) {
				$comp = "<";
			}else if ($levelComparison == 5) {
				$comp = ">";
			}
			if ($level == 0) {
				$myLevel = "Any";
			}else{
				$myLevel = $comp.$level;
			}
			if ($num == -2) {
				$poke = 'Any Pokemon';
			}else{
				$poke = '<img src="'. get_Graphic_Url().'/games/ptd/small/'.$num.'_'.$shinyType.'.png"/>';
			}
			if (!$madeRequest) {
				$madeRequest = true; ?>
                <div class="block">
					<div class="title thin">
						<p>You made the following request:</p>
					</div>
				</div>
                <?php
				$currentRequest = $whichRequest;
			}
			if ($currentRequest != $whichRequest) {
				$currentCount = 1;
				$currentRequest = $whichRequest;
				echo '</div></div>';
				echo '<div class="block"><div class="content"><div class = "title thin"><p>OR</p></div></div></div>';
			}
			if ($currentCount == 1) {
				echo '<div class="block"><div class="content">';
			}
			echo '<p>'.$poke.$genderIcon.' - Lvl ('.$myLevel.') '.$isShiny.'</p>';
			if ($i == $hmp) {
				echo '</div></div>';
			}
			$currentCount++;
		 }
		 $result->free_result();
		 $result->close();
		 if (!$madeRequest) {
			 echo '<div class="block"><div class="content"><p>You made no request for this trade.</p></div></div>';
		 }
	}
?>
</body>
</html>