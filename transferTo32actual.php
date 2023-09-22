<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Transfer to PTD3 Completed";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'breedingList.php';
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
					<div class="title"><p>Transfer to PTD3 Completed - <a href="transferTo32.php?<?= $urlValidation?>">Go Back</a></p></div>
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
		<p>You cannot transfer this pokemon. <a href="trading.php">Click here to go back.</a></p>
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
	
		
	//Get moves from basic evolution
	$eggInfo = get_Egg_Info($pokeNum, 0, 0, $pokeNum, 0, 0, false);
	$m1 = $eggInfo[3];
	$m2 = $eggInfo[4];
	$m3 = $eggInfo[5];
	$m4 = $eggInfo[6];
		
	$extra1 = 0;
	$extra2 = 0;
	$query = "INSERT INTO ptdtrad_ptd2_trading.ptd3_pickup_pokes (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender, extra1, extra2, happy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result = $db->prepare($query);
	$result->bind_param("iiiisiiiiiiisiiii", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $myTag, $pokeGender, $extra1, $extra2, $pokeHoF);	
	if (!$result->execute()) {
		$transactionFlag = false;
	}
	$result->close();
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
	<p>Pokemon transfered to your PTD3 Pickup area! <a href="transferTo32.php?<?= $urlValidation?>">Click here to go back.</a></p>
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
?>
</body>
</html>