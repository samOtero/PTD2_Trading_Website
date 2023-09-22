<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Your Trades";
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
					<div class="title"><p>Your Trade Request - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>These are the pokemon you have up for trade. Click on "View Request" to view, accept or decline trade offers for that pokemon:</p>
					</div>
				</div>
                <?php
$db_New = connect_To_ptd2_Trading();
$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, item, gender, sndCost, happy FROM trainer_trades WHERE currentTrainer = ? ORDER BY num DESC";
$result = $db_New->prepare($query);
$result->bind_param("i", $id);
$result->execute();
$result->store_result();
$hmp = $result->affected_rows;
$yourTrades = array();
//$yourTradesInfo = array();
$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeItem, $pokeGender, $sndCost, $pokeHoF);
	if ($hmp == 0) { ?>
    <div class="block">
    <div class="content">
		<p>You have no pokemon up for trade. Go to the Create Trade Section to put some pokemon up for trade.</p>
	</div>
	</div>
	<?php }else{
		$tradeInfo = array();
		$requestInfo = array();
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
			$isHacked = "";
			if ($myTag == "h") {
				$isHacked = "<b>(Hacked Version)</b> - ";
			}
			$pokeNickname = stripslashes($pokeNickname).$isHacked;
			array_push($yourTrades, $tradeID);
			//$yourTradesInfo[$tradeID."_num"] = $pokeNum;
			//$yourTradesInfo[$tradeID."_lvl"] = $pokeLevel;
			//$yourTradesInfo[$tradeID."_shiny"] = $pokeShiny;
			//$yourTradesInfo[$tradeID."_nickname"] = $pokeNickname;
			//$yourTradesInfo[$tradeID."_myTag"] = $myTag;
			//$yourTradesInfo[$tradeID."_m1"] = $m1;
			//$yourTradesInfo[$tradeID."_m2"] = $m2;
			//$yourTradesInfo[$tradeID."_m3"] = $m3;
			//$yourTradesInfo[$tradeID."_m4"] = $m4;
			//$yourTradesInfo[$tradeID."_item"] = $pokeItem;
			//$yourTradesInfo[$tradeID."_gender"] = $pokeGender;
			$query2 = "SELECT distinct offerID FROM trade_request WHERE tradePokeID = ?";
			$result2 = $db_New->prepare($query2);
			$result2->bind_param("s", $tradeID);
			$result2->execute();
			$result2->store_result();
			$hmr = $result2->affected_rows;
			$tradeInfo[$tradeID] = array("num"=>$pokeNum, "lvl"=>$pokeLevel, "shiny"=>$pokeShiny, "nickname"=>$pokeNickname, "m1"=>$m1, "m2"=>$m2, "m3"=>$m3, "m4"=>$m4, "hmr"=>$hmr, "tradeID"=>$tradeID, "gender"=>$pokeGender, "item"=>$pokeItem, "cost"=>$sndCost, "HoF"=>$pokeHoF);
			$requestInfo[$tradeID] = $hmr;
			//pokeBox_Your_Trade2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, $hmr, $tradeID, $urlValidation, $pokeGender, $pokeItem, $sndCost);
			$result2->free_result();
			$result2->close();
		}
		arsort($requestInfo);
		foreach ($requestInfo as $x=>$x_value) {
			$pokeNickname = $tradeInfo[$x]["nickname"];
			$pokeLevel = $tradeInfo[$x]["lvl"];
			$pokeShiny = $tradeInfo[$x]["shiny"];
			$m1 = $tradeInfo[$x]["m1"];
			$m2 = $tradeInfo[$x]["m2"];
			$m3 = $tradeInfo[$x]["m3"];
			$m4 = $tradeInfo[$x]["m4"];
			$pokeNum = $tradeInfo[$x]["num"];
			$hmr = $x_value;
			$tradeID = $x;
			$pokeGender = $tradeInfo[$x]["gender"];
			$pokeItem = $tradeInfo[$x]["item"];
			$sndCost = $tradeInfo[$x]["cost"];
			$pokeHoF = $tradeInfo[$x]["HoF"];
			pokeBox_Your_Trade2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, $hmr, $tradeID, $urlValidation, $pokeGender, $pokeItem, $sndCost, $pokeHoF);
		}
	}
	$result->free_result();
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
	$result2->free_result();
	$result2->close();
}
	$db = connect_To_Database();
	for ($b=0; $b<count($offerIDList); $b++) {
		$offerID = $offerIDList[$b];
		show_Offers($db_New, $db, $offerID, $urlValidation);
	}
	$db->close();
	$db_New->close();
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
	$result2->free_result();
	$result2->close();
	$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, gender, happy FROM trainer_trades WHERE uniqueID = ?";
	$result = $db_New->prepare($query);
	$result->bind_param("s", $tradePokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $pokeGender, $pokeHoF);
	$result->fetch();
	$result->free_result();
	$result->close();
	$pokeNickname = stripslashes($pokeNickname);
	$genderName = get_Gender($pokeGender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "'.get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
	}
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
	$hallOfFame = '';
	if ($pokeHoF != 0) {
		$hallOfFame = ' <img src = "images/ribbon_smaller.png"/>HoF';
	}
	$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
	$result3 = $db->prepare($query);
	$result3->bind_param("i", $currentTrainer);
	$result3->execute();
	$result3->store_result();
	$result3->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
	$result3->fetch();
	$result3->free_result();
	$result3->close();
	?>
    <div class="block">
					<div class="title middle"><p>Trade Offer to <img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo ${avatar.$whichAvatar} ?>.png"> <?php echo $accNickname ?> for his <img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum ?>_0.png"> <?php echo $pokeNickname.$genderIcon ?> - Lvl (<?php echo $pokeLevel ?>) <b><?php echo $isShiny.$isHacked.$hallOfFame ?> </b></p></div>
					<div class="content middle">
						<p>Status: Waiting on Response - <a href="removeRequest2.php?<?php echo $urlValidation?>&requestID=<?php echo $requestID ?>&offerID=<?php echo $offerID ?>">Remove Offer</a></p>
    <?php
	$query2 = "SELECT requestPokeID FROM trade_request WHERE offerID = ?";
	$result2 = $db_New->prepare($query2);
	$result2->bind_param("s", $offerID);
	$result2->execute();
	$result2->store_result();
	$hmr = $result2->affected_rows;
	$result2->bind_result($requestPokeID);
	for ($b=1; $b<=$hmr; $b++) {
		$result2->fetch();
		$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, gender, happy FROM trainer_trades WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $requestPokeID);
		$result->execute();
		$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $pokeGender, $pokeHoF);
		$result->fetch();
		$result->free_result();
		$result->close();
		$genderName = get_Gender($pokeGender);
		$genderIcon = "";
		if ($genderName != "none") {
			$genderIcon = '<img src = "'.get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
		}
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
		$hallOfFame = '';
		if ($pokeHoF != 0) {
			$hallOfFame = ' <img src = "images/ribbon_smaller.png"/>HoF';
		}
		//$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
//		$result3 = $db->prepare($query);
//		$result3->bind_param("i", $currentTrainer);
//		$result3->execute();
//		$result3->store_result();
//		$result3->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
//		$result3->fetch();
//		$result3->close();
		?>
        <p><img src="<?php echo get_Graphic_Url() ?>/games/ptd/small/<?php echo $pokeNum ?>_0.png"> <?php echo $pokeNickname.$genderIcon ?> - Lvl (<?php echo $pokeLevel ?>) <b><?php echo $isShiny.$isHacked.$hallOfFame ?></b></p>
        <?php
	}
	$result2->free_result();
	$result2->close();
	
	 ?>
    </div>
	</div>
    <?php
}
?>
</body>
</html>