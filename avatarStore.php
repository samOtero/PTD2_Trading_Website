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
					$result->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Avatar Store - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
						<p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins to use in the avatar store. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
						<p><a href="avatarStore_list.php?<?php echo $urlValidation ?>&list=5">Halloween Avatars</a></p>
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