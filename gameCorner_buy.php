<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Game Corner - Prize";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();

$query = "select c_story_".$whichProfile."_a from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($avatarList);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
if ($reason != "savedOutside") {
	 $query = "select howManyCoins from gameCorner WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("i", $id);
	$result->execute();
	$result->store_result();
	$result->bind_result($howManyCoins);			
	if ($result->affected_rows) {
		$result->fetch();
	}else{
		$needCoins = true;
	}
	if ($needCoins == true) {
		$reason = "needCoins";
	}else{
		$whichPokeBuy = $_REQUEST['who'];
		$cost = 0;
		$myLevel = 1;	
		$allow = 0;	
		$noMove = 0;
		$who = $charmander;
		$nicknamePoke = "Charmander";
		$bulbasaur = 1;
		$charmander = 4;
		$squirtle = 7;
		$caterpie = 10;
		$weedle = 13;
		$pidgey = 16;
		$rattata = 19;
		$spearow = 21;
		$ekans = 23;
		$pikachu = 25;
		$clefairy = 35;
		$abra = 63;
		$scyther = 123;
		$pinsir = 127;
		$porygon = 137;
		$dratini = 147;
		$raikou = 243;
		$entei = 244;
		$suicune = 245;
	
		$tackle = 1;
		$tail_whip = 3;
		$quick_attack = 4;
		$growl = 5;
		$scratch = 6;
		$string_shot = 7;
		$poison_sting = 8;
		$ember = 10;
		$bubble = 11;
		$focused_energy = 12;
		$bug_bite = 13;
		$vine_whip = 16;
		$bite = 19;
		$confusion = 20;
		$thundershock = 22;
		$thunder_wave = 23;
		$defense_curl = 25;
		$peck = 42;
		$leer = 43;
		$sing = 47;
		$pound = 48;
		$low_kick = 51;
		$leech_life = 54;
		$transform = 56;
		$reflect_type = 57;
		$wrap = 70;
		$fire_fang = 92;
		$roar = 101;
		$reversal = 104;
		$absorb = 107;
		$hypnosis = 110;
		$teleport = 116;
		$lick = 119;
		$lovely_kiss = 120;
		$powder_snow = 121;
		$ice_punch = 122;
		$splash = 141;
		$faint_attack = 176;
		$poison_fang = 188;
		$hydro_pump = 193;
		$barrier = 197;
		$thunder = 202;
		$fire_blast = 203;
		$blizzard = 204;
		$tri_attack = 251;
		$charge = 257;
		$conversion = 263;
		$vicegrip = 269;
		$shadow_rush = 368;
		$move1 = $noMove;
		$move2 = $noMove;
		$move3 = $noMove;
		$move4 = $noMove;
	
		$isShiny = 0;
		if ($whichPokeBuy == 1) {
			$who = $abra;
			$move1 = $teleport;
			$nicknamePoke = "Abra";
			$cost = 120;
		}else if ($whichPokeBuy == 2) {
			$who = $clefairy;
			$nicknamePoke = "Clefairy";
			$move1 = $pound;
			$move2 = $growl;
			$cost = 500;
		}else if ($whichPokeBuy == 3) {
			$who = $pinsir;
			$nicknamePoke = "Pinsir";
			$move1 = $vicegrip;
			$move2 = $focused_energy;
			$cost = 2500;
		}else if ($whichPokeBuy == 4) {
			$who = $dratini;
			$nicknamePoke = "Dratini";
			$move1 = $wrap;
			$move2 = $leer;
			$cost = 2800;
		}else if ($whichPokeBuy == 5) {
			$who = $scyther;
			$nicknamePoke = "Scyther";
			$move1 = $quick_attack;
			$move2 = $leer;
			$cost = 5500;
		}else if ($whichPokeBuy == 6) {
			$who = $porygon;
			$nicknamePoke = "Porygon";
			$move1 = $tackle;
			$move2 = $conversion;
			$cost = 6500;
		}else if ($whichPokeBuy == 7) {
			$who = $abra;
			$move1 = $teleport;
			$nicknamePoke = "Abra";
			$cost = 9000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 8) {
			$who = $clefairy;
			$nicknamePoke = "Clefairy";
			$move1 = $pound;
			$move2 = $growl;
			$cost = 15000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 9) {
			$who = $pinsir;
			$nicknamePoke = "Pinsir";
			$move1 = $vicegrip;
			$move2 = $focused_energy;
			$cost = 50000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 10) {
			$who = $dratini;
			$nicknamePoke = "Dratini";
			$move1 = $wrap;
			$move2 = $leer;
			$cost = 100000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 11) {
			$who = $scyther;
			$nicknamePoke = "Scyther";
			$move1 = $quick_attack;
			$move2 = $leer;
			$cost = 120000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 12) {
			$who = $porygon;
			$nicknamePoke = "Porygon";
			$move1 = $tackle;
			$move2 = $conversion;
			$cost = 150000;
			$isShiny = 1;
		}else if ($whichPokeBuy == 14) {
			$cost = 150000;
		}else if ($whichPokeBuy == 13) {
			$move1 = $shadow_rush;
			$isShiny = 2;
			$cost = 300000;
			$who = randomNonEvolved();
			$nicknamePoke = get_Nickname($who);
		}
		if ($isShiny == 0) {
			$shinyRandom = rand(1,100);
			if ($shinyRandom == 50) {
				$isShiny = 1;
			}
		}
		if ($cost == 0) {
			$reason = "error";
		}else{
			$gotPrize = true;
			if ($howManyCoins >= $cost) {
				$howManyCoins -= $cost;
				$query = "UPDATE gameCorner SET howManyCoins = ? WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("ii", $howManyCoins, $id);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$result->close();
				}else{
					$result->close();
					$reason = "error";
				}
				if ($reason != "error") {
					if ($whichPokeBuy < 14) {
						$db_New = connect_To_Database_New();
						$query = "INSERT INTO trainer_pickup (pickup, num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, item, ability, uniqueID, currentTrainer, originalTrainer) VALUES (1,?, ?, 0, ?, ?, ?, ?, ?, ?, 1, 0, 0, ?, ?, ?)";
						$result = $db_New->prepare($query);
						$uniqueID = uniqid(true);
						$result->bind_param("iiisiiiisii", $who, $myLevel, $isShiny, $nicknamePoke, $move1, $move2, $move3, $move4, $uniqueID, $id, $id);
						$result->execute();
						$result->close();
					}else if ($whichPokeBuy == 14) {
						if ($avatarList == "") {
							$avatarList = 12;
						}else{
							$avatarList = $avatarList."|12";
						}
					}
					$newCurrentSave = uniqid(true);
					$query = "UPDATE poke_accounts SET currentSave = ?, c_story_".$whichProfile."_a = ? WHERE trainerID = ? AND currentSave = ?";
					$result = $db->prepare($query);
					$result->bind_param("ssis", $newCurrentSave, $avatarList, $id, $currentSave);
					$result->execute();
					if ($result->sqlstate=="00000") {
						$currentSave = $newCurrentSave;
						$_SESSION['currentSave'] = $currentSave;
						$result->close();
					}else{
						$result->close();
						$reason = "error";
					 }
				}
   		}else {
	  		$gotPrize = false;
		}
  	}
 }
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
					<div class="title"><p>Game Corner - <a href="gameCorner_test.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
						<p>Welcome to the Game Corner! Now that you are part of Team Rocket you can access the fun of the slots and play to get Team Rocket Exclusive pokemon!</p>
						<p>You have <?php echo $howManyCoins ?> Casino Coins.</p>
					</div>
				</div>
                <?php if ($reason == "savedOutside") { ?>
                <div class="block">
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
				</div>
                <?php }else if ($reason == "error") { ?>
                <div class="block">
					<div class="content">
						 <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
					</div>
				</div>
                <?php }else if ($reason == "needCoins") { ?>
                <div class="block">
					<div class="content">
						 <p>You don't have any coins. <a href="trading.php">Click here to go back.</a></p>
					</div>
				</div>
                <?php }else if ($gotPrize == false) { ?>
                <div class="block">
					<div class="content">
						 <p>You don't have enough coins for this prize.</p>
					</div>
				</div>
                    <?php }else { ?>
                    <div class="block">
					<div class="title thin"><p>Congratulation! Your prize is the following:</p></div>
				</div>
                <?php
if ($whichPokeBuy < 14) {
			pokeBox($nicknamePoke, $myLevel, $isShiny, $move1, $move2, $move3, $move4, $who, "&nbsp;");
		?>
        <div class="block">
					<div class="content">
						<p>Pick it up in the Pokemon Center!</p>
					</div>
				</div>
  <?php
}else{
	?>
    <div class="avatars">
	<?php avatarBox("Duskull Costume", "b_15", "no", "&nbsp;"); ?>
    </div>
    <div class="block">
					<div class="content">
						<p>To use this avatar go to the Inventory page in the Pokemon Center.</p>
					</div>
				</div>
    <?php
}
					}
