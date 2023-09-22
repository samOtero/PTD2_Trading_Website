<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Hall Of Fame";
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
					$whichList = $_REQUEST['list'];
					$whichTrainerID = $_REQUEST['who'];
					$listType = "";
					if ($whichList == 2) {
						$listType = "Shadow";
					}else if ($whichList == 1) {
						$listType = "Shiny";
					}else{
						$whichList = 0;
						$listType = "Regular";
					}
					$db = connect_To_Database();
					$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
					$result = $db->prepare($query);
					$result->bind_param("i", $whichTrainerID);
					$result->execute();
					$result->store_result();
					$result->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);	
					$result->fetch();
					$result->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Hall Of Fame - <?php echo '<img src="'.get_Graphic_Url().'/trading_center/avatar/'.${avatar.$whichAvatar}.'.png"> '.$accNickname?>'s <?php echo $listType ?> Pokémon List - <a href="hof_list2.php?<?php echo $urlValidation ?>&list=<?php echo $whichList ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { 
					?>
                    <p>Here you will see a list of this Trainer's <?php echo $listType ?> Pokémon collection.</p>
                        </div>
                        </div>
                        <div class="block">
						<div class="content">
                        <?php 
						$db2 = connect_To_ptd2_Trading();
						$query2 = "select pokeNum from hofRecords WHERE trainerID = ? AND pokeType = ? ORDER BY pokeNum";
						$result2 = $db2->prepare($query2);						
						$result2->bind_param("ii", $whichTrainerID, $whichList);
						$result2->execute();
						$result2->store_result();
						$result2->bind_result($pokeNum);
						if ($result2->affected_rows) {
							echo '<p>';
							$pokeType = 0;
							if ($whichList == 1) {
								$pokeType = 1;
							}
							for ($i=1; $i<=$result2->affected_rows; $i++) {
								$result2->fetch();
								echo '<img src="games/ptd/small/'.$pokeNum.'_'.$pokeType.'.png"/>';
							}
							echo '</p>';
						}else{
							echo '<p>No Pokémon registered in the Hall Of Fame found for this trainer in this category.</p>';
						}
						$result2->free_result();
						$result2->close();
						$db2->close();
						} ?>
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