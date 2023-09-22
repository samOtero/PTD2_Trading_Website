<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Adoption";
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
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Pokémon Adoption - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins and (<?php echo $howManyDailyCoins ?>) Daily Coins to use in adopting a pokémon. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
						<p><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=8">View Common Shiny Pokémon List</a></p>
						<p><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=7">View Uncommon Shiny Pokémon List</a></p>
						<p><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=6">View Rare Shiny Pokémon List</a></p>
                        <p><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=9">View Legendary Shiny Pokémon/Cosmoids List</a></p>
                        <p>View Trainer Adoption List - These are adoptions created by other players. Usually you will find them cheaper here than in other list.</p>
                        <ul>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=1">Cost 1 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=7">Cost 2 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=6">Cost 3-4 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=2">Cost 5-9 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=3">Cost 10-14 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=4">Cost 15-19 Snd Coins</a></li>
                          <li><a href="adoption_list2.php?<?php echo $urlValidation ?>&list=10&costType=5">Cost 20 Snd Coins</a></li>
                        </ul>
                        <?php } ?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>