?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
	function randomNonEvolved() {
	$which = rand(1, 64);
	if ($which == 1) {
		return 4;
	}else if ($which == 2) {
		return 151;
	}else if ($which == 3) {
		return 10;
	}else if ($which == 4) {
		return 13;
	}else if ($which == 5) {
		return 16;
	}else if ($which == 6) {
		return 19;
	}else if ($which == 7) {
		return 21;
	}else if ($which == 8) {
		return 23;
	}else if ($which == 9) {
		return 25;
	}else if ($which == 10) {
		return 27;
	}else if ($which == 11) {
		return 29;
	}else if ($which == 12) {
		return 32;
	}else if ($which == 13) {
		return 35;
	}else if ($which == 14) {
		return 37;
	}else if ($which == 15) {
		return 39;
	}else if ($which == 16) {
		return 41;
	}else if ($which == 17) {
		return 77;
	}else if ($which == 18) {
		return 46;
	}else if ($which == 19) {
		return 48;
	}else if ($which == 20) {
		return 50;
	}else if ($which == 21) {
		return 52;
	}else if ($which == 22) {
		return 56;
	}else if ($which == 23) {
		return 58;
	}else if ($which == 24) {
		return 60;
	}else if ($which == 25) {
		return 102;
	}else if ($which == 26) {
		return 66;
	}else if ($which == 27) {
		return 69;
	}else if ($which == 28) {
		return 72;
	}else if ($which == 29) {
		return 74;
	}else if ($which == 30) {
		return 81;
	}else if ($which == 31) {
		return 113;
	}else if ($which == 32) {
		return 92;
	}else if ($which == 33) {
		return 96;
	}else if ($which == 34) {
		return 98;
	}else if ($which == 35) {
		return 1;
	}else if ($which == 36) {
		return 104;
	}else if ($which == 37) {
		return 106;
	}else if ($which == 38) {
		return 107;
	}else if ($which == 39) {
		return 116;
	}else if ($which == 40) {
		return 118;
	}else if ($which == 41) {
		return 115;
	}else if ($which == 42) {
		return 123;
	}else if ($which == 43) {
		return 111;
	}else if ($which == 44) {
		return 127;
	}else if ($which == 45) {
		return 129;
	}else if ($which == 46) {
		return 131;
	}else if ($which == 47) {
		return 133;
	}else if ($which == 48) {
		return 137;
	}else if ($which == 49) {
		return 143;
	}else if ($which == 50) {
		return 147;
	}else if ($which == 51) {
		return 86;
	}else if ($which == 52) {
		return 142;
	}else if ($which == 53) {
		return 140;
	}else if ($which == 54) {
		return 138;
	}else if ($which == 55) {
		return 150;
	}else if ($which == 56) {
		return 54;
	}else if ($which == 57) {
		return 90;
	}else if ($which == 58) {
		return 132;
	}else if ($which == 59) {
		return 128;
	}else if ($which == 60) {
		return 79;
	}else if ($which == 61) {
		return 84;
	}else if ($which == 62) {
		return 114;
	}else if ($which == 63) {
		return 109;
	}else if ($which == 64) {
		return 125;
	}
}
function get_Nickname($who) {
	if ($who == 4) {
		return "Charmander";
	}else if ($who == 7) {
		return "Squirtle";
	}else if ($who == 10) {
		return "Caterpie";
	}else if ($who == 13) {
		return "Weedle";
	}else if ($who == 16) {
		return "Pidgey";
	}else if ($who == 19) {
		return "Rattata";
	}else if ($who == 21) {
		return "Spearow";
	}else if ($who == 23) {
		return "Ekans";
	}else if ($who == 25) {
		return "Pikachu";
	}else if ($who == 27) {
		return "Sandshrew";
	}else if ($who == 29) {
		return "Nidoran F";
	}else if ($who == 32) {
		return "Nidoran M";
	}else if ($who == 35) {
		return "Clefairy";
	}else if ($who == 37) {
		return "Vulpix";
	}else if ($who == 39) {
		return "Jigglypuff";
	}else if ($who == 41) {
		return "Zubat";
	}else if ($who == 43) {
		return "Oddish";
	}else if ($who == 46) {
		return "Paras";
	}else if ($who == 48) {
		return "Venonat";
	}else if ($who == 50) {
		return "Diglett";
	}else if ($who == 52) {
		return "Meowth";
	}else if ($who == 54) {
		return "Psyduck";
	}else if ($who == 56) {
		return "Mankey";
	}else if ($who == 58) {
		return "Growlithe";
	}else if ($who == 60) {
		return "Poliwag";
	}else if ($who == 63) {
		return "Abra";
	}else if ($who == 66) {
		return "Machop";
	}else if ($who == 69) {
		return "Bellsprout";
	}else if ($who == 72) {
		return "Tentacool";
	}else if ($who == 74) {
		return "Geodude";
	}else if ($who == 77) {
		return "Ponyta";
	}else if ($who == 81) {
		return "Magnemite";
	}else if ($who == 83) {
		return "Farfetchd";
	}else if ($who == 88) {
		return "Grimer";
	}else if ($who == 90) {
		return "Shellder";
	}else if ($who == 92) {
		return "Gastly";
	}else if ($who == 95) {
		return "Onix";
	}else if ($who == 96) {
		return "Drowzee";
	}else if ($who == 98) {
		return "Krabby";
	}else if ($who == 100) {
		return "Voltorb";
	}else if ($who == 104) {
		return "Cubone";
	}else if ($who == 106) {
		return "Hitmonlee";
	}else if ($who == 107) {
		return "Hitmonchan";
	}else if ($who == 114) {
		return "Tangela";
	}else if ($who == 116) {
		return "Horsea";
	}else if ($who == 118) {
		return "Goldeen";
	}else if ($who == 120) {
		return "Staryu";
	}else if ($who == 122) {
		return "Mr. Mime";
	}else if ($who == 123) {
		return "Scyther";
	}else if ($who == 124) {
		return "Jynx";
	}else if ($who == 127) {
		return "Pinsir";
	}else if ($who == 129) {
		return "Magikarp";
	}else if ($who == 131) {
		return "Lapras";
	}else if ($who == 132) {
		return "Ditto";
	}else if ($who == 133) {
		return "Eevee";
	}else if ($who == 137) {
		return "Porygon";
	}else if ($who == 143) {
		return "Snorlax";
	}else if ($who == 147) {
		return "Dratini";
	}else if ($who == 151) {
		return "Mew";
	}else if ($who == 243) {
		return "Raikou";
	}else if ($who == 244) {
		return "Entei";
	}else if ($who == 245) {
		return "Suicune";
	}else if ($who == 494) {
		return "Victini";
	}else if ($who == 102) {
		return "Exeggcute";
	}else if ($who == 111) {
		return "Rhyhorn";
	}else if ($who == 113) {
		return "Chansey";
	}else if ($who == 115) {
		return "Kangaskhan";
	}else if ($who == 128) {
		return "Tauros";
	}else if ($who == 79) {
		return "Slowpoke";
	}else if ($who == 84) {
		return "Doduo";
	}else if ($who == 86) {
		return "Seel";
	}else if ($who == 126) {
		return "Magmar";
	}else if ($who == 138) {
		return "Omanyte";
	}else if ($who == 140) {
		return "Kabuto";
	}else if ($who == 142) {
		return "Aerodactyl";
	}else if ($who == 109) {
		return "Koffing";
	}else if ($who == 125) {
		return "Electabuzz";
	}else if ($who == 150) {
		return "Mewtwo";
	}else if ($who == 1) {
		return "Bulbasaur";
	}
}
?>
</body>
</html>