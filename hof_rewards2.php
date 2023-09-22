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
					$rewardsIHave = array();
					$query = "select whichReward from hof_rewards WHERE trainerID = ?";
					$result = $db2->prepare($query);
					$result->bind_param("i", $id);
					$result->execute();
					$result->store_result();
					$result->bind_result($whichReward);			
					if ($result->affected_rows) {
						for ($i=0; $i<$result->affected_rows; $i++) {
							$result->fetch();
							$rewardsIHave[$i] = $whichReward;
						}
					}
					$result->free_result();
					$result->close();
					function have_Reward($whichReward, $rewardList) {
						for ($i=0; $i<count($rewardList); $i++) {
							if ($rewardList[$i] == $whichReward) {
								return " - Already Redeemed";
							}
						}
						return "";
					}
					$db2->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Hall Of Fame- Rewards - <a href="hof2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else { ?>
                    <p>Once you have submitted enough pokemon you can receive Legendary Pokémon and other great prizes!</p>
						<p>You have submitted (<?php echo $totalRegularRegistered ?>) Regular Pokémon, (<?php echo $totalShinyRegistered ?>) Shiny Pokémon and (<?php echo $totalShadowRegistered ?>) Shadow Pokémon to the Hall of Fame, so far.</p>
                        <?php
							$regularCelebi = 1;
							$shinyCelebi = 2;
							$shadowCelebi = 3;
							$regularLugia = 4;
							$shinyLugia = 5;
							$shadowLugia = 6;
							$regularHooh = 7;
							$shinyHooh = 8;
							$shadowHooh = 9;
							$regularRegirock = 10;
							$shinyRegirock = 11;
							$shadowRegirock = 12;
							$regularRegice = 13;
							$shinyRegice = 14;
							$shadowRegice = 15;
							$regularRegisteel = 16;
							$shinyRegisteel = 17;
							$shadowRegisteel = 18;
							$regularLatias = 19;
							$shinyLatias = 20;
							$shadowLatias = 21;
							$regularLatios = 22;
							$shinyLatios = 23;
							$shadowLatios = 24;
							$regularKyogre = 25;
							$shinyKyogre = 26;
							$shadowKyogre = 27;
							$regularGroudon = 28;
							$shinyGroudon = 29;
							$shadowGroudon = 30;
							$regularDeoxys = 31;
							$shinyDeoxys = 32;
							$shadowDeoxys = 33;
							$regularRegigigas = 34;
							$shinyRegigigas = 35;
							$shadowRegigigas = 36;
							$regularCresselia = 37;
							$shinyCresselia = 38;
							$shadowCresselia = 39;
							$regularDarkrai = 40;
							$shinyDarkrai = 41;
							$shadowDarkrai = 42;
							$regularGenesect = 43;
							$shinyGenesect = 44;
							$shadowGenesect = 45;
							$regularManaphy = 46;
							$shinyManaphy = 47;
							$shadowManaphy = 48;
							$regularZygarde = 49;
							$shinyZygarde = 50;
							$shadowZygarde = 51;
							$regularDialga = 52;
							$shinyDialga = 53;
							$shadowDialga = 54;
							$regularPalkia = 55;
							$shinyPalkia = 56;
							$shadowPalkia = 57;
							$regularGiratina = 58;
							$shinyGiratina = 59;
							$shadowGiratina = 60;
							$regularZekrom = 61;
							$shinyZekrom = 62;
							$shadowZekrom = 63;
							$regularReshiram = 64;
							$shinyReshiram = 65;
							$shadowReshiram = 66;
							$regularUxie = 67;
							$shinyUxie = 68;
							$shadowUxie = 69;
							$regularMesprit = 70;
							$shinyMesprit = 71;
							$shadowMesprit = 72;
							$regularAzelf = 73;
							$shinyAzelf = 74;
							$shadowAzelf = 75;
							$regularShaymin = 76;
							$shinyShaymin = 77;
							$shadowShaymin = 78;
							$regularCobalion = 79;
							$shinyCobalion = 80;
							$shadowCobalion = 81;
							$regularTerrakion = 82;
							$shinyTerrakion = 83;
							$shadowTerrakion = 84;
							$regularVirizion = 85;
							$shinyVirizion = 86;
							$shadowVirizion = 87;
							$regularKeldeo = 88;
							$shinyKeldeo = 89;
							$shadowKeldeo = 90;
							$regularTornadus = 91;
							$shinyTornadus = 92;
							$shadowTornadus = 93;
							$regularThundurus = 94;
							$shinyThundurus = 95;
							$shadowThundurus = 96;
							$regularLandorus = 97;
							$shinyLandorus = 98;
							$shadowLandorus = 99;
							$regularMeloetta = 100;
							$shinyMeloetta = 101;
							$shadowMeloetta = 102;
							$regularDiancie = 103;
							$shinyDiancie = 104;
							$shadowDiancie = 105;
							$regularJirachi = 106;
							$shinyJirachi = 107;
							$shadowJirachi = 108;
							$regularRayquaza = 109;
							$shinyRayquaza = 110;
							$shadowRayquaza = 111;
							$regularHeatran = 112;
							$shinyHeatran = 113;
							$shadowHeatran = 114;
							$regularXerneas = 115;
							$shinyXerneas = 116;
							$shadowXerneas = 117;
							$regularYveltal = 118;
							$shinyYveltal = 119;
							$shadowYveltal = 120;
							$regularVictini = 121;
							$shinyVictini = 122;
							$shadowVictini = 123;
							$regularArceus = 124;
							$shinyArceus = 125;
							$shadowArceus = 126;
							$regularKyurem = 127;
							$shinyKyurem = 128;
							$shadowKyurem = 129;
							$regularHeatranF = 130;
							$shinyHeatranF = 131;
							$shadowHeatranF = 132;
						?>
						<p><img src="games/ptd/small/251_0.png"/>(3) Regular Celebi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularCelebi ?>">(50) Regular Pokémon</a><?php echo have_Reward($regularCelebi, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/251_1.png"/>(3) Shiny Celebi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyCelebi ?>">(50) Shiny Pokémon</a><?php echo have_Reward($shinyCelebi, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/251_0.png"/>(3) Shadow Celebi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowCelebi ?>">(50) Shadow Pokémon</a><?php echo have_Reward($shadowCelebi, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/249_0.png"/>(3) Regular Lugia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularLugia ?>">(75) Regular Pokémon</a><?php echo have_Reward($regularLugia, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/249_1.png"/>(3) Shiny Lugia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyLugia ?>">(75) Shiny Pokémon</a><?php echo have_Reward($shinyLugia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/249_0.png"/>(3) Shadow Lugia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowLugia ?>">(75) Shadow Pokémon</a><?php echo have_Reward($shadowLugia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/250_0.png"/>(3) Regular Hooh - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularHooh ?>">(100) Regular Pokémon</a><?php echo have_Reward($regularHooh, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/250_1.png"/>(3) Shiny Hooh - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyHooh ?>">(100) Shiny Pokémon</a><?php echo have_Reward($shinyHooh, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/250_0.png"/>(3) Shadow Hooh - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowHooh ?>">(100) Shadow Pokémon</a><?php echo have_Reward($shadowHooh, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/491_0.png"/>(3) Regular Darkrai - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularDarkrai ?>">(100) Regular Pokémon</a><?php echo have_Reward($regularDarkrai, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/491_1.png"/>(3) Shiny Darkrai - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyDarkrai ?>">(100) Shiny Pokémon</a><?php echo have_Reward($shinyDarkrai, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/491_0.png"/>(3) Shadow Darkrai - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowDarkrai ?>">(100) Shadow Pokémon</a><?php echo have_Reward($shadowDarkrai, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/377_0.png"/>(3) Regular Regirock - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularRegirock ?>">(125) Regular Pokémon</a><?php echo have_Reward($regularRegirock, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/377_1.png"/>(3) Shiny Regirock - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyRegirock ?>">(125) Shiny Pokémon</a><?php echo have_Reward($shinyRegirock, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/377_0.png"/>(3) Shadow Regirock - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowRegirock ?>">(125) Shadow Pokémon</a><?php echo have_Reward($shadowRegirock, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/378_0.png"/>(3) Regular Regice - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularRegice ?>">(150) Regular Pokémon</a><?php echo have_Reward($regularRegice, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/378_1.png"/>(3) Shiny Regice - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyRegice ?>">(150) Shiny Pokémon</a><?php echo have_Reward($shinyRegice, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/378_0.png"/>(3) Shadow Regice - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowRegice ?>">(150) Shadow Pokémon</a><?php echo have_Reward($shadowRegice, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/379_0.png"/>(3) Regular Registeel - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularRegisteel ?>">(175) Regular Pokémon</a><?php echo have_Reward($regularRegisteel, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/379_1.png"/>(3) Shiny Registeel - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyRegisteel ?>">(175) Shiny Pokémon</a><?php echo have_Reward($shinyRegisteel, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/379_0.png"/>(3) Shadow Registeel - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowRegisteel ?>">(175) Shadow Pokémon</a><?php echo have_Reward($shadowRegisteel, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/380_0.png"/>(3) Regular Latias - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularLatias ?>">(200) Regular Pokémon</a><?php echo have_Reward($regularLatias, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/380_1.png"/>(3) Shiny Latias - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyLatias ?>">(200) Shiny Pokémon</a><?php echo have_Reward($shinyLatias, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/380_0.png"/>(3) Shadow Latias - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowLatias ?>">(200) Shadow Pokémon</a><?php echo have_Reward($shadowLatias, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/381_0.png"/>(3) Regular Latios - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularLatios ?>">(200) Regular Pokémon</a><?php echo have_Reward($regularLatios, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/381_1.png"/>(3) Shiny Latios - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyLatios ?>">(200) Shiny Pokémon</a><?php echo have_Reward($shinyLatios, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/381_0.png"/>(3) Shadow Latios - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowLatios ?>">(200) Shadow Pokémon</a><?php echo have_Reward($shadowLatios, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/382_0.png"/>(3) Regular Kyogre - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularKyogre ?>">(225) Regular Pokémon</a><?php echo have_Reward($regularKyogre, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/382_1.png"/>(3) Shiny Kyogre - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyKyogre ?>">(225) Shiny Pokémon</a><?php echo have_Reward($shinyKyogre, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/382_0.png"/>(3) Shadow Kyogre - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowKyogre ?>">(225) Shadow Pokémon</a><?php echo have_Reward($shadowKyogre, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/383_0.png"/>(3) Regular Groudon - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularGroudon ?>">(250) Regular Pokémon</a><?php echo have_Reward($regularGroudon, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/383_1.png"/>(3) Shiny Groudon - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyGroudon ?>">(250) Shiny Pokémon</a><?php echo have_Reward($shinyGroudon, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/383_0.png"/>(3) Shadow Groudon - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowGroudon ?>">(250) Shadow Pokémon</a><?php echo have_Reward($shadowGroudon, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/386_0.png"/>(3) Regular Deoxys - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularDeoxys ?>">(275) Regular Pokémon</a><?php echo have_Reward($regularDeoxys, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/386_1.png"/>(3) Shiny Deoxys - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyDeoxys ?>">(275) Shiny Pokémon</a><?php echo have_Reward($shinyDeoxys, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/386_0.png"/>(3) Shadow Deoxys - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowDeoxys ?>">(275) Shadow Pokémon</a><?php echo have_Reward($shadowDeoxys, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/486_0.png"/>(3) Regular Regigigas - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularRegigigas ?>">(300) Regular Pokémon</a><?php echo have_Reward($regularRegigigas, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/486_1.png"/>(3) Shiny Regigigas - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyRegigigas ?>">(300) Shiny Pokémon</a><?php echo have_Reward($shinyRegigigas, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/486_0.png"/>(3) Shadow Regigigas - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowRegigigas ?>">(300) Shadow Pokémon</a><?php echo have_Reward($shadowRegigigas, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/488_0.png"/>(3) Regular Cresselia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularCresselia ?>">(310) Regular Pokémon</a><?php echo have_Reward($regularCresselia, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/488_1.png"/>(3) Shiny Cresselia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyCresselia ?>">(310) Shiny Pokémon</a><?php echo have_Reward($shinyCresselia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/488_0.png"/>(3) Shadow Cresselia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowCresselia ?>">(310) Shadow Pokémon</a><?php echo have_Reward($shadowCresselia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/649_0.png"/>(3) Regular Genesect - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularGenesect ?>">(325) Regular Pokémon</a><?php echo have_Reward($regularGenesect, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/649_1.png"/>(3) Shiny Genesect - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyGenesect ?>">(325) Shiny Pokémon</a><?php echo have_Reward($shinyGenesect, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/649_0.png"/>(3) Shadow Genesect - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowGenesect ?>">(325) Shadow Pokémon</a><?php echo have_Reward($shadowGenesect, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/490_0.png"/>(3) Regular Manaphy - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularManaphy ?>">(335) Regular Pokémon</a><?php echo have_Reward($regularManaphy, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/490_1.png"/>(3) Shiny Manaphy - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyManaphy ?>">(335) Shiny Pokémon</a><?php echo have_Reward($shinyManaphy, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/490_0.png"/>(3) Shadow Manaphy - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowManaphy ?>">(335) Shadow Pokémon</a><?php echo have_Reward($shadowManaphy, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/718_0.png"/>(3) Regular Zygarde - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularZygarde ?>">(350) Regular Pokémon</a><?php echo have_Reward($regularZygarde, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/718_1.png"/>(3) Shiny Zygarde - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyZygarde ?>">(350) Shiny Pokémon</a><?php echo have_Reward($shinyZygarde, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/718_0.png"/>(3) Shadow Zygarde - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowZygarde ?>">(350) Shadow Pokémon</a><?php echo have_Reward($shadowZygarde, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/483_0.png"/>(3) Regular Dialga - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularDialga ?>">(375) Regular Pokémon</a><?php echo have_Reward($regularDialga, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/483_1.png"/>(3) Shiny Dialga - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyDialga ?>">(375) Shiny Pokémon</a><?php echo have_Reward($shinyDialga, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/483_0.png"/>(3) Shadow Dialga - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowDialga ?>">(375) Shadow Pokémon</a><?php echo have_Reward($shadowDialga, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/484_0.png"/>(3) Regular Palkia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularPalkia ?>">(385) Regular Pokémon</a><?php echo have_Reward($regularPalkia, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/484_1.png"/>(3) Shiny Palkia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyPalkia ?>">(385) Shiny Pokémon</a><?php echo have_Reward($shinyPalkia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/484_0.png"/>(3) Shadow Palkia - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowPalkia ?>">(385) Shadow Pokémon</a><?php echo have_Reward($shadowPalkia, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/487_0.png"/>(3) Regular Giratina - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularGiratina ?>">(400) Regular Pokémon</a><?php echo have_Reward($regularGiratina, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/487_1.png"/>(3) Shiny Giratina - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyGiratina ?>">(400) Shiny Pokémon</a><?php echo have_Reward($shinyGiratina, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/487_0.png"/>(3) Shadow Giratina - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowGiratina ?>">(400) Shadow Pokémon</a><?php echo have_Reward($shadowGiratina, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/644_0.png"/>(3) Regular Zekrom - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularZekrom ?>">(410) Regular Pokémon</a><?php echo have_Reward($regularZekrom, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/644_1.png"/>(3) Shiny Zekrom - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyZekrom ?>">(410) Shiny Pokémon</a><?php echo have_Reward($shinyZekrom, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/644_0.png"/>(3) Shadow Zekrom - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowZekrom ?>">(410) Shadow Pokémon</a><?php echo have_Reward($shadowZekrom, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/643_0.png"/>(3) Regular Reshiram - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularReshiram ?>">(415) Regular Pokémon</a><?php echo have_Reward($regularReshiram, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/643_1.png"/>(3) Shiny Reshiram - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyReshiram ?>">(415) Shiny Pokémon</a><?php echo have_Reward($shinyReshiram, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/643_0.png"/>(3) Shadow Reshiram - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowReshiram ?>">(415) Shadow Pokémon</a><?php echo have_Reward($shadowReshiram, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/480_0.png"/>(3) Regular Uxie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularUxie ?>">(420) Regular Pokémon</a><?php echo have_Reward($regularUxie, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/480_1.png"/>(3) Shiny Uxie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyUxie ?>">(420) Shiny Pokémon</a><?php echo have_Reward($shinyUxie, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/480_0.png"/>(3) Shadow Uxie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowUxie ?>">(420) Shadow Pokémon</a><?php echo have_Reward($shadowUxie, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/481_0.png"/>(3) Regular Mesprit - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularMesprit ?>">(425) Regular Pokémon</a><?php echo have_Reward($regularMesprit, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/481_1.png"/>(3) Shiny Mesprit - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyMesprit ?>">(425) Shiny Pokémon</a><?php echo have_Reward($shinyMesprit, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/481_0.png"/>(3) Shadow Mesprit - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowMesprit ?>">(425) Shadow Pokémon</a><?php echo have_Reward($shadowMesprit, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/482_0.png"/>(3) Regular Azelf - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularAzelf ?>">(430) Regular Pokémon</a><?php echo have_Reward($regularAzelf, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/482_1.png"/>(3) Shiny Azelf - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyAzelf ?>">(430) Shiny Pokémon</a><?php echo have_Reward($shinyAzelf, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/482_0.png"/>(3) Shadow Azelf - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowAzelf ?>">(430) Shadow Pokémon</a><?php echo have_Reward($shadowAzelf, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/492_0.png"/>(3) Regular Shaymin - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularShaymin ?>">(440) Regular Pokémon</a><?php echo have_Reward($regularShaymin, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/492_1.png"/>(3) Shiny Shaymin - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyShaymin ?>">(440) Shiny Pokémon</a><?php echo have_Reward($shinyShaymin, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/492_0.png"/>(3) Shadow Shaymin - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowShaymin ?>">(440) Shadow Pokémon</a><?php echo have_Reward($shadowShaymin, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/638_0.png"/>(3) Regular Cobalion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularCobalion ?>">(450) Regular Pokémon</a><?php echo have_Reward($regularCobalion, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/638_1.png"/>(3) Shiny Cobalion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyCobalion ?>">(450) Shiny Pokémon</a><?php echo have_Reward($shinyCobalion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/638_0.png"/>(3) Shadow Cobalion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowCobalion ?>">(450) Shadow Pokémon</a><?php echo have_Reward($shadowCobalion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/639_0.png"/>(3) Regular Terrakion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularTerrakion ?>">(455) Regular Pokémon</a><?php echo have_Reward($regularTerrakion, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/639_1.png"/>(3) Shiny Terrakion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyTerrakion ?>">(455) Shiny Pokémon</a><?php echo have_Reward($shinyTerrakion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/639_0.png"/>(3) Shadow Terrakion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowTerrakion ?>">(455) Shadow Pokémon</a><?php echo have_Reward($shadowTerrakion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/640_0.png"/>(3) Regular Virizion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularVirizion ?>">(460) Regular Pokémon</a><?php echo have_Reward($regularVirizion, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/640_1.png"/>(3) Shiny Virizion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyVirizion ?>">(460) Shiny Pokémon</a><?php echo have_Reward($shinyVirizion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/640_0.png"/>(3) Shadow Virizion - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowVirizion ?>">(460) Shadow Pokémon</a><?php echo have_Reward($shadowVirizion, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/647_0.png"/>(3) Regular Keldeo - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularKeldeo ?>">(465) Regular Pokémon</a><?php echo have_Reward($regularKeldeo, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/647_1.png"/>(3) Shiny Keldeo - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyKeldeo ?>">(465) Shiny Pokémon</a><?php echo have_Reward($shinyKeldeo, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/647_0.png"/>(3) Shadow Keldeo - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowKeldeo ?>">(465) Shadow Pokémon</a><?php echo have_Reward($shadowKeldeo, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/641_0.png"/>(3) Regular Tornadus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularTornadus ?>">(470) Regular Pokémon</a><?php echo have_Reward($regularTornadus, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/641_1.png"/>(3) Shiny Tornadus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyTornadus ?>">(470) Shiny Pokémon</a><?php echo have_Reward($shinyTornadus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/641_0.png"/>(3) Shadow Tornadus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowTornadus ?>">(470) Shadow Pokémon</a><?php echo have_Reward($shadowTornadus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/642_0.png"/>(3) Regular Thundurus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularThundurus ?>">(475) Regular Pokémon</a><?php echo have_Reward($regularThundurus, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/642_1.png"/>(3) Shiny Thundurus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyThundurus ?>">(475) Shiny Pokémon</a><?php echo have_Reward($shinyThundurus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/642_0.png"/>(3) Shadow Thundurus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowThundurus ?>">(475) Shadow Pokémon</a><?php echo have_Reward($shadowThundurus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/645_0.png"/>(3) Regular Landorus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularLandorus ?>">(480) Regular Pokémon</a><?php echo have_Reward($regularLandorus, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/645_1.png"/>(3) Shiny Landorus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyLandorus ?>">(480) Shiny Pokémon</a><?php echo have_Reward($shinyLandorus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/645_0.png"/>(3) Shadow Landorus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowLandorus ?>">(480) Shadow Pokémon</a><?php echo have_Reward($shadowLandorus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/648_0.png"/>(3) Regular Meloetta - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularMeloetta ?>">(485) Regular Pokémon</a><?php echo have_Reward($regularMeloetta, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/648_1.png"/>(3) Shiny Meloetta - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyMeloetta ?>">(485) Shiny Pokémon</a><?php echo have_Reward($shinyMeloetta, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/648_0.png"/>(3) Shadow Meloetta - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowMeloetta ?>">(485) Shadow Pokémon</a><?php echo have_Reward($shadowMeloetta, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/719_0.png"/>(3) Regular Diancie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularDiancie ?>">(490) Regular Pokémon</a><?php echo have_Reward($regularDiancie, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/719_1.png"/>(3) Shiny Diancie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyDiancie ?>">(490) Shiny Pokémon</a><?php echo have_Reward($shinyDiancie, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/719_0.png"/>(3) Shadow Diancie - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowDiancie ?>">(490) Shadow Pokémon</a><?php echo have_Reward($shadowDiancie, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/385_0.png"/>(3) Regular Jirachi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularJirachi ?>">(495) Regular Pokémon</a><?php echo have_Reward($regularJirachi, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/385_1.png"/>(3) Shiny Jirachi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyJirachi ?>">(495) Shiny Pokémon</a><?php echo have_Reward($shinyJirachi, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/385_0.png"/>(3) Shadow Jirachi - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowJirachi ?>">(495) Shadow Pokémon</a><?php echo have_Reward($shadowJirachi, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/384_0.png"/>(3) Regular Rayquaza - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularRayquaza ?>">(500) Regular Pokémon</a><?php echo have_Reward($regularRayquaza, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/384_1.png"/>(3) Shiny Rayquaza - Only available from Adoption or trading with other trainers.</p>
                       <p><img src="games/ptd/small/384_0.png"/>(3) Shadow Rayquaza - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowRayquaza ?>">(500) Shadow Pokémon</a><?php echo have_Reward($shadowRayquaza, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/485_0.png"/>(3) Regular Heatran Male - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularHeatran ?>">(505) Regular Pokémon</a><?php echo have_Reward($regularHeatran, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/485_1.png"/>(3) Shiny Heatran Male - Only available from Adoption or trading with other trainers.</p>
                       <p><img src="games/ptd/small/485_0.png"/>(3) Shadow Heatran Male - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowHeatran ?>">(505) Shadow Pokémon</a><?php echo have_Reward($shadowHeatran, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/485_0.png"/>(3) Regular Heatran Female - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularHeatranF ?>">(505) Regular Pokémon</a><?php echo have_Reward($regularHeatranF, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/485_1.png"/>(3) Shiny Heatran Female - Only available from Adoption or trading with other trainers.</p>
                       <p><img src="games/ptd/small/485_0.png"/>(3) Shadow Heatran Female - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowHeatranF ?>">(505) Shadow Pokémon</a><?php echo have_Reward($shadowHeatranF, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/716_0.png"/>(3) Regular Xerneas - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularXerneas ?>">(510) Regular Pokémon</a><?php echo have_Reward($regularXerneas, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/716_1.png"/>(3) Shiny Xerneas - Only available from Adoption or trading with other trainers.</p>
                       <p><img src="games/ptd/small/716_0.png"/>(3) Shadow Xerneas - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowXerneas ?>">(510) Shadow Pokémon</a><?php echo have_Reward($shadowXerneas, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/717_0.png"/>(3) Regular Yveltal - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularYveltal ?>">(515) Regular Pokémon</a><?php echo have_Reward($regularYveltal, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/717_1.png"/>(3) Shiny Yveltal - Only available from Adoption or trading with other trainers.</p>
                       <p><img src="games/ptd/small/717_0.png"/>(3) Shadow Yveltal - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowYveltal ?>">(515) Shadow Pokémon</a><?php echo have_Reward($shadowYveltal, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/494_0.png"/>(3) Regular Victini - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularVictini ?>">(520) Regular Pokémon</a><?php echo have_Reward($regularVictini, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/494_1.png"/>(3) Shiny Victini - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyVictini ?>">(520) Shiny Pokémon</a><?php echo have_Reward($shinyVictini, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/494_0.png"/>(3) Shadow Victini - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowVictini ?>">(520) Shadow Pokémon</a><?php echo have_Reward($shadowVictini, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/646_0.png"/>(3) Regular Kyurem - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularKyurem ?>">(530) Regular Pokémon</a><?php echo have_Reward($regularKyurem, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/646_1.png"/>(3) Shiny Kyurem - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyKyurem ?>">(530) Shiny Pokémon</a><?php echo have_Reward($shinyKyurem, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/646_0.png"/>(3) Shadow Kyurem - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowKyurem ?>">(530) Shadow Pokémon</a><?php echo have_Reward($shadowKyurem, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/493_0.png"/>(3) Regular Arceus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $regularArceus ?>">(600) Regular Pokémon</a><?php echo have_Reward($regularArceus, $rewardsIHave) ?></p>
                        <p><img src="games/ptd/small/493_1.png"/>(3) Shiny Arceus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shinyArceus ?>">(600) Shiny Pokémon</a><?php echo have_Reward($shinyArceus, $rewardsIHave) ?></p>
                       <p><img src="games/ptd/small/493_0.png"/>(3) Shadow Arceus - <a href="hof_rewards_get2.php?<?php echo $urlValidation ?>&which=<?php echo $shadowArceus ?>">(600) Shadow Pokémon</a><?php echo have_Reward($shadowArceus, $rewardsIHave) ?></p>
              
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