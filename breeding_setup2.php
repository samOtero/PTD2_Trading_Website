<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Breeding Center Setup";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'breedingList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	$whichDB = $profileInfo[5];
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
					<div class="title"><p>Breeding Center Setup - <a href="breeding2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Thanks for visiting the Breeding Center!</p>
						<p>Here you can leave two compatible Pokemon and they will leave you an egg! Visit the egg once a day, every day, until it hatches!</p>
                        <p>Having the parents be shiny/shadow and using certain items you can buy in the item store will increase the chance that the egg is shiny/shadow depending on the combination that you use.<br>
<strong>(Note: Items that increase shadow/shiny chances will be consumed in the breeding process.)</strong></p>
                        
                        <?php
						$db2 = connect_To_ptd2_Story_Database();
						$query = "select usedOn, howMany from breeding_stable WHERE trainerID = ?";
						$result = $db2->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($lastTimeUsed, $howManyVisits);
						$moveOn = false;		
						if ($result->affected_rows) {
							echo '<p><strong>Stable Status:</strong> Full! You cannot start breeding a new egg at this time. Go back.</p>';
							$moveOn = true;
							$result->fetch();
						}
						$result->free_result();
						$result->close();
						$db2->close();
                        if ($moveOn == false) {
							echo '<p><strong>First Step:</strong> Choose the first Pokemon for the breeding. This Pokemon is usually a Female and often determines the species of the egg that is hatched, except when Ditto is used as the first Pokemon.</strong></p></div></div>';
							$dbActual = get_PTD2_Pokemon_Database($whichDB);
							$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? AND ((gender = 2 ".get_Legal_Query().") OR num = 132) ORDER BY num, lvl";
							//echo $query;
							$result = $dbActual->prepare($query);
							$result->bind_param("ii", $id, $whichProfile);
							$result->execute();
							$result->store_result();
							$hmp = $result->affected_rows;
							$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeHoF);
							if ($hmp == 0) {
								echo '<div class="block"><div class="content"><p>You have no Pokemon that fit this criteria. Go back and catch or trade for some Pokemon.</p></div></div>';	
							}else{
							for ($i=1; $i<=$hmp; $i++) {
								$result->fetch();
								$isHacked = "";
								if ($myTag == "h") {
									$isHacked = " (Hacked Version)";
								}
								$pokeNickname = stripslashes($pokeNickname).$isHacked;
								$extra = '<a href="breeding_setup_male2.php?'.$urlValidation.'&pokeID='.$pokeID.'">Pick Me as the First Pokemon</a>';
								pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeGender, $pokeItem, $pokeHoF);
							}
							}
							$result->free_result();
							$result->close();
							$dbActual->close();
						}
                        ?>
                        
					</div>
				</div>
                <?php 
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