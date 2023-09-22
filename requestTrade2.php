<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Request Trade";
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
			?>
			<td id="main">
				<div class="block">
                <?php
					$backURL = "searchTrades2.php";
				?>
					<div class="title"><p>Request Trade - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function do_Stuff() {
		global $id, $urlValidation, $whichProfile, $currentSave;
		$action = $_REQUEST['Action'];
		$tradeID = $_REQUEST['tradeID'];
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $pokeHoF);
		if ($hmp == 0) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo '<div class="content"><p>No Pokemon have been found that match your criteria.</p></div></div>';
			return;
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
		$db = connect_To_Database();
		$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
		$result2 = $db->prepare($query);
		$result2->bind_param("i", $currentTrainer);
		$result2->execute();
		$result2->store_result();
		$result2->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
		$result2->fetch();
		$result2->free_result();
		$result2->close();
		$db->close();
		pokeBox_Request2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", ${avatar.$whichAvatar}, $accNickname, $pokeGender, $pokeItem, $currentTrainer, $pokeHoF);
		show_Trade_Wants($db_New, $tradeID);
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_trades WHERE currentTrainer = ? AND uniqueID != ? ORDER BY num, lvl";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $requestID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $pokeHoF);
		if ($hmp == 0) {
			echo '<div class="block"><div class="content"><p>You have no pokemon available for trading. To make your pokemon available for trading go to the &quot;Create Trade&quot; section.</p></div></div>';
			return;
		}
		echo '<div class="block"><div class="content"><p>Pick which pokemon you wish to offer for this trade: (You can only offer up 10 pokemon per trade. If you offer all of the pokemon from one of the 3 possible request the trade will automatically go through.)</p></div></div>';
		?>
    	<form id="form1" name="form1" method="post" action="offerMe2.php?<?php echo $urlValidation?>&tradeID=<?php echo $tradeID ?>">
        <div class="block"><div class="content">
		<?php
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
			$pokeNickname = stripslashes($pokeNickname);
			$isHacked = "";
			if ($myTag == "h") {
				$isHacked = " - <b>(Hacked Version)</b>";
			}
			$pokeNickname = $pokeNickname.$isHacked;
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, 'Offer Me <input type="checkbox" name="offer[]" value="'.$requestID.'" />', $pokeGender, $pokeItem, $pokeHoF);
		}
		$result->free_result();
		$result->close();
		$db_New->close();
		?>
		<p class="clear"><input type="submit" name="button" id="button" value="Submit" /></p>
    	</form>
          </div></div>
        <?php
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
						<p>This trainer made the following request:</p>
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
			 echo '<div class="block"><div class="content"><p>This trainer made no request for this trade.</p></div></div>';
		 }
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include 'template/footer.php';
?>
</body>
</html>