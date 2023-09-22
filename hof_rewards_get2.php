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
	require 'moveList.php';
	include 'breedingList.php';
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
					$db2->close();
					function have_Reward($whichReward, $rewardList) {
						for ($i=0; $i<count($rewardList); $i++) {
							if ($rewardList[$i] == $whichReward) {
								return true;
							}
						}
						return false;
					}
					function have_Requirement($whichList, $howMany, $db2, $id) {
						$returnValue = false;
						$query = "select pokeTotal from hof_ladder WHERE trainerID = ? AND ladderType = ?";
						$result = $db2->prepare($query);
						$result->bind_param("ii", $id, $whichList);
						$result->execute();
						$result->store_result();
						$result->bind_result($pokeTotal);			
						if ($result->affected_rows) {
							$result->fetch();
							if ($pokeTotal >= $howMany) {
								$returnValue = true;
							}
						}
						$result->free_result();
						$result->close();
						return $returnValue;
					}
					function add_Reward($whichReward, $db2, $id) {
						$pokeNum = 251;
						$pokeShiny = 0;
						$pokeLevel = 1;
						$pokeGender = -1;
						$pokeExp = 0;
						$pokeNickname = "none";
						$item = 0;
						$originalOwner = -$id;
						$myTag = "n";
						if ($whichReward <= 3) {
							$pokeNickname = "Celebi";
							$pokeNum = 251;
							$pokeGender = -1;
						}else if ($whichReward <= 6) {
							$pokeNickname = "Lugia";
							$pokeNum = 249;
							$pokeGender = -1;
						}else if ($whichReward <= 9) {
							$pokeNickname = "Hooh";
							$pokeNum = 250;
							$pokeGender = -1;
						}else if ($whichReward <= 12) {
							$pokeNickname = "Regirock";
							$pokeNum = 377;
							$pokeGender = -1;
						}else if ($whichReward <= 15) {
							$pokeNickname = "Regice";
							$pokeNum = 378;
							$pokeGender = -1;
						}else if ($whichReward <= 18) {
							$pokeNickname = "Registeel";
							$pokeNum = 379;
							$pokeGender = -1;
						}else if ($whichReward <= 21) {
							$pokeNickname = "Latias";
							$pokeNum = 380;
							$pokeGender = 2;
						}else if ($whichReward <= 24) {
							$pokeNickname = "Latios";
							$pokeNum = 381;
							$pokeGender = 1;
						}else if ($whichReward <= 27) {
							$pokeNickname = "Kyogre";
							$pokeNum = 382;
							$pokeGender = -1;
						}else if ($whichReward <= 30) {
							$pokeNickname = "Groudon";
							$pokeNum = 383;
							$pokeGender = -1;
						}else if ($whichReward <= 33) {
							$pokeNickname = "Deoxys";
							$pokeNum = 386;
							$pokeGender = -1;
						}else if ($whichReward <= 36) {
							$pokeNickname = "Regigigas";
							$pokeNum = 486;
							$pokeGender = -1;
						}else if ($whichReward <= 39) {
							$pokeNickname = "Cresselia";
							$pokeNum = 488;
							$pokeGender = 2;
						}else if ($whichReward <= 42) {
							$pokeNickname = "Darkrai";
							$pokeNum = 491;
							$pokeGender = -1;
						}else if ($whichReward <= 45) {
							$pokeNickname = "Genesect";
							$pokeNum = 649;
							$pokeGender = -1;
						}else if ($whichReward <= 48) {
							$pokeNickname = "Manaphy";
							$pokeNum = 490;
							$pokeGender = -1;
						}else if ($whichReward <= 51) {
							$pokeNickname = "Zygarde";
							$pokeNum = 718;
							$pokeGender = -1;
						}else if ($whichReward <= 54) {
							$pokeNickname = "Dialga";
							$pokeNum = 483;
							$pokeGender = -1;
						}else if ($whichReward <= 57) {
							$pokeNickname = "Palkia";
							$pokeNum = 484;
							$pokeGender = -1;
						}else if ($whichReward <= 60) {
							$pokeNickname = "Giratina";
							$pokeNum = 487;
							$pokeGender = -1;
						}else if ($whichReward <= 63) {
							$pokeNickname = "Zekrom";
							$pokeNum = 644;
							$pokeGender = -1;
						}else if ($whichReward <= 66) {
							$pokeNickname = "Reshiram";
							$pokeNum = 643;
							$pokeGender = -1;
						}else if ($whichReward <= 69) {
							$pokeNickname = "Uxie";
							$pokeNum = 480;
							$pokeGender = -1;
						}else if ($whichReward <= 72) {
							$pokeNickname = "Mesprit";
							$pokeNum = 481;
							$pokeGender = -1;
						}else if ($whichReward <= 75) {
							$pokeNickname = "Azelf";
							$pokeNum = 482;
							$pokeGender = -1;
						}else if ($whichReward <= 78) {
							$pokeNickname = "Shaymin";
							$pokeNum = 492;
							$pokeGender = -1;
						}else if ($whichReward <= 81) {
							$pokeNickname = "Cobalion";
							$pokeNum = 638;
							$pokeGender = -1;
						}else if ($whichReward <= 84) {
							$pokeNickname = "Terrakion";
							$pokeNum = 639;
							$pokeGender = -1;
						}else if ($whichReward <= 87) {
							$pokeNickname = "Virizion";
							$pokeNum = 640;
							$pokeGender = -1;
						}else if ($whichReward <= 90) {
							$pokeNickname = "Keldeo";
							$pokeNum = 647;
							$pokeGender = -1;
						}else if ($whichReward <= 93) {
							$pokeNickname = "Tornadus";
							$pokeNum = 641;
							$pokeGender = 1;
						}else if ($whichReward <= 96) {
							$pokeNickname = "Thundurus";
							$pokeNum = 642;
							$pokeGender = 1;
						}else if ($whichReward <= 99) {
							$pokeNickname = "Landorus";
							$pokeNum = 645;
							$pokeGender = 1;
						}else if ($whichReward <= 102) {
							$pokeNickname = "Meloetta";
							$pokeNum = 648;
							$pokeGender = -1;
						}else if ($whichReward <= 105) {
							$pokeNickname = "Diancie";
							$pokeNum = 719;
							$pokeGender = -1;
						}else if ($whichReward <= 108) {
							$pokeNickname = "Jirachi";
							$pokeNum = 385;
							$pokeGender = -1;
						}else if ($whichReward <= 111) {
							$pokeNickname = "Rayquaza";
							$pokeNum = 384;
							$pokeGender = -1;
						}else if ($whichReward <= 114) {
							$pokeNickname = "Heatran";
							$pokeNum = 485;
							$pokeGender = 1;
						}else if ($whichReward <= 117) {
							$pokeNickname = "Xerneas";
							$pokeNum = 716;
							$pokeGender = -1;
						}else if ($whichReward <= 120) {
							$pokeNickname = "Yveltal";
							$pokeNum = 717;
							$pokeGender = -1;
						}else if ($whichReward <= 123) {
							$pokeNickname = "Victini";
							$pokeNum = 494;
							$pokeGender = -1;
						}else if ($whichReward <= 126) {
							$pokeNickname = "Arceus";
							$pokeNum = 493;
							$pokeGender = -1;
						}else if ($whichReward <= 129) {
							$pokeNickname = "Kyurem";
							$pokeNum = 646;
							$pokeGender = -1;
						}else if ($whichReward <= 132) {
							$pokeNickname = "Heatran";
							$pokeNum = 485;
							$pokeGender = 2;
						}
						if ($whichReward == 1 || $whichReward == 4 || $whichReward == 7 || $whichReward == 10 || $whichReward == 13 || $whichReward == 16 || $whichReward == 19 || $whichReward == 22 || $whichReward == 25 || $whichReward == 28 || $whichReward == 31 || $whichReward == 34 || $whichReward == 37 || $whichReward == 40 || $whichReward == 43 || $whichReward == 46 || $whichReward == 49 || $whichReward == 52 || $whichReward == 55 || $whichReward == 58 || $whichReward == 61 || $whichReward == 64 || $whichReward == 67 || $whichReward == 70 || $whichReward == 73 || $whichReward == 76 || $whichReward == 79 || $whichReward == 82 || $whichReward == 85 || $whichReward == 88 || $whichReward == 91 || $whichReward == 94 || $whichReward == 97 || $whichReward == 100 || $whichReward == 103 || $whichReward == 106 || $whichReward == 109 || $whichReward == 112 || $whichReward == 115 || $whichReward == 118 || $whichReward == 121 || $whichReward == 124 || $whichReward == 127 || $whichReward == 130) {
							$pokeShiny = 0;
						}else if ($whichReward == 2 || $whichReward == 5 || $whichReward == 8 || $whichReward == 11 || $whichReward == 14 || $whichReward == 17 || $whichReward == 20 || $whichReward == 23 || $whichReward == 26 || $whichReward == 29 || $whichReward == 32 || $whichReward == 35 || $whichReward == 38 || $whichReward == 41 || $whichReward == 44 || $whichReward == 47 || $whichReward == 50 || $whichReward == 53 || $whichReward == 56 || $whichReward == 59 || $whichReward == 62 || $whichReward == 65 || $whichReward == 68 || $whichReward == 71 || $whichReward == 74 || $whichReward == 77 || $whichReward == 80 || $whichReward == 83 || $whichReward == 86 || $whichReward == 89 || $whichReward == 92 || $whichReward == 95 || $whichReward == 98 || $whichReward == 101 || $whichReward == 104 || $whichReward == 107 || $whichReward == 110 || $whichReward == 113 || $whichReward == 116 || $whichReward == 119 || $whichReward == 122 || $whichReward == 125 || $whichReward == 128 || $whichReward == 131) {
							$pokeShiny = 1;
						}else if ($whichReward == 3 || $whichReward == 6 || $whichReward == 9 || $whichReward == 12 || $whichReward == 15 || $whichReward == 18 || $whichReward == 21 || $whichReward == 24 || $whichReward == 27 || $whichReward == 30 || $whichReward == 33 || $whichReward == 36 || $whichReward == 39 || $whichReward == 42 || $whichReward == 45 || $whichReward == 48 || $whichReward == 51 || $whichReward == 54 || $whichReward == 57 || $whichReward == 60 || $whichReward == 63 || $whichReward == 66 || $whichReward == 69 || $whichReward == 72 || $whichReward == 75 || $whichReward == 78 || $whichReward == 81 || $whichReward == 84 || $whichReward == 87 || $whichReward == 90 || $whichReward == 93 || $whichReward == 96 || $whichReward == 99 || $whichReward == 102 || $whichReward == 105 || $whichReward == 108 || $whichReward == 111 || $whichReward == 114 || $whichReward == 117 || $whichReward == 120 || $whichReward == 123 || $whichReward == 126 || $whichReward == 129 || $whichReward == 132) {
							$pokeShiny = 2;
						}
						$transactionFlag = true;
						$db2->autocommit(false);
						$eggInfo = get_Egg_Info($pokeNum, 0, 0, $pokeNum, 0, 0, false);
						$m1 = $eggInfo[3];
						$m2 = $eggInfo[4];
						$m3 = $eggInfo[5];
						$m4 = $eggInfo[6];
						$query2 = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalTrainer, currentTrainer, myTag, gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						$result2 = $db2->prepare($query2);
						$result2->bind_param("iiiisiiiiiiisi", $pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $id, $myTag, $pokeGender);
						if(!$result2->execute()) {
							$transactionFlag = false;
						}
						if(!$result2->execute()) {
							$transactionFlag = false;
						}
						if(!$result2->execute()) {
							$transactionFlag = false;
						}
						$result2->close();
						
						$query2 = "INSERT INTO hof_rewards (trainerID, whichReward) VALUES (?,?)";
						$result2 = $db2->prepare($query2);
						$result2->bind_param("ii", $id, $whichReward);
						if(!$result2->execute()) {
							$transactionFlag = false;
						}
						$result2->close();
						if ($transactionFlag == false) {
							$db2->rollback();
						}else{
							$db2->commit();
						}
						$db2->autocommit(true);
						$db2->close();
						return $transactionFlag;
					}
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Hall Of Fame- Rewards - <a href="hof_rewards2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
                    <?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else {
						$whichReward = $_REQUEST['which'];
						$regularCelebi = 1;
						$shinyCelebi = 2;
						$shadowCelebi = 3;
							if ($whichReward < 1 || $whichReward > 132) {
								echo "<p>This reward does not exist.</p>";
							}else if (have_Reward($whichReward, $rewardsIHave)) {
								echo "<p>You have already redeemed this reward.</p>";
							}else{
								$db2 = connect_To_ptd2_Trading();
								$whichList = 0;
								$howMany = 50;
								if ($whichReward == 1) {
									$whichList = 0;
									$howMany = 50;
								}else if ($whichReward == 2) {
									$whichList = 1;
									$howMany = 50;
								}else if ($whichReward == 3) {
									$whichList = 2;
									$howMany = 50;
								}else if ($whichReward == 4) {
									$whichList = 0;
									$howMany = 75;
								}else if ($whichReward == 5) {
									$whichList = 1;
									$howMany = 75;
								}else if ($whichReward == 6) {
									$whichList = 2;
									$howMany = 75;
								}else if ($whichReward == 7) {
									$whichList = 0;
									$howMany = 100;
								}else if ($whichReward == 8) {
									$whichList = 1;
									$howMany = 100;
								}else if ($whichReward == 9) {
									$whichList = 2;
									$howMany = 100;
								}else if ($whichReward == 10) {
									$whichList = 0;
									$howMany = 125;
								}else if ($whichReward == 11) {
									$whichList = 1;
									$howMany = 125;
								}else if ($whichReward == 12) {
									$whichList = 2;
									$howMany = 125;
								}else if ($whichReward == 13) {
									$whichList = 0;
									$howMany = 150;
								}else if ($whichReward == 14) {
									$whichList = 1;
									$howMany = 150;
								}else if ($whichReward == 15) {
									$whichList = 2;
									$howMany = 150;
								}else if ($whichReward == 16) {
									$whichList = 0;
									$howMany = 175;
								}else if ($whichReward == 17) {
									$whichList = 1;
									$howMany = 175;
								}else if ($whichReward == 18) {
									$whichList = 2;
									$howMany = 175;
								}else if ($whichReward == 19) {
									$whichList = 0;
									$howMany = 200;
								}else if ($whichReward == 20) {
									$whichList = 1;
									$howMany = 200;
								}else if ($whichReward == 21) {
									$whichList = 2;
									$howMany = 200;
								}else if ($whichReward == 22) {
									$whichList = 0;
									$howMany = 200;
								}else if ($whichReward == 23) {
									$whichList = 1;
									$howMany = 200;
								}else if ($whichReward == 24) {
									$whichList = 2;
									$howMany = 200;
								}else if ($whichReward == 25) {
									$whichList = 0;
									$howMany = 225;
								}else if ($whichReward == 26) {
									$whichList = 1;
									$howMany = 225;
								}else if ($whichReward == 27) {
									$whichList = 2;
									$howMany = 225;
								}else if ($whichReward == 28) {
									$whichList = 0;
									$howMany = 250;
								}else if ($whichReward == 29) {
									$whichList = 1;
									$howMany = 250;
								}else if ($whichReward == 30) {
									$whichList = 2;
									$howMany = 250;
								}else if ($whichReward == 31) {
									$whichList = 0;
									$howMany = 275;
								}else if ($whichReward == 32) {
									$whichList = 1;
									$howMany = 275;
								}else if ($whichReward == 33) {
									$whichList = 2;
									$howMany = 275;
								}else if ($whichReward == 34) {
									$whichList = 0;
									$howMany = 300;
								}else if ($whichReward == 35) {
									$whichList = 1;
									$howMany = 300;
								}else if ($whichReward == 36) {
									$whichList = 2;
									$howMany = 300;
								}else if ($whichReward == 37) {
									$whichList = 0;
									$howMany = 310;
								}else if ($whichReward == 38) {
									$whichList = 1;
									$howMany = 310;
								}else if ($whichReward == 39) {
									$whichList = 2;
									$howMany = 310;
								}else if ($whichReward == 40) {
									$whichList = 0;
									$howMany = 100;
								}else if ($whichReward == 41) {
									$whichList = 1;
									$howMany = 100;
								}else if ($whichReward == 42) {
									$whichList = 2;
									$howMany = 100;
								}else if ($whichReward == 43) {
									$whichList = 0;
									$howMany = 325;
								}else if ($whichReward == 44) {
									$whichList = 1;
									$howMany = 325;
								}else if ($whichReward == 45) {
									$whichList = 2;
									$howMany = 325;
								}else if ($whichReward == 46) {
									$whichList = 0;
									$howMany = 335;
								}else if ($whichReward == 47) {
									$whichList = 1;
									$howMany = 335;
								}else if ($whichReward == 48) {
									$whichList = 2;
									$howMany = 335;
								}else if ($whichReward == 49) {
									$whichList = 0;
									$howMany = 350;
								}else if ($whichReward == 50) {
									$whichList = 1;
									$howMany = 350;
								}else if ($whichReward == 51) {
									$whichList = 2;
									$howMany = 350;
								}else if ($whichReward == 52) {
									$whichList = 0;
									$howMany = 375;
								}else if ($whichReward == 53) {
									$whichList = 1;
									$howMany = 375;
								}else if ($whichReward == 54) {
									$whichList = 2;
									$howMany = 375;
								}else if ($whichReward == 55) {
									$whichList = 0;
									$howMany = 385;
								}else if ($whichReward == 56) {
									$whichList = 1;
									$howMany = 385;
								}else if ($whichReward == 57) {
									$whichList = 2;
									$howMany = 385;
								}else if ($whichReward == 58) {
									$whichList = 0;
									$howMany = 400;
								}else if ($whichReward == 59) {
									$whichList = 1;
									$howMany = 400;
								}else if ($whichReward == 60) {
									$whichList = 2;
									$howMany = 400;
								}else if ($whichReward == 61) {
									$whichList = 0;
									$howMany = 410;
								}else if ($whichReward == 62) {
									$whichList = 1;
									$howMany = 410;
								}else if ($whichReward == 63) {
									$whichList = 2;
									$howMany = 410;
								}else if ($whichReward == 64) {
									$whichList = 0;
									$howMany = 415;
								}else if ($whichReward == 65) {
									$whichList = 1;
									$howMany = 415;
								}else if ($whichReward == 66) {
									$whichList = 2;
									$howMany = 415;
								}else if ($whichReward == 67) {
									$whichList = 0;
									$howMany = 420;
								}else if ($whichReward == 68) {
									$whichList = 1;
									$howMany = 420;
								}else if ($whichReward == 69) {
									$whichList = 2;
									$howMany = 420;
								}else if ($whichReward == 70) {
									$whichList = 0;
									$howMany = 425;
								}else if ($whichReward == 71) {
									$whichList = 1;
									$howMany = 425;
								}else if ($whichReward == 72) {
									$whichList = 2;
									$howMany = 425;
								}else if ($whichReward == 73) {
									$whichList = 0;
									$howMany = 430;
								}else if ($whichReward == 74) {
									$whichList = 1;
									$howMany = 430;
								}else if ($whichReward == 75) {
									$whichList = 2;
									$howMany = 430;
								}else if ($whichReward == 76) {
									$whichList = 0;
									$howMany = 440;
								}else if ($whichReward == 77) {
									$whichList = 1;
									$howMany = 440;
								}else if ($whichReward == 78) {
									$whichList = 2;
									$howMany = 440;
								}else if ($whichReward == 79) {
									$whichList = 0;
									$howMany = 450;
								}else if ($whichReward == 80) {
									$whichList = 1;
									$howMany = 450;
								}else if ($whichReward == 81) {
									$whichList = 2;
									$howMany = 450;
								}else if ($whichReward == 82) {
									$whichList = 0;
									$howMany = 455;
								}else if ($whichReward == 83) {
									$whichList = 1;
									$howMany = 455;
								}else if ($whichReward == 84) {
									$whichList = 2;
									$howMany = 455;
								}else if ($whichReward == 85) {
									$whichList = 0;
									$howMany = 460;
								}else if ($whichReward == 86) {
									$whichList = 1;
									$howMany = 460;
								}else if ($whichReward == 87) {
									$whichList = 2;
									$howMany = 460;
								}else if ($whichReward == 88) {
									$whichList = 0;
									$howMany = 465;
								}else if ($whichReward == 89) {
									$whichList = 1;
									$howMany = 465;
								}else if ($whichReward == 90) {
									$whichList = 2;
									$howMany = 465;
								}else if ($whichReward == 91) {
									$whichList = 0;
									$howMany = 470;
								}else if ($whichReward == 92) {
									$whichList = 1;
									$howMany = 470;
								}else if ($whichReward == 93) {
									$whichList = 2;
									$howMany = 470;
								}else if ($whichReward == 94) {
									$whichList = 0;
									$howMany = 475;
								}else if ($whichReward == 95) {
									$whichList = 1;
									$howMany = 475;
								}else if ($whichReward == 96) {
									$whichList = 2;
									$howMany = 475;
								}else if ($whichReward == 97) {
									$whichList = 0;
									$howMany = 480;
								}else if ($whichReward == 98) {
									$whichList = 1;
									$howMany = 480;
								}else if ($whichReward == 99) {
									$whichList = 2;
									$howMany = 480;
								}else if ($whichReward == 100) {
									$whichList = 0;
									$howMany = 485;
								}else if ($whichReward == 101) {
									$whichList = 1;
									$howMany = 485;
								}else if ($whichReward == 102) {
									$whichList = 2;
									$howMany = 485;
								}else if ($whichReward == 103) {
									$whichList = 0;
									$howMany = 490;
								}else if ($whichReward == 104) {
									$whichList = 1;
									$howMany = 490;
								}else if ($whichReward == 105) {
									$whichList = 2;
									$howMany = 490;
								}else if ($whichReward == 106) {
									$whichList = 0;
									$howMany = 495;
								}else if ($whichReward == 107) {
									$whichList = 1;
									$howMany = 495;
								}else if ($whichReward == 108) {
									$whichList = 2;
									$howMany = 495;
								}else if ($whichReward == 109) {
									$whichList = 0;
									$howMany = 500;
								}else if ($whichReward == 110) {
									$whichList = 1;
									$howMany = 10000;
								}else if ($whichReward == 111) {
									$whichList = 2;
									$howMany = 500;
								}else if ($whichReward == 112) {
									$whichList = 0;
									$howMany = 505;
								}else if ($whichReward == 113) {
									$whichList = 1;
									$howMany = 10000;
								}else if ($whichReward == 114) {
									$whichList = 2;
									$howMany = 505;
								}else if ($whichReward == 115) {
									$whichList = 0;
									$howMany = 510;
								}else if ($whichReward == 116) {
									$whichList = 1;
									$howMany = 10000;
								}else if ($whichReward == 117) {
									$whichList = 2;
									$howMany = 510;
								}else if ($whichReward == 118) {
									$whichList = 0;
									$howMany = 515;
								}else if ($whichReward == 119) {
									$whichList = 1;
									$howMany = 10000;
								}else if ($whichReward == 120) {
									$whichList = 2;
									$howMany = 515;
								}else if ($whichReward == 121) {
									$whichList = 0;
									$howMany = 520;
								}else if ($whichReward == 122) {
									$whichList = 1;
									$howMany = 520;
								}else if ($whichReward == 123) {
									$whichList = 2;
									$howMany = 520;
								}else if ($whichReward == 124) {
									$whichList = 0;
									$howMany = 600;
								}else if ($whichReward == 125) {
									$whichList = 1;
									$howMany = 600;
								}else if ($whichReward == 126) {
									$whichList = 2;
									$howMany = 600;
								}else if ($whichReward == 127) {
									$whichList = 0;
									$howMany = 530;
								}else if ($whichReward == 128) {
									$whichList = 1;
									$howMany = 530;
								}else if ($whichReward == 129) {
									$whichList = 2;
									$howMany = 530;
								}else if ($whichReward == 130) {
									$whichList = 0;
									$howMany = 505;
								}else if ($whichReward == 131) {
									$whichList = 1;
									$howMany = 10000;
								}else if ($whichReward == 132) {
									$whichList = 2;
									$howMany = 505;
								}
								if (!have_Requirement($whichList, $howMany, $db2, $id) && $id !== 1) { // && $id !== 1
									echo "<p>You don't meet the requirements for this reward.</p>";
								}else if (!add_Reward($whichReward, $db2, $id)) {
									echo "<p>An error has occurred. Please go back and try again.</p>";
								}else{
									echo "<p>Congratulations! Your reward has been sent to your pickup area!</p>";
								}
							}
						}
						?>
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