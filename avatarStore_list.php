<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Avatar Store";
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
					<div class="title"><p>Avatar Store - <a href="avatarStore.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins to use in the avatar store. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
                        <?php
$whichList = $_REQUEST['list'];
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
		echo '<p>No avatars found in this category. Press back to continue.</p></div>';
	}else{
		?>
        </div>
        </div>
        <div class="avatars">
        <?php
		for ($i=0; $i<$hm; $i++) {
			$result->fetch();
			$infoList = explode("|", $extraInfo);
			$boyLink = $infoList[0];
			$girlLink = $infoList[1];
			$type = $infoList[2];
			$name = $infoList[3];
			$extra = '<a href="avatarStore_buy.php?'.$urlValidation.'&who='.$invID.'">Buy it for ('.$cost.') coins</a>';
			avatarBox($name, $boyLink, $girlLink, $extra);
		}
		?>
        <?php
	}
?>
</div>
 <?php } else { ?>
                        <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                        </div>
				</div>
                        <?php } ?>
					
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>