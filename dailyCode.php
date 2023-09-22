<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Daily Gift";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "success";
$query = "select a_story_".$whichProfile.", c_story_".$whichProfile."_a from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($levels, $avatarList);			
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
if ($reason == "success") {
	$query = "select lastTimeUsed from dailyCodes WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("i", $id);
	$result->execute();
	$result->store_result();
	$result->bind_result($lastTimeUsed);			
	if ($result->affected_rows) {
		$result->fetch();
	}else{
		$lastTimeUsed = -1;
	}
	$result->close();
	$canUse = true;
	$rightNow = strtotime(date( 'Y-m-d'));
	$yourLastTimeUsed = strtotime($lastTimeUsed);
	if ($lastTimeUsed != -1) {
		if ($yourLastTimeUsed >= $rightNow) {
			$canUse = false;
		}
	}
	$requirementUncommon = true;
	$requirementRare = true;
	if ($badges < 3) {
		$requirementUncommon = false;
	}
	if ($levels < 28) {
		$requirementRare = false;
	}
	$whichCode = $_REQUEST['whichDaily'];
  	if (!empty($whichCode)) {
		$reason = "success";
		if ($whichCode == "Common") {
			$money -= 1000;
	  	}else if ($whichCode == "Uncommon" && $requirementUncommon) {
			$money -= 10000;
	  	}else if ($whichCode == "Rare" && $requirementRare) {
			$money -= 100000;
	  	}else{
			$canUse = false;
		}
	  	$canDoCode = true;
	  	if ($money < 0) {
			  $canDoCode = false;
	  	}else if (!$canUse) {
			  $canDoCode = false;
	  	}else if ($whichCode == "Uncommon" && !$requirementUncommon) {
	  		  $canDoCode = false;
	  	}else if ($whichCode == "Rare" && !$requirementRare) {
	  		  $canDoCode = false;
	  	}
	  	if (!$canDoCode) {
			$reason = "notEligible";
	  	}
		if ($reason == "success") {
	  		$newTime = date( 'Y-m-d');
	  		if ($lastTimeUsed == -1) {
		  		$query = "INSERT INTO dailyCodes (trainerID, lastTimeUsed) VALUES (?,?)";
				$result = $db->prepare($query);
				$result->bind_param("is", $id, $newTime);
				$result->execute();
				$result->close();
	  		}else{
		  		$query = "UPDATE dailyCodes SET lastTimeUsed = ? WHERE trainerID = ?";
				$result = $db->prepare($query);
				$result->bind_param("si", $newTime, $id);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$result->close();
				}else{
					$result->close();
					$reason = "error";
				}
	  		}
			if ($reason == "success") {
  				$newCurrentSave = uniqid(true);
				$query = "UPDATE poke_accounts SET currentSave = ?, Money_".$whichProfile." = ?  WHERE trainerID = ? AND currentSave = ?";
				$result = $db->prepare($query);
				$result->bind_param("siis", $newCurrentSave, $money, $id, $currentSave);
				$result->execute();
				if ($result->sqlstate=="00000") {
					$currentSave = $newCurrentSave;
					$_SESSION['currentSave'] = $currentSave;
					$result->close();
				}else{
					$result->close();
					$reason == "error";
				}
				if ($reason == "success") {
					$ranNum = rand(1,100000);
	
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
					$abra = 63;
					$raikou = 243;
					$entei = 244;
					$suicune = 245;
	
					$noMove = 0;
					$tackle = 1;
					$tail_whip = 3;
					$growl = 5;
					$scratch = 6;
					$string_shot = 7;
					$poison_sting = 8;
					$ember = 10;
					$bubble = 11;
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
	
					$noShiny = 0;
					$yesShiny = 1;
	
					$myLevel = 1;
	
					$allow = 0;
	
					$move1 = $noMove;
					$move2 = $noMove;
					$move3 = $noMove;
					$move4 = $noMove;
	
					$isShiny = $noShiny;
	
					$whichPrize = "none";
					if ($whichCode == "Common") {
						if ($ranNum <= 1) {
							$whichPrize = "20snd";
						}else if ($ranNum <= 6) {
							$whichPrize = "10snd";
						}else if ($ranNum <= 16) {
							$whichPrize = "5snd";
						}else if ($ranNum <= 66) {
							$whichPrize = "1snd";
						}else if ($ranNum <= 50000) {
							$whichPrize = "HighCasino";
						}else if ($ranNum <= 100000) {
							$whichPrize = "lowCasino";
						}
					}else if ($whichCode == "Uncommon") {
						if ($ranNum <= 5) {
							$whichPrize = "20snd";
						}else if ($ranNum <= 10) {
							$whichPrize = "10snd";
						}else if ($ranNum <= 60) {
							$whichPrize = "5snd";
						}else if ($ranNum <= 160) {
							$whichPrize = "1snd";
						}else if ($ranNum <= 50000) {
							$whichPrize = "HighCasino";
						}else if ($ranNum <= 100000) {
							$whichPrize = "lowCasino";
						}
					}else if ($whichCode == "Rare") {
						if ($ranNum <= 10) {
							$whichPrize = "20snd";
						}else if ($ranNum <= 60) {
							$whichPrize = "10snd";
						}else if ($ranNum <= 160) {
							$whichPrize = "5snd";
						}else if ($ranNum <= 660) {
							$whichPrize = "1snd";
						}else if ($ranNum <= 50000) {
							$whichPrize = "HighCasino";
						}else if ($ranNum <= 100000) {
							$whichPrize = "lowCasino";
						}
  					}
					if ($whichPrize == "HighCasino" || $whichPrize == "lowCasino") {
						$casinoPrize = 0;
						if ($whichPrize == "HighCasino") {
							if ($whichCode == "Rare") {
								$casinoPrize = 100000;
							}else if ($whichCode == "Uncommon") {
								$casinoPrize = 25000;
							}else if ($whichCode == "Common") {
								$casinoPrize = 10000;
							}
						}else if ($whichPrize == "lowCasino") {
							if ($whichCode == "Rare") {
								$casinoPrize = 50000;
							}else if ($whichCode == "Uncommon") {
								$casinoPrize = 10000;
							}else if ($whichCode == "Common") {
								$casinoPrize = 1000;
							}
						}
						$needCoins = false;
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
							$howManyCoins = 100;
						}
						$howManyCoins += $casinoPrize;
						if ($needCoins == true) {
							$lastTimeUsed = date( 'Y-m-d');
							$query = "INSERT INTO gameCorner (trainerID, howManyCoins, lastTimeUsed) VALUES (?,?,?)";
							$result = $db->prepare($query);
							$result->bind_param("iis", $id, $howManyCoins, $lastTimeUsed);
							$result->execute();
							$result->close();
						}else {
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
						}
					}else {
						$howMany = 1;
						if ($whichPrize == "20snd") {
							$howMany = 20;
						}else if ($whichPrize == "10snd") {
							$howMany = 10;
						}else if ($whichPrize == "5snd") {
							$howMany = 5;
						}
						$query = "SELECT howManyCoins FROM  sndCoins WHERE trainerID = ?";
						$result = $db->prepare($query);
						$result->bind_param("i", $id);
						$result->execute();
						$result->store_result();
						$result->bind_result($howManyCoins);	
						$hmp = $result->affected_rows;
						$howTheyGotIt = 1;
						if ($hmp == 0) {
							$result->close();
							$query = "INSERT INTO sndCoins (trainerID, howManyCoins, howTheyGotIt) VALUES (?,?, ?)";
							$result = $db->prepare($query);
							$result->bind_param("iii", $id, $howMany, $howTheyGotIt);
							$result->execute();
							$result->close();
						}else{
							$result->fetch();
							$result->close();
							$howManyCoins += $howMany;
							$query = "UPDATE sndCoins SET howManyCoins = ?, howTheyGotIt = ? WHERE trainerID = ?";
							$result = $db->prepare($query);
							$result->bind_param("iii", $howManyCoins, $howTheyGotIt, $id);
							$result->execute();
							if ($result->sqlstate=="00000") {
								$result->close();
							}else{
								$result->close();
								$reason = "error";
							}
						}
					}
				}
			}
		}
  	}
}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Daily Gift - <a href="checkPokemon.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
					<div class="content">
