<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Your Profile";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	include 'template/ptd1_check_Save.php';
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
					<div class="title"><p>Home - <a href="trading_account.php?Action=logged">Go Back</a></p></div>
					<div class="content">
                    <?php
					 if ($reason == "savedOutside") { ?>
                    	<p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
						<p><a href="adoption.php?<?php echo $urlValidation?>">Pokémon Adoption</a> - Adopt a shiny pokémon, both rare and legendary using your SnD Coins. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a></p>
						<p><a href="avatarStore.php?<?php echo $urlValidation?>">Avatar Store</a> - Buy different avatars using your SnD Coins. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a></p>
						<p><a href="dailyCode.php?<?php echo $urlValidation?>">Daily Gift</a> - Once a day you can participate and try to win rare prizes.</p>
						<p><a href="inventory.php?<?php echo $urlValidation?>">Inventory</a> - Check your items and change your avatar for this profile.</p>
						<p><a href="gameCorner_test.php?<?php echo $urlValidation?>">Game Corner</a> - Play the slots to earn rare avatars or rare pokémon with a chance for them to be shiny!</p>
                        <p><a href="transferTo2.php?<?php echo $urlValidation?>">Transfer to PTD 2</a> - Transfer your pokémon to PTD 2!</p>
						<p><b>(New!)</b><a href="transferTo3.php?<?php echo $urlValidation?>">Transfer to PTD 3</a> - Transfer your pokémon to PTD 3 and continue your adventure!</p>
						<p><a href="createTrade.php?<?php echo $urlValidation?>">Create Trade</a> - Go here to select one of your pokémon and put him up for trade, other players will be able to request trades for your pokémon. Once you put your pokémon to trade you will not be able to use him in your game unless you call him back.</p>
						<p><a href="yourTrades.php?<?php echo $urlValidation?>">Your Trade Request</a> - Go here to see if anybody has requested a trade with you or if any of your request have been accepted. You can also call back your pokémon if you don't wish to trade it anymore.</p>
						<p><a href="searchTrades.php?<?php echo $urlValidation?>">Search Trades</a> - Go here to search for trades that other players have posted.</p>
                        <p><a href="latestTrades.php?<?php echo $urlValidation?>">Latest Trades</a> - Go here to search for the latest trades that other players have posted.</p>
                        <p><a href="removeHack.php?<?php echo $urlValidation?>">Remove Hacked Tag</a> - Go here to remove the Hacked Tag from your pokemon.</p>
                        <p><a href="elite4fix.php?<?php echo $urlValidation?>">Elite 4 Black Screen Fix</a> - Go here here if your screen is going black when beating the elite 4, this will unlock the champion level for you.</p>
						 <?php }?>
                    </div>
				</div>
                 <?php if ($reason != "savedOutside") { ?>
				<div class="block">
					<div class="content">
						<p>You have the following pokémon to pick up:</p>
					</div>
				</div>

				<?php
$db_New = connect_To_Database_New();
$query = "SELECT num, lvl, exp, shiny, nickname, uniqueID, myTag, m1, m2, m3, m4 FROM trainer_pickup WHERE pickup = 1 AND currentTrainer = ?  ORDER BY num, lvl";
$result = $db_New->prepare($query);
$result->bind_param("i", $id);
$result->execute();
$result->store_result();
$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $pokeID, $myTag, $pokeM1, $pokeM2, $pokeM3, $pokeM4);
$hmp = $result->affected_rows;
if ($hmp == 0) {
	?>
    <div class="block">
					<div class="content">
						<p>No pokemon to pick up.</p>
					</div>
				</div>
<?php
}else{
for ($i=1; $i<=$hmp; $i++) {
$result->fetch();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = "(Hacked Version)";
		}
		$extraStuff = '<a href="acceptPickup.php?'.$urlValidation.'&pokeID='.$pokeID.'">Send To Profile</a>';
		$extraStuff = '';
		$pokeNickname = $pokeNickname.$isHacked;
		pokeBoxPickup($pokeNickname, $pokeLevel, $pokeShiny, $pokeM1, $pokeM2, $pokeM3, $pokeM4, $pokeNum, $extraStuff, $pokeID, $whichProfile);
}
}
$result->close();
?>
				<?php }?>
				
				
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>