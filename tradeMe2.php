<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Trade Created";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	$pokeID = $_REQUEST['pokeID'];
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
	$whichDB = $profileInfo[5];
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
					<div class="title"><p>Trade Created - <a href="createTrade2.php?<?= $urlValidation?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
					$db = connect_To_Database();
					$updateResult = update_Current_Save($db, $id, $currentSave);
					$reason = $updateResult[0];
					$currentSave = $updateResult[1];
					$db->close();
					if ($reason == "error") {
						?>
   						 <div class="content">
						<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
                        </div>
                     </div>
    				<?php
					}
if ($reason == "success") {
$_SESSION['currentSave'] = $currentSave;
$dbActual = get_PTD2_Pokemon_Database($whichDB);
 ?>
 <?php 
	$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalOwner, myTag, gender, happy FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $myTag, $pokeGender, $pokeHoF);
	if ($result->affected_rows == 0) {
		$result->close();
		?>
    <div class="content">
		<p>You cannot trade this pokemon. <a href="trading.php">Click here to go back.</a></p>
	</div>
 </div>
    <?php
	}else{
	$result->fetch();
	$result->free_result();
	$pokeNickname = strip_tags($pokeNickname);
	$result->close();
	$dbActual->autocommit(false);
	$transactionFlag = true;
	$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("i", $pokeID);
	if (!$result->execute()) {
		$transactionFlag = false;
	}
	$result->close();
	$db = connect_To_ptd2_Trading();
	$db->autocommit(false);
	$pokeUnique = uniqid(true);
	$newTime = date( 'Y-m-d');
	$adoptNowPrice = 0;
	if (isset($_REQUEST['adoptNowPrice'])) {
		$adoptNowPrice = $_REQUEST['adoptNowPrice'];
	}
		
	//Combat against snd coins hack
	if ($adoptNowPrice < 0) {
		$adoptNowPrice = 0;
	}
		
	$query = "INSERT INTO trainer_trades (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, uniqueID, originalTrainer, currentTrainer, myTag, lastTimeUsed, gender, sndCost, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result = $db->prepare($query);
	$result->bind_param("iiiisiiiiisiissiii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $pokeUnique, $originalOwner, $id, $myTag, $newTime, $pokeGender, $adoptNowPrice, $pokeHoF);
	if (!$result->execute()) {
		$transactionFlag = false;
	}
	$result->close();
	$action = $_REQUEST['Action'];
	if ($action == "setup") {
		$content = trade_Request_Setup($pokeUnique, $db);
		if ($content == "fail") {
			$transactionFlag = false;
		}
	}
	if ($transactionFlag == true) {
		$db->commit();
		$dbActual->commit();
	}else{
		$db->rollback();
		$dbActual->rollback();
	}
	$db->autocommit(true);
	$dbActual->autocommit(true);
	$db->close();
	$dbActual->close();
	if ($transactionFlag == true) {
	$isHacked = "";
	if ($myTag == "h") {
		$isHacked = " - <b>(Hacked Version)</b>";
	}
	$pokeNickname = stripslashes($pokeNickname).$isHacked;
	?></div><?php
	pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", $pokeGender, $item, $pokeHoF);
	?>
	<div class="block">
	<div class="content">
	<p>Pokemon added to your Trade Request. Give this code to anybody you want to direct to this particular trade - <?= $pokeUnique?>. <a href="createTrade2.php?<?= $urlValidation?>">Click here to go back.</a></p>
</div>
</div>
	<?php
	echo $content;
	}else{
	?>
	 <div class="content">
	<p>An error occured. Please Try again.</p>
</div>
</div>
	<?php
	}
 }
				}
				}?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	function trade_Request_Setup($pokeUnique, $db) {
	 $madeRequest = false;
	 $content = "";
	 for ($z=1; $z<=3; $z++) {
		 for ($i=1; $i<=10; $i++) {
			 $num = $_REQUEST['poke'.$z.'_'.$i];
			 if ( empty($num) || $num == -1) {
				 continue;
			 }
			 $level = $_REQUEST['level'.$z.'_'.$i];
			 $levelComparison = $_REQUEST['levelComparison'.$z.'_'.$i];
			 $shiny = $_REQUEST['type'.$z.'_'.$i];
			 $gender = $_REQUEST['gender'.$z.'_'.$i];
		 $query = "INSERT INTO trade_wants (tradePokeID, num, level, levelComparison, shiny, gender, whichRequest) VALUES (?,?,?,?,?, ?, ?)";
			$result = $db->prepare($query);
			$result->bind_param("siiiiii", $pokeUnique, $num, $level, $levelComparison, $shiny, $gender, $z);
			if (!$result->execute()) {
				return "fail";
			}
			$shinyType = 0;
			$genderName = get_Gender($gender);
			$genderIcon = "";
			if ($genderName != "none") {
				$genderIcon = '<img src = "'. get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
			}
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
			if ($i == 1) {
				if ($madeRequest == false) {
					$madeRequest = true;
					$content .= '<div class="block">';
				 	$content .= '<div class="content">';
				}
				if ($z == 1) {
					$content .= '<div class="title"><p>You made the following request:</p></div>';
				}else{
					$content .= '<div class="title"><p>OR</p></div>';
				}
			}
			$content .= '<p>'.$poke.$genderIcon.' - Lvl ('.$myLevel.') '.$isShiny.'</p>';
		 }
	 }
	 if (!$madeRequest) {
		 $content .= '<div class="block"><div class="content"><p>You made no request for this trade.</p>';
	 }
	 $content .= '</div></div>';
	return $content;
 }
?>
</body>
</html>