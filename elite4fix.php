<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Elite 4 Fix ";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();

$query = "select a_story_".$whichProfile." from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($story);			
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
					<div class="title"><p>Elite 4 Fix  - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else if ($story < 36) { ?>
                    <p>You must reach the elite 4 before you can use this fix.</p>
                    <?php }else if ($story >= 40) { ?>
                    <p>You already have the champion level unlocked.</p>
                    <?php }else { 
					$newStory = 40;
					$query = "UPDATE poke_accounts SET a_story_".$whichProfile." = ? WHERE trainerID = ?";
					$result = $db->prepare($query);
					$result->bind_param("ii", $newStory, $id);
					$result->execute();
					if ($result->sqlstate=="00000") {
						$result->close();
						?><p>Fix completed! You have unlocked the champion level for this profile.</p><?php
					}else{
						$result->close();
						?><p>Error in database, please try again later.</p><?php
					}
					}?>
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