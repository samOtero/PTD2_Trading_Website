<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Inventory - Items";
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
					<div class="title"><p>Items - <a href="inventory.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                     <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    </div>
					</div>
                    <?php }else { ?>
                    	<p>Here is a list of your items from this profile. </p>
					</div>
					</div>
                    <?php 
					$query = "SELECT itemNum FROM trainer_items WHERE trainerID = ? AND whichProfile = ?";
					$result = $db->prepare($query);
					$result->bind_param("ii", $id, $whichProfile);
					$result->execute();
					$result->store_result();
					$hmp = $result->affected_rows;
					$result->bind_result($itemNum);
					if ($hmp == 0) {
						if ($story >= 21) {
							show_Item(7);
						}else{
							?>
							<div class="content">
                    <p>You have no items in this profile.</p>
                    </div>
                    <?php
						}
					}else{
						for ($i=1; $i<=$hmp; $i++) {
							$result->fetch();
							show_Item($itemNum);
						}
						if ($story >= 21) {
							show_Item(7);
						}
					}
					$result->close();
				}?>				
			</td>
		</tr>
	</table>
</div>
<?php
	function show_Item($itemNum) {
		$itemName = "";
		$itemDescription = "";
		if ($itemNum == 1) {
			$itemName = "Moon Stone";
			$itemDescription = "A peculiar stone that makes certain species of Pokémon evolve. It is as black as the night sky.";
		}else if ($itemNum == 2) {
			$itemName = "Leaf Stone";
			$itemDescription = "A peculiar stone that makes certain species of Pokémon evolve. It has a leaf pattern.";
		}else if ($itemNum == 3) {
			$itemName = "Thunder Stone";
			$itemDescription = "A peculiar stone that makes certain species of Pokémon evolve. It has a thunderbolt pattern.";
		}else if ($itemNum == 4) {
			$itemName = "Water Stone";
			$itemDescription = "A peculiar stone that makes certain species of Pokémon evolve. It is a clear, light blue.";
		}else if ($itemNum == 5) {
			$itemName = "Fire Stone";
			$itemDescription = "A peculiar stone that makes certain species of Pokémon evolve. It is colored orange.";
		}else if ($itemNum == 6) {
			$itemName = "Old Rod";
			$itemDescription = "An old and beat-up fishing rod. Use it by any body of water to fish for wild Pokémon.";
		}else if ($itemNum == 7) {
			$itemName = "Silph Scope";
			$itemDescription = "A scope that makes unseeable Pokémon visible. It is made by Silph Co.";
		}else if ($itemNum == 8) {
			$itemName = "Super Rod";
			$itemDescription = "An awesome, high-tech fishing rod. Use it by any body of water to fish for wild aquatic Pokémon.";
		}else {
			return;
		}
		?>
        <div class="block item">
					<div class="image_holder">
						<div class="image_center">
							<img src="<?php echo get_Graphic_Url() ?>/trading_center/item/<?php echo $itemNum ?>.png" alt="" class="image" />
						</div>
					</div>
					<span class="name"><?php echo $itemName ?></span>
					<span class="total"></span>
					<div class="description">
						<?php echo $itemDescription ?>
					</div>
				</div>
        <?php
	}
	include 'template/footer.php';
?>
</body>
</html>