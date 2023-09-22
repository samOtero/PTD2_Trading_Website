<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Adoption";
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
					<div class="title"><p>Pokémon Adoption - <a href="adoption.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason != "savedOutside") {
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
						$result->close();
					?>
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins to use in adopting a pokémon. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
                        <?php } else { ?>
                        <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                        <?php } ?>
					</div>
				</div>
				<?php
				if ($reason != "savedOutside") { 
$whichList = $_REQUEST['list'];
if ($whichList > 5) {
	$whichList = 10000;
}
$query = "select costInCoins from sndCoins_category WHERE catID = ?";
$result = $db->prepare($query);
$result->bind_param("i", $whichList);
$result->execute();
$result->store_result();
$result->bind_result($cost);
$result->fetch();
$result->close();
$query = "select extraInfo, invID from sndCoins_inventory WHERE category = ? AND whichGame = 'ptd'";
	$result = $db->prepare($query);
	$result->bind_param("i", $whichList);
	$result->execute();
	$result->store_result();
	$result->bind_result($extraInfo, $invID);
	$hm = $result->affected_rows;
	if ($hm == 0) {
		$result->close();
		?>
        <div class="block">
        <div class="content">
        <p>No pokemon found in this category.</p>
        </div>
        </div>
        <?php
	}else{
		for ($i=0; $i<$hm; $i++) {
			$result->fetch();
			$infoList = explode("|", $extraInfo);
			$who = $infoList[0];
			$isShiny = $infoList[1];
			$myLevel = $infoList[2];
			$move1 = $infoList[3];
			$move2 = $infoList[4];
			$move3 = $infoList[5];
			$move4 = $infoList[6];
			$nickname = $infoList[9];
			pokeBox($nickname, $myLevel, $infoList[1], $move1, $move2, $move3, $move4, $who, '<a href="adopt_me.php?'.$urlValidation.'&who='.$invID.'">Adopt me for ('.$cost.') coins</a>');
		}
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