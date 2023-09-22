<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Your Trades";
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
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Your Trade Request - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>These are the request that you have received for your pokemon:</p>
					</div>
				</div>
                <?php
$db_New = connect_To_Database_New();
$db = connect_To_Database();
$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE currentTrainer = ? AND pickup = 0 ORDER BY num";
$result = $db_New->prepare($query);
$result->bind_param("i", $id);
$result->execute();
$result->store_result();
$hmp = $result->affected_rows;
$yourTrades = array();
$yourTradesInfo = array();
$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4);
	if ($hmp == 0) { ?>
    <div class="block">
    <div class="content">
		<p>You have no pokemon up for trade. Go to the Create Trade Section to put some pokemon up for trade.</p>
	</div>
	</div>
	<?php }else{
	for ($i=1; $i<=$hmp; $i++) {
		$result->fetch();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = "<b>(Hacked Version)</b> - ";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		array_push($yourTrades, $tradeID);
		$yourTradesInfo[$tradeID."_num"] = $pokeNum;
		$yourTradesInfo[$tradeID."_lvl"] = $pokeLevel;
		$yourTradesInfo[$tradeID."_shiny"] = $pokeShiny;
		$yourTradesInfo[$tradeID."_nickname"] = $pokeNickname;
		$yourTradesInfo[$tradeID."_myTag"] = $myTag;
		$yourTradesInfo[$tradeID."_m1"] = $m1;
		$yourTradesInfo[$tradeID."_m2"] = $m2;
		$yourTradesInfo[$tradeID."_m3"] = $m3;
		$yourTradesInfo[$tradeID."_m4"] = $m4;
		if ($pokeShiny == 0) {
			$isShiny = "Regular";
		}else if ($pokeShiny == 2) {
			$isShiny = "<b>Shadow</b>";
		}else {
			$isShiny = "<b>Shiny</b>";
		}
		$query2 = "SELECT distinct offerID FROM trade_request WHERE tradePokeID = ?";
		$result2 = $db_New->prepare($query2);
		$result2->bind_param("s", $tradeID);
		$result2->execute();
		$result2->store_result();
		$hmr = $result2->affected_rows;
		pokeBox_Your_Trade($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, $hmr, $tradeID, $urlValidation);
		$result2->close();
	}
	}
	$result->close();
	?>
				<div class="block">
					<div class="title thin"><p>These are the trades that you requested for:</p></div>
				</div>
                <?php
$haveRequest = false;
$hmp = count($yourTrades);
$offerIDList = array();
for ($i=0; $i<$hmp; $i++) {
	$currentTrade = $yourTrades[$i];
$query2 = "SELECT offerID FROM trade_request WHERE requestPokeID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $currentTrade);
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
	$result2->close();
}
	for ($b=0; $b<count($offerIDList); $b++) {
		$offerID = $offerIDList[$b];
		show_Offers($db_New, $db, $offerID, $urlValidation);
	}
if ($haveRequest == false) { ?>
<div class="block">
<div class="title middle"><p>You have not requested any trades.</p></div>
</div>
<?php }
?>
<?php }?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
function show_Offers($db_New, $db, $offerID, $urlValidation) {
	$query2 = "SELECT tradePokeID, requestID FROM trade_request WHERE offerID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $offerID);
	$result2->execute();
	$result2->store_result();
	$hmr = $result2->affected_rows;
	$result2->bind_result($tradePokeID, $requestID);
	$result2->fetch();
	$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $tradePokeID);
		$result->execute();
		$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag);
		$result->fetch();
		$pokeNickname = stripslashes($pokeNickname);
		if ($pokeShiny == 0) {
			$isShiny = "Regular";
		}else if ($pokeShiny == 2) {
			$isShiny = "<b>Shadow</b>";
		}else {
			$isShiny = "<b>Shiny</b>";
		}
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
	$result3 = $db->prepare($query);
	$result3->bind_param("i", $currentTrainer);
	$result3->execute();
	$result3->store_result();
	$result3->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
	$result3->fetch();
	$result3->close();
	?>
    <div class="block">
					<div class="title middle"><p>Trade Offer to <img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo ${avatar.$whichAvatar} ?>.png"> <?php echo $accNickname ?> for his <img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum ?>_0.png"> <?php echo $pokeNickname ?> - Lvl (<?php echo $pokeLevel ?>) <b><?php echo $isShiny.$isHacked ?> </b></p></div>
					<div class="content middle">
						<p>Status: Waiting on Response - <a href="removeRequest.php?<?php echo $urlValidation?>&requestID=<?php echo $requestID ?>&offerID=<?php echo $offerID ?>">Remove Offer</a></p>
    <?php
	$result->close();
	$result2->close();
	$query2 = "SELECT requestPokeID FROM trade_request WHERE offerID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $offerID);
	$result2->execute();
	$result2->store_result();
	$hmr = $result2->affected_rows;
	$result2->bind_result($requestPokeID);
	for ($b=1; $b<=$hmr; $b++) {
		$result2->fetch();
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $requestPokeID);
		$result->execute();
		$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag);
		$result->fetch();
		$pokeNickname = stripslashes($pokeNickname);
		if ($pokeShiny == 0) {
			$isShiny = "Regular";
		}else if ($pokeShiny == 2) {
			$isShiny = "<b>Shadow</b>";
		}else {
			$isShiny = "<b>Shiny</b>";
		}
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
		$result3 = $db->prepare($query);
		$result3->bind_param("i", $currentTrainer);
		$result3->execute();
		$result3->store_result();
		$result3->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
		$result3->fetch();
		$result3->close();
		?>
        <p><img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum ?>_0.png"> <?php echo $pokeNickname ?> - Lvl (<?php echo $pokeLevel ?>) <b><?php echo $isShiny.$isHacked ?></b></p>
        <?php
		$result->close();
	}
	$result2->close(); ?>
    </div>
	</div>
    <?php
}
?>
</body>
</html>