<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Item Store";
	$pageMenuset = "extended";
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
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				function check_Value_Item($db_New, $id, $whichProfile, $whichExtra) {
					$value = false;
					if (get_Extra_Value($db_New, $id, $whichProfile, $whichExtra) == "1") {
						$value = true;
					}
					return $value;
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if ($reason != "savedOutside") {
					$db = connect_To_Database();
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
					$result->free_result();
					$result->close();
					$db->close();
					
					$db2 = connect_To_ptd2_Story_Database();
					$query = "select howMany from dailyCoins WHERE trainerID = ?";
					$result = $db2->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$result->bind_result($howManyDailyCoins);			
					if ($result->affected_rows) {
						$result->fetch();
					}else{
						$howManyDailyCoins = 0;
					}
					$result->free_result();
					$result->close();
					$db2->close();
					$db_New = connect_To_ptd2_Story_Extra_Database();
					$haveMetalCoat = check_Value_Item($db_New, $id, $whichProfile, "18");
					$haveShinyStone = check_Value_Item($db_New, $id, $whichProfile, "65");
					$haveWaveIncense = check_Value_Item($db_New, $id, $whichProfile, "105");
					$haveSeaIncense = check_Value_Item($db_New, $id, $whichProfile, "106");
					$haveLaxIncense = check_Value_Item($db_New, $id, $whichProfile, "107");
					$haveFullIncense = check_Value_Item($db_New, $id, $whichProfile, "108");
					$haveLuckIncense = check_Value_Item($db_New, $id, $whichProfile, "109");
					$haveOddIncense = check_Value_Item($db_New, $id, $whichProfile, "110");
					$haveRockIncense = check_Value_Item($db_New, $id, $whichProfile, "111");
					$haveRoseIncense = check_Value_Item($db_New, $id, $whichProfile, "112");
					$haveUpgrade = check_Value_Item($db_New, $id, $whichProfile, "113");
					$haveDubiousDisc = check_Value_Item($db_New, $id, $whichProfile, "114");
					$haveElectirizer = check_Value_Item($db_New, $id, $whichProfile, "115");
					$haveMagmarizer = check_Value_Item($db_New, $id, $whichProfile, "116");
					$haveProtector = check_Value_Item($db_New, $id, $whichProfile, "117");
					$haveOvalStone = check_Value_Item($db_New, $id, $whichProfile, "118");
					$haveRazorClaw = check_Value_Item($db_New, $id, $whichProfile, "119");
					$haveRazorFang = check_Value_Item($db_New, $id, $whichProfile, "120");
					$havePureIncense = check_Value_Item($db_New, $id, $whichProfile, "122");
					$db_New->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Item Store - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins and (<?php echo $howManyDailyCoins ?>) Daily Coins to use in buying items. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more SnD coins.</a> <a href="dailyActivity2.php?<?php echo $urlValidation ?>">Click here to get more Daily Coins.</a></p>
                        	</div>
				</div>
						<?php 
						itemBox2(13, 20, 1, 1, 10, $urlValidation);
						itemBox2(14, 20, 1, 1, 10, $urlValidation);
						itemBox2(15, 20, 1, 1, 10, $urlValidation);
						itemBox2(16, 20, 1, 1, 10, $urlValidation);
						itemBox2(17, 20, 1, 1, 10, $urlValidation);
						itemBox2(18, 20, 1, 1, 10, $urlValidation);
						itemBox2(19, 20, 1, 1, 10, $urlValidation);
						itemBox2(20, 20, 1, 1, 10, $urlValidation);
						itemBox2(21, 20, 1, 1, 10, $urlValidation);
						itemBox2(22, 20, 1, 1, 10, $urlValidation);
						itemBox2(23, 20, 1, 1, 10, $urlValidation);
						itemBox2(24, 20, 1, 1, 10, $urlValidation);
						if ($haveMetalCoat == true) {
							itemBox2(25, 20, 1, 1, 10, $urlValidation);
						}
						itemBox2(26, 20, 1, 1, 10, $urlValidation);
						itemBox2(27, 20, 1, 1, 10, $urlValidation);
						if ($haveShinyStone == true) {
							itemBox2(34, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveWaveIncense == true) {
							itemBox2(54, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveSeaIncense == true) {
							itemBox2(55, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveLaxIncense == true) {
							itemBox2(56, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveFullIncense == true) {
							itemBox2(57, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveLuckIncense == true) {
							itemBox2(58, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveOddIncense == true) {
							itemBox2(59, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveRockIncense == true) {
							itemBox2(60, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveRoseIncense == true) {
							itemBox2(61, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveUpgrade == true) {
							itemBox2(62, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveDubiousDisc == true) {
							itemBox2(64, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveElectirizer == true) {
							itemBox2(65, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveMagmarizer == true) {
							itemBox2(66, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveProtector == true) {
							itemBox2(67, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveOvalStone == true) {
							itemBox2(68, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveRazorClaw == true) {
							itemBox2(69, 20, 1, 1, 10, $urlValidation);
						}
						if ($haveRazorFang == true) {
							itemBox2(70, 20, 1, 1, 10, $urlValidation);
						}
						if ($havePureIncense == true) {
							itemBox2(79, 20, 1, 1, 10, $urlValidation);
						}
					}
                        ?>
				
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>