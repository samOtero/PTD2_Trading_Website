<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Trade Created";
	$pageMenuset = "extended";
	require 'moveList.php';
	$pokeID = $_REQUEST['pokeID'];
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "go";
$query = "select whichDB from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($whichDB);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
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
					<div class="title"><p>Trade Created - <a href="createTrade.php?<?= $urlValidation?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
                $newCurrentSave = uniqid(true);
$query = "UPDATE poke_accounts SET currentSave = ? WHERE trainerID = ? AND currentSave = ?";
$result = $db->prepare($query);
$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
$result->execute();
if ($result->sqlstate=="00000") {
	$currentSave = $newCurrentSave;
	$result->close();
}else{
	$result->close();
	?>
    <div class="content">
		<p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
	</div>
 </div>
    <?php
	$reason = "error";
}
if ($reason == "go") {
$_SESSION['currentSave'] = $currentSave;
$dbActual = get_Pokemon_Database($whichDB, $db);
 ?>
 <?php 
	$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, ability, item, originalOwner, myTag FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $mSel, $ability, $item, $originalOwner, $myTag);
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
	$pokeNickname = strip_tags($pokeNickname);
	$result->close();
	$query = "DELETE FROM trainer_pokemons WHERE uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("i", $pokeID);
	$result->execute();
	if ($result->affected_rows) {
		$result->close();
		$db = connect_To_Database_New();
		$pokeUnique = uniqid(true);
		$newTime = date( 'Y-m-d');
		$query = "INSERT INTO trainer_trades (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, ability, mSel, item, uniqueID, originalTrainer, currentTrainer, myTag, lastTimeUsed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $db->prepare($query);
		$result->bind_param("iiiisiiiiiiisiiss", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $ability, $mSel, $item, $pokeUnique, $originalOwner, $id, $myTag, $newTime);
		$result->execute();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		?></div><?php
		pokeBox($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;");
		?>
        <div class="block">
        <div class="content">
		<p>Pokemon added to your Trade Request. Give this code to anybody you want to direct to this particular trade - <?= $pokeUnique?>. <a href="createTrade.php?<?= $urlValidation?>">Click here to go back.</a></p>
	</div>
 </div>
        <?php
		$result->close();
		$action = $_REQUEST['Action'];
		if ($action == "setup") {
			trade_Request_Setup($pokeUnique, $db);
		}
	}else {
		$result->close();
		?>
         <div class="content">
		<p>You cannot trade this pokemon. <a href="trading.php">Click here to go back.</a></p>
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
	function trade_Request_Setup($pokeUnique, $db) {
	 $madeRequest = false;
	 for ($i=1; $i<=6; $i++) {
		 $num = $_REQUEST['poke'.$i];
		 if ($num == -1) {
			 continue;
		 }
		 $level = $_REQUEST['level'.$i];
		 $levelComparison = $_REQUEST['levelComparison'.$i];
		 $shiny = $_REQUEST['type'.$i];
	 $query = "INSERT INTO trade_wants (tradePokeID, num, level, levelComparison, shiny) VALUES (?,?,?,?,?)";
		$result = $db->prepare($query);
		$result->bind_param("siiii", $pokeUnique, $num, $level, $levelComparison, $shiny);
		$result->execute();
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
			$madeRequest = true;
			?>
            <div class="block">
         <div class="content">
		<p>You made the following request:</p>
        <?php
		}
		echo '<p>'.$poke.' - Lvl ('.$myLevel.') '.$isShiny.'</p>';
	 }
	 if (!$madeRequest) {
		 ?>
         <div class="block">
         <div class="content">
		<p>You made no request for this trade.</p>
        <?php
	 }
	 ?>
	</div>
 </div>
        <?php
 }
?>
</body>
</html>