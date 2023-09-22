<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Deevolution Chamber";
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
					<div class="title"><p>Deevolution Chamber - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else {
					
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
					
					 ?>
					<div class="content">
                    	<p>You have (<?php echo $howManyDailyCoins ?>) Daily Coins to use in Deevolving your Pokemon.
						<p>Welcome... Welcome... Prof. Elm here with a new invention!</p>
                        <p><img src="images/elm_front.png" width="34"></p>
						<p>I call it the Deevolution Ray! If you... ummm... ZAP!... your pokemon with it, it will revert back to its first stage and back to level 1.</p>
                        
                      <p>The good news is.... what is the good news again? .... OH YEAH! The good news is that it can keep its attacks, it keep its name (Is this even good?), and it also removes the Hall of Fame tag. But let's keep that our little secret. *WINK WINK* .... okay... no more winking.</p>
                      <p>One last thing.. I forgot to mention... you can't deevolve pokemon that can't be breed... so keep that in mind.. I might fix the beam to allow it later... but anyways... ON TO THE RAY!</p></div></div>
                        
                        <?php
							$dbActual = get_PTD2_Pokemon_Database($whichDB);
							//$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? ".get_Legal_Query()." ORDER BY num, lvl";
							$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? ORDER BY num, lvl";
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
								$extra = '<a href="deevolve_actual2.php?'.$urlValidation.'&pokeID='.$pokeID.'">Deevolve for (15) Daily Coins. ZAP!</a>';
								pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeGender, $pokeItem, $pokeHoF);
							}
							}
							$result->free_result();
							$result->close();
							$dbActual->close();
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