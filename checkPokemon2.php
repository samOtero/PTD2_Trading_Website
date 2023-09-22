<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Your Profile";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	//Connect to original user database and confirm that their currentSave has not changed.
	$reason = get_Current_Save_Status($id, $currentSave);
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
					<div class="title"><p>Home - <a href="trading_account.php?Action=logged">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    	<p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
						<p><a href="adoption2.php?<?php echo $urlValidation?>">Pokémon Adoption</a> - Adopt a shiny pokémon, both rare and legendary using your SnD Coins or Daily Coins. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a></p>
						<p><a href="dailyActivity2.php?<?php echo $urlValidation?>">Daily Activities</a> - Complete the daily activity to earn Daily Coins!</p>
                        <p><a href="breeding2.php?<?php echo $urlValidation?>">Breeding Center</a> - Breed your pokémon! Use items to increase the chance to get a Shiny or Shadow Egg!</p>
                         <p><a href="deevolve2.php?<?php echo $urlValidation?>">Deevolution Chamber</a> - Turn your pokemon back to its original stage and back to level 1 using your Daily Coins. (This also removes the Hall of Fame tag on that pokémon)</p>
                         <p><a href="createShiny2.php?<?php echo $urlValidation?>">Convert To Shiny</a> - Convert any of your regular pokémon into Shiny using your SnD Coins or Daily Coins. (This also removes the Hacked and Hall of Fame tag on that pokémon) <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a> </p>
                         <p><a href="createShadow2.php?<?php echo $urlValidation?>">Convert To Shadow</a> - Convert any of your regular pokémon into Shadow using your SnD Coins or Daily Coins. (This also removes the Hacked and Hall of Fame tag on that pokémon) <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a> </p>
                        <p><a href="itemStore2.php?<?php echo $urlValidation?>">Item Store</a> - Buy Items for your pokémon! Including Friendship Doll, Evolution Stones and Incense!</p>
                        <p><a href="Rewards2.php?<?php echo $urlValidation?>">Funding Rewards</a> - Watch videos and earn funding credit. Enter your code received from funding to redeem your rewards!</p>
                        <p><b>(New) </b><a href="transferTo32.php?<?php echo $urlValidation?>">Transfer to PTD3</a> - Transfer your pokémon to PTD3 to continue on your adventure!</p>
                        <p><a href="createTrade2.php?<?php echo $urlValidation?>">Create Trade</a> - Go here to select one of your pokémon and put him up for trade, other players will be able to request trades for your pokémon. Once you put your pokémon to trade you will not be able to use him in your game unless you call him back.</p>
						<p><a href="yourTrades2.php?<?php echo $urlValidation?>">Your Trade Request</a> - Go here to see if anybody has requested a trade with you or if any of your request have been accepted. You can also call back your pokémon if you don't wish to trade it anymore.</p>
						<p><a href="searchTrades2.php?<?php echo $urlValidation?>">Search Trades</a> - Go here to search for trades that other players have posted.</p>
                        <p><a href="latestTrades2.php?<?php echo $urlValidation?>">Latest Trades</a> - Go here to search the latest trades that other players have posted.</p>
						<p><a href="removeHack2.php?<?php echo $urlValidation?>">Remove Hacked Tag</a> - Go here to remove the Hacked Tag from your pokemon using your SnD Coins or Daily Coins. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Go here to find out how to get SnD Coins.</a> </p>
                        <p><a href="hof2.php?<?php echo $urlValidation?>">Hall Of Fame</a> - Check out the best trainer's collections and submit your own to see how you rank against the best! Can you catch em all?!</p>

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
$db_New = connect_To_ptd2_Trading();

$limit = 100;
$page = mysql_escape_string($_GET['page']);
if ($page) {
	$start = ($page -1) * $limit;
}else{
	$page = 1;
	$start = 0;
}

$queryTotal = "SELECT COUNT(*) FROM trainer_pickup WHERE currentTrainer = ?";
$resultTotal = $db_New->prepare($queryTotal);
$resultTotal->bind_param("i", $id);
$resultTotal->execute();
$resultTotal->store_result();
$resultTotal->bind_result($totalPickups);
$resultTotal->fetch();
$resultTotal->free_result();
$resultTotal->close();

if ($start > 0) {
	if ($totalPickups < $start) {
		$start = 0;
		$page = 1;
	}
}
if ($page == 0) {$page = 1;};
$lastpage = ceil($totalPickups/$limit);
$paginateText = 'Total Pickups: '.$totalPickups;
$didFirst = false;
if ($lastpage > 1) {
	$paginateText .= ' - Pages: ';
	 //if there is more than one page total
	//if ($page > 1) {//if you are not on the first page
		//if ($page > 2) {//if you are not right next to the first page
			//$paginateText .= 'First';
			//$didFirst = true;
		//}
		//if ($didFirst == true) {
			//$paginateText .= ' - ';
		//}
		//$paginateText .= 'Previous';
		//$didFirst = true;
	//}
	if ($page > 1) {
		$paginateText .= '<a href="checkPokemon2.php?live=true&page='.($page -1).'&'.$urlValidation.'">Prev</a>';
		$didFirst = true;
	}
	for ($counter = 1; $counter <= $lastpage; $counter++) {
		if ($didFirst == true) {
			$paginateText .= ' - ';
		}
		if ($counter == $page) {
			$paginateText .= $counter;
		}else{
			$paginateText .= '<a href="checkPokemon2.php?live=true&page='.$counter.'&'.$urlValidation.'">'.$counter.'</a>';
		}
		$didFirst = true;
	}
	if ($page < $lastpage) {
		$paginateText .= ' - ';
		$paginateText .= '<a href="checkPokemon2.php?live=true&page='.($page +1).'&'.$urlValidation.'">Next</a>';
		$didFirst = true;
	}
}
?>
<div class="block">
					<div class="content">
						<p><?php echo $paginateText?></p>
					</div>
				</div>
                <?php
				
$query = "SELECT num, lvl, exp, shiny, nickname, uniquePickupID, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pickup WHERE currentTrainer = ?  ORDER BY num, lvl LIMIT $start, $limit";
$result = $db_New->prepare($query);
$result->bind_param("i", $id);
$result->execute();
$result->store_result();
$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $pokeID, $myTag, $pokeM1, $pokeM2, $pokeM3, $pokeM4, $pokeGender, $pokeItem, $pokeHoF);
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
		$pokeNickname = $pokeNickname.$isHacked;
		pokeBoxPickup2($pokeNickname, $pokeLevel, $pokeShiny, $pokeM1, $pokeM2, $pokeM3, $pokeM4, $pokeNum, '', $pokeID, $whichProfile, $pokeGender, $pokeItem, $pokeHoF);
}
}
$result->close();
?>
				<?php }?>
				<div class="block">
					<div class="content">
						<p><?php echo $paginateText?></p>
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