<?php if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    </div>
				</div>
<?php }else if ($reason == "error") { ?>
                   <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
                    </div>
				</div>
<?php }else if ($reason == "notEligible") { ?>
                   <p>You are not eligible for this code.</p>
                    </div>
				</div>
<?php }else if ($reason == "success"){ ?>
						<p>You have <?php echo $money ?> pokedollars. <a href="dailyCode_Prizes.php?<?php echo $urlValidation ?>">View Prize List.</a></p>
<?php
	if (empty($whichCode)) {
		if (!$canUse) { ?>
			<p>You already bought a Gift today. Come back tomorrow for another Gift.</p>
        	</div>
		</div>
<?php
		}else{
?>
<p><a href="dailyCode.php?<?php echo $urlValidation ?>&whichDaily=Common">Buy a Common Daily Gift for 1,000 pokedollars.</a></p>
<?php
			if (!$requirementUncommon) {
?>
<p>You don't meet the requirements for the Uncommon Daily Gift in this profile.</p>
<?php
			}else{
?>
<p><a href="dailyCode.php?<?php echo $urlValidation ?>&whichDaily=Uncommon">Buy an Uncommon Daily Gift for 10,000 pokedollars.</a></p>
<?php
			}
			if (!$requirementRare) {
?>
<p>You don't meet the requirements for the Rare Daily Gift in this profile.</p>
<?php
			}else{
?>
<p><a href="dailyCode.php?<?php echo $urlValidation ?>&whichDaily=Rare">Buy a Rare Daily Gift for 100,000 pokedollars.</a></p>
<?php
			}
		}
	?>
    </div>
</div>
<?php                
	}else{
	echo '<p>Congratulation! Your daily Gift prize is the following:</p>';
		if ($whichPrize == "HighCasino" || $whichPrize == "lowCasino" || $whichPrize == "MedCasino") {
			echo '<p>Casino Coins ('.$casinoPrize.')</p>';
			echo '<p>See you back tomorrow!</p></div></div>';
		}else {
			echo '<p>('.$howMany.') SnD Coin! Use it to buy avatars in the avatar store or to adopt pokemon in the adoption center. You can also use the coin on any of our current and future games!</p>';
			echo '<p>See you back tomorrow!</p></div></div>';
		}
	}
} ?>
					
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
function secondsToTime($seconds)
	{
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
 
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
    return $obj;
}
?>
</body>
</html>