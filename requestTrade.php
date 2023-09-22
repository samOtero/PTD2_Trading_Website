<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Request Trade";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
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
					$backURL = "searchTrades.php";
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
		global $id, $db, $urlValidation, $whichProfile, $currentSave;
		$action = $_REQUEST['Action'];
		$tradeID = $_REQUEST['tradeID'];
		$db_New = connect_To_Database_New();
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4);
		if ($hmp == 0) {
			echo '<div class="content"><p>No Pokemon have been found that match your criteria.</p></div></div>';
			return;
		}
		$result->fetch();
		$pokeNickname = stripslashes($pokeNickname);
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = $pokeNickname.$isHacked;
		$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
		$result2 = $db->prepare($query);
		$result2->bind_param("i", $currentTrainer);
		$result2->execute();
		$result2->store_result();
		$result2->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
		$result2->fetch();
		$result2->close();
		pokeBox_Request($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", ${avatar.$whichAvatar}, $accNickname);
		$result->close();
		show_Trade_Wants($db_New, $tradeID);
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE currentTrainer = ? AND uniqueID != ? AND pickup != 1 ORDER BY num, lvl";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $requestID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4);
		if ($hmp == 0) {
			echo '<div class="block"><div class="content"><p>You have no pokemon available for trading. To make your pokemon available for trading go to the &quot;Create Trade&quot; section.</p></div></div>';
			return;
		}
		echo '<div class="block"><div class="content"><p>Pick which pokemon you wish to offer for this trade: (You can only offer up 6 pokemon per trade, if you offer ALL of the requested pokemon the trade will automatically be done)</p></div></div>';
		?>
    	<form id="form1" name="form1" method="post" action="offerMe.php?<?php echo $urlValidation?>&tradeID=<?php echo $tradeID ?>">
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
			pokeBox($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, 'Offer Me <input type="checkbox" name="offer[]" value="'.$requestID.'" />');
		}
		$result->close();
		?>
		<p class="clear"><input type="submit" name="button" id="button" value="Submit" /></p>
    	</form>
          </div></div>
        <?php
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function show_Trade_Wants($db, $tradeID) {
		 $madeRequest = false;
	 	$query = "SELECT num, level, levelComparison, shiny FROM trade_wants WHERE tradePokeID = ?";
		$result = $db->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($num, $level, $levelComparison, $shiny);
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
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
			if ($num == 0) {
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
			}
			echo '<div class="block"><div class="content"><p>'.$poke.' - Lvl ('.$myLevel.') '.$isShiny.'</p></div></div>';
		 }
		 if (!$madeRequest) {
			 echo '<div class="block"><div class="content"><p>This trainer made no request for this trade.</p></div></div>';
		 }
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include 'template/footer.php';
?>
</body>
</html>