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
					$totalRegularRegistered = 0;
					$totalShinyRegistered = 0;
					$totalShadowRegistered = 0;
					$db2 = connect_To_ptd2_Trading();
					$query = "select pokeTotal, ladderType from hof_ladder WHERE trainerID = ?";
					$result = $db2->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$result->bind_result($totalPoke, $ladderType);			
					if ($result->affected_rows) {
						for ($i=0; $i<$result->affected_rows; $i++) {
							$result->fetch();
							if ($ladderType == 0) {
								$totalRegularRegistered = $totalPoke;
							}else if ($ladderType == 1) {
								$totalShinyRegistered = $totalPoke;
							}else if ($ladderType == 2) {
								$totalShadowRegistered = $totalPoke;
							}
						}
					}
					$result->free_result();
					$result->close();
					$db2->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Hall Of Fame - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
                    <p>Welcome to the Hall Of Fame! Here you will be able to see the trainers with the best collections from around the world.</p>
						<p>You have submitted (<?php echo $totalRegularRegistered ?>) Regular Pokémon, (<?php echo $totalShinyRegistered ?>) Shiny Pokémon and (<?php echo $totalShadowRegistered ?>) Shadow Pokémon to the Hall of Fame, so far.</p>
						<p><a href="hof_list2.php?<?php echo $urlValidation ?>&list=0">View Top 500 Regular Pokémon Hall Of Fame Trainers</a></p>
                        <p><a href="hof_list2.php?<?php echo $urlValidation ?>&list=1">View Top 500 Shiny Pokémon Hall Of Fame Trainers</a></p>
                        <p><a href="hof_list2.php?<?php echo $urlValidation ?>&list=2">View Top 500 Shadow Pokémon Hall Of Fame Trainers</a></p>
                        <p>Search for a Trainer's Hall Of Fame list:</p>
<form id="form1" name="form1" method="post" action="hof_trainer2.php?<?php echo $urlValidation ?>">
  <p>Which List:
    <label>
      <input type="radio" name="list" id="Regular" value="0"  checked="checked"/>
      Regular
    </label>
    <label>
      <input name="list" type="radio" id="Shiny" value="1" />
      Shiny
    </label>
    <label>
      <input name="list" type="radio" id="Shadow" value="2" />
      Shadow
    </label>
  </p>
  <p>Trainer ID:
    <input type="text" name="who" id="who" />
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form>
                        <p><a href="hof_rewards2.php?<?php echo $urlValidation ?>">Collect Rewards</a> - The more Pokémon you submit the more legendary Pokémon rewards you can get!</p>
                        <p><a href="hof_submit2.php?<?php echo $urlValidation ?>">Submit your Pokémon</a> - Enter your Pokémon to increase your rank and qualify for more rewards! <b>Note: Once you submit your Pokémon they will get a Hall Of Fame ribbon and they won't be able to be submitted again by any player.</b></p>
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