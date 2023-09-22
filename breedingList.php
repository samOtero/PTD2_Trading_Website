<?php
require 'pokeList.php';
function get_Legal_Query() {
	$numList = get_Egg_Group_Undiscovered();
	$equals = "!=";
	$command = "AND";
	$legalQuery = get_Query_These_Nums($numList, $equals, $command);
	return $legalQuery;
}
function get_Egg_Info($maleNum, $maleShiny, $maleItem, $femaleNum, $femaleShiny, $femaleItem, $forBreeding=true, $move1M=0, $move2M=0, $move3M=0, $move4M=0, $move1F=0, $move2F=0, $move3F=0, $move4F=0) {
	//echo "$move1M$move2M$move3M$move4M$move1F$move2F$move3F$move4F";
	require 'pokeList.php';
	$usedMale = false;
	$shinyChance = 0;
	$shadowChance = 0;
	if ($maleItem == 15) {
		$shinyChance += 10;
	}else if ($maleItem == 16) {
		$shadowChance += 10;
	}
	if ($femaleItem == 15) {
		$shinyChance += 10;
	}else if ($femaleItem == 16) {
		$shadowChance += 10;
	}
	if ($maleShiny == 1) {
		$shinyChance += 4;
	}else if ($maleShiny == 2) {
		$shadowChance += 4;
	}
	if ($femaleShiny == 1) {
		$shinyChance += 4;
	}else if ($femaleShiny == 2) {
		$shadowChance += 4;
	}
	
	if (is_Same_Family($maleNum, $femaleNum) == true) {
		$shinyChance += 10;
		$shadowChance += 10;
	}
	if ($forBreeding == true && $femaleNum == $ditto) {
		$femaleNum = $maleNum;
		$usedMale = true;
	}
	if ($forBreeding == true && $femaleNum == $nidoran_f) {
		$isMaleOrFemale = rand(1,2);
		if ($isMaleOrFemale == 2) {
			$femaleNum = $nidoran_m;
		}
	}else if ($forBreeding == true && $femaleNum == $nidoran_m) {
		$isMaleOrFemale = rand(1,2);
		if ($isMaleOrFemale == 2) {
			$femaleNum = $nidoran_f;
		}
	}else if ($forBreeding == true && $femaleNum == $volbeat) {
		$isMaleOrFemale = rand(1,2);
		if ($isMaleOrFemale == 2) {
			$femaleNum = $illumise;
		}
	}else if ($forBreeding == true && $femaleNum == $illumise && $maleNum == $ditto) {
		$isMaleOrFemale = rand(1,2);
		if ($isMaleOrFemale == 2) {
			$femaleNum = $volbeat;
		}
	}
	$familyList = get_Family($femaleNum);
	$shinyRand = rand(1, 100);
	$shadowRand = rand(1,100);
	$pokeShiny = 0;
	if ($shinyRand <= $shinyChance) {
		$pokeShiny = 1;
	}else if ($shadowRand <= $shadowChance) {
		$pokeShiny = 2;
	}
	$pokeNum = $familyList[0];
	$moveList = get_New_Egg_Moves($pokeNum);
	$maleChance = rand(1,100);
	$maleChanceValue = 50;
	if ($pokeNum == $cleffa) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $corsola) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $vulpix) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $gothita) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $snubbull) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $growlithe) {
		$maleChanceValue = 75;
	}else if ($pokeNum == $abra) {
		$maleChanceValue = 75;
	}else if ($pokeNum == $machop) {
		$maleChanceValue = 75;
	}else if ($pokeNum == $chansey) {
		$maleChanceValue = 0;
	}else if ($pokeNum == $timburr) {
		$maleChanceValue = 75;
	}else if ($pokeNum == $makuhita) {
		$maleChanceValue = 75;
	}else if ($pokeNum == $togepi) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $archen) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $shieldon) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $combee) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $torchic) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $tepig) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $omanyte) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $kabuto) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $aerodactyl) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $snorlax) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $chameleaf) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $coalla) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $bubbull) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $mudkip) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $piplup) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $tirtouga) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $anorith) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $pansear) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $pansage) {//511
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $panpour) {//515
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $minccino) {//572
		$maleChanceValue = 25;
	}else if ($pokeNum == $litleo) {//667
		$maleChanceValue = 25;
	}else if ($pokeNum == $tyrunt) {//696
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $amaura) {//698
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $eevee) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $turtwig) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $snivy) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $fennekin) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $chespin) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $froakie) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $relicanth) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $lileep) {
		$maleChanceValue = 87.5;
	}else if ($pokeNum == $skitty) {
		$maleChanceValue = 25;
	}else if ($pokeNum == $glameow) {
		$maleChanceValue = 25;
	}
	$gender = 2;
	if ($maleChance <= $maleChanceValue) {
		$gender = 1;
	}
	if ($pokeNum == $nidoran_f) {
		$gender = 2;
	}else if ($pokeNum == $nidoran_m) {
		$gender = 1;
	}else if ($pokeNum == $sawk) {
		$gender = 1;
	}else if ($pokeNum == $rufflet) {
		$gender = 1;
	}else if ($pokeNum == $smoochum) {
		$gender = 2;
	}else if ($pokeNum == $miltank) {
		$gender = 2;
	}else if ($pokeNum == $beldum) {
		$gender = -1;
	}else if ($pokeNum == $golett) {
		$gender = -1;
	}else if ($pokeNum == $staryu) {
		$gender = -1;
	}else if ($pokeNum == $magnemite) {
		$gender = -1;
	}else if ($pokeNum == $rotom) {
		$gender = -1;
	}else if ($pokeNum == $cryogonal) {
		$gender = -1;
	}else if ($pokeNum == $voltorb) {
		$gender = -1;
	}else if ($pokeNum == $tyrogue) {
		$gender = 1;
	}else if ($pokeNum == $kangaskhan) {
		$gender = 2;
	}else if ($pokeNum == $tauros) {
		$gender = 1;
	}else if ($pokeNum == $porygon) {
		$gender = -1;
	}else if ($pokeNum == $missingno) {
		$gender = -1;
	}else if ($pokeNum == $celebi) {
		$gender = -1;
	}else if ($pokeNum == $lugia) {
		$gender = -1;
	}else if ($pokeNum == $ho_oh) {
		$gender = -1;
	}else if ($pokeNum == $klink) {
		$gender = -1;
	}else if ($pokeNum == $regirock) {
		$gender = -1;
	}else if ($pokeNum == $regice) {
		$gender = -1;
	}else if ($pokeNum == $registeel) {
		$gender = -1;
	}else if ($pokeNum == $latias) {
		$gender = 2;
	}else if ($pokeNum == $latios) {
		$gender = 1;
	}else if ($pokeNum == $volbeat) {//313
		$gender = 1;
	}else if ($pokeNum == $illumise) {//314
		$gender = 2;
	}else if ($pokeNum == $lunatone) {//337
		$gender = -1;
	}else if ($pokeNum == $solrock) {//338
		$gender = -1;
	}else if ($pokeNum == $baltoy) {//343
		$gender = -1;
	}else if ($pokeNum == $kyogre) {
		$gender = -1;
	}else if ($pokeNum == $groudon) {
		$gender = -1;
	}else if ($pokeNum == $rayquaza) {//384
		$gender = -1;
	}else if ($pokeNum == $jirachi) {//385
		$gender = -1;
	}else if ($pokeNum == $deoxys) {
		$gender = -1;
	}else if ($pokeNum == $regigigas) {
		$gender = -1;
	}else if ($pokeNum == $darkrai) {
		$gender = -1;
	}else if ($pokeNum == $cresselia) {
		$gender = 2;
	}else if ($pokeNum == $bronzor) {//436
		$gender = -1;
	}else if ($pokeNum == $uxie) {//480
		$gender = -1;
	}else if ($pokeNum == $mesprit) {//481
		$gender = -1;
	}else if ($pokeNum == $azelf) {//482
		$gender = -1;
	}else if ($pokeNum == $dialga) {//483
		$gender = -1;
	}else if ($pokeNum == $palkia) {//484
		$gender = -1;
	}else if ($pokeNum == $giratina) {//487
		$gender = -1;
	}else if ($pokeNum == $phione) {//489
		$gender = -1;
	}else if ($pokeNum == $shaymin) {//492
		$gender = -1;
	}else if ($pokeNum == $arceus) {//493
		$gender = -1;
	}else if ($pokeNum == $victini) {//494
		$gender = -1;
	}else if ($pokeNum == $throh) {//538
		$gender = 1;
	}else if ($pokeNum == $petilil) {//548
		$gender = 2;
	}else if ($pokeNum == $vullaby) {//629
		$gender = 2;
	}else if ($pokeNum == $cobalion) {//638
		$gender = -1;
	}else if ($pokeNum == $terrakion) {//639
		$gender = -1;
	}else if ($pokeNum == $virizion) {//640
		$gender = -1;
	}else if ($pokeNum == $tornadus) {//641
		$gender = 1;
	}else if ($pokeNum == $thundurus) {//642
		$gender = 1;
	}else if ($pokeNum == $reshiram) {//643
		$gender = -1;
	}else if ($pokeNum == $zekrom) {//644
		$gender = -1;
	}else if ($pokeNum == $landorus) {//645
		$gender = 1;
	}else if ($pokeNum == $kyurem) {//646
		$gender = -1;
	}else if ($pokeNum == $keldeo) {//647
		$gender = -1;
	}else if ($pokeNum == $meloetta) {//648
		$gender = -1;
	}else if ($pokeNum == $genesect) {//649
		$gender = -1;
	}else if ($pokeNum == $flabebe) {//669
		$gender = 2;
	}else if ($pokeNum == $carbink) {//703
		$gender = -1;
	}else if ($pokeNum == $xerneas) {//716
		$gender = -1;
	}else if ($pokeNum == $yveltal) {//717
		$gender = -1;
	}else if ($pokeNum == $zygarde) {//718
		$gender = -1;
	}else if ($pokeNum == $diancie) {//719
		$gender = -1;
	}else if ($pokeNum == $hoopa) {//720
		$gender = -1;
	}
	$itemUsed = $femaleItem;
	if ($usedMale == true) {
		$itemUsed = $maleItem;
	}
	if ($pokeNum == $roselia && $itemUsed == 61) {
		$pokeNum = $budew;
	}else if ($pokeNum == $sudowoodo && $itemUsed == 60) {
		$pokeNum = $bonsly;
	}else if ($pokeNum == $mrmime && $itemUsed == 59) {
		$pokeNum = $mimejr;
	}else if ($pokeNum == $chansey && $itemUsed == 58) {
		$pokeNum = $happiny;
	}else if ($pokeNum == $snorlax && $itemUsed == 57) {
		$pokeNum = $munchlax;
	}else if ($pokeNum == $wobbuffet && $itemUsed == 56) {
		$pokeNum = $wynaut;
	}else if ($pokeNum == $marill && $itemUsed == 55) {
		$pokeNum = $azurill;
	}else if ($pokeNum == $mantine && $itemUsed == 54) {
		$pokeNum = $mantyke;
	}else if ($pokeNum == $chimecho && $itemUsed == 79) {
		$pokeNum = $chingling;
	}
	$totalMoves = 4;
	$originalMoves = 1;
	if ($moveList[3] != 0) {
		$originalMoves = 4;
	}else if ($moveList[2] != 0) {
		$originalMoves = 3;
	}else if ($moveList[1] != 0) {
		$originalMoves = 2;
	}
	$parentMoves = array ($move1M, $move2M, $move3M, $move4M, $move1F, $move2F, $move3F, $move4F);
	$newEggMoves = get_Breed_Egg_Moves($parentMoves, $pokeNum);
	$totalBreedMoves = count($newEggMoves);
	if ($totalBreedMoves > 0) {//HAVE BREED MOVES!
		$availableSpots = $totalMoves-$originalMoves;
		$startPoint = $originalMoves;
		if ($availableSpots < $totalBreedMoves) {
			if ($totalBreedMoves >= $totalMoves) {
				$startPoint = 0;
			}else{
				$startPoint = $totalMoves-$totalBreedMoves;
			}
		}
		for ($b=0, $i=$startPoint; $i<$totalMoves && $b<count($newEggMoves); $i++, $b++) {
			$moveList[$i] = $newEggMoves[$b];
		}
	}
	$eggInfo = array($pokeNum, $pokeShiny, $gender, $moveList[0], $moveList[1], $moveList[2], $moveList[3]);
	return $eggInfo;
}
function get_Breed_Egg_Moves($parentMoves, $pokeNum) {
	$newEggMoves = array();
	$eggMoveList = get_Egg_Move_List($pokeNum);
	$currentMoveList = 0;
	$currentMoveParent = 0;
	if (count($eggMoveList)) {//THIS POKEMON HAS EGG MOVES!
		for ($b=0; $b<count($parentMoves); $b++) {
			$currentMoveParent = $parentMoves[$b];
			for ($i=0; $i<count($eggMoveList); $i++) {
				$currentMoveList = $eggMoveList[$i];
				if ($currentMoveParent == $currentMoveList) {
					array_push($newEggMoves, $currentMoveParent);
					break;
				}
			}
		}
	}
	return $newEggMoves;
}
function get_Egg_Move_List($pokeNum) {
	
	require 'pokeList.php';
	$moveList = array();
	$TACKLE = 1;//done
		$SAND_ATTACK = 2;//done
		$TAIL_WHIP = 3;//done
		$QUICK_ATTACK = 4;//done
		$GROWL=5;//done
		$SCRATCH=6;//done
		$STRING_SHOT=7;//done
		$POISON_STING=8;//done
		$LEECH_SEED=9;//done
		$EMBER=10;//done
		$BUBBLE=11;//done
		$FOCUS_ENERGY = 12;//done
		$BUG_BITE = 13;//done
		$HARDEN = 14;//done
		$GUST = 15;//done
		$VINE_WHIP = 16;//done
		$SMOKESCREEN = 17;//done
		$WITHDRAW = 18;//done
		$BITE = 19;//done
		$CONFUSION = 20;//done
		$FURY_ATTACK =21;//done
		$THUNDERSHOCK = 22;//done
		$THUNDERWAVE = 23;//done
		$POISON_POWDER = 24;//done
		$DEFENSE_CURL = 25;//done
		$SLEEP_POWDER = 26;//done
		$TAKE_DOWN = 27;//done
		$RAZOR_LEAF = 28;//done
		$DRAGON_RAGE = 29;//done
		$SCARY_FACE = 30;//done
		$WATER_GUN = 31;//done
		$RAPID_SPIN = 32;//done
		$PURSUIT = 33;//done
		$HYPER_FANG = 34;//done
		$SUCKER_PUNCH = 35;//done
		$WHIRLWIND = 36;//done
		$STUN_SPORE = 37;//done
		$SUPERSONIC = 38;//done
		$TWINEEDLE = 39;//done
		$RAGE = 40;//done
		$ELECTRO_BALL = 41;//done
		$PECK = 42;//done
		$LEER = 43;//done
		$AERIAL_ACE = 44;//done
		$DOUBLE_KICK = 45;//done
		$FURY_SWIPES = 46;//done
		$SING = 47;//done
		$POUND = 48;//done
		$DISABLE = 49;//done
		$ROUND = 50;//done
		$LOW_KICK = 51;//done
		$KARATE_CHOP = 52;//done
		$SEISMIC_TOSS = 53;//done
		$LEECH_LIFE = 54;//done
		$FURY_CUTTER = 55;//done
		$TRANSFORM = 56;//done
		$REFLECT_TYPE = 57;//done
		$METRONOME = 58;//done
		$MEGA_PUNCH = 59;//done
		$ENCORE = 60;//done
		$DOUBLE_SLAP = 61;//done
		$FOLLOW_ME = 62;//done
		$MINIMIZE = 63;//done
		$MUD_SPORT = 64;//done
		$ROCK_POLISH = 65;//done
		$ROCK_THROW = 66;//done
		$MAGNITUDE = 67;//done
		$ROLLOUT = 68;//done
		$SWIFT = 69;//done
		$WRAP = 70;//done
		$GLARE = 71;//done
		$SCREECH = 72;//done
		$ACID = 73;//done
		$ASTONISH = 74;//done
		$WING_ATTACK = 75;//done
		$SWEET_SCENT = 76;//done
		$TWISTER = 77;//done
		$MIRROR_MOVE = 78;//done
		$DOUBLE_TEAM = 79;//done
		$HORN_ATTACK = 80;//done
		$CONFUSE_RAY = 81;//done
		$ROCK_BLAST = 82;//done
		$PROTECT = 83;//done
		$CRUNCH = 84;//done
		$CRUSH_CLAW = 85;//done
		$WAKE_UP_SLAP = 86;//done
		$SPORE = 87;//done
		$CHIP_AWAY = 88;//done
		$PSYBEAM = 89;//done
		$SMACK_DOWN = 90;//done
		$GROWTH = 91;//done
		$FIRE_FANG = 92;//done
		$WATER_PULSE = 93;//done
		$TOXIC_SPIKES = 94;//done
		$FEATHERDANCE = 95;//done
		$ASSURANCE = 96;//done
		$AGILITY =97;//done
		$STOCKPILE = 98;//done
		$HELPING_HAND = 99;//done
		$AIR_CUTTER = 100;//done
		$ROAR = 101;//done
		$ODOR_SLEUTH = 102;//done
		$FLAME_WHEEL = 103;//done
		$REVERSAL = 104;//done
		$FIRE_SPIN = 105;//done
		$FLAME_BURST = 106;//done
		$ABSORB = 107;//done
		$MEGA_DRAIN = 108;//done
		$WATER_SPORT = 109;//done
		$HYPNOSIS = 110;//done
		$RAIN_DANCE = 111;//done
		$BUBBLEBEAM = 112;//done
		$LUCKY_CHANT = 113;//done
		$BODY_SLAM = 114;//done
		$RECOVER = 115;//done
		$TELEPORT = 116;//done
		$MIRACLE_EYE = 117;//done
		$ALLY_SWITCH = 118;//done
		$LICK = 119;//done
		$LOVELY_KISS = 120;//done
		$POWDER_SNOW = 121;//done
		$ICE_PUNCH = 122;//done
		$HEART_STAMP = 123;//done
		$SWALLOW = 124;//done
		$SLAM = 125;//done
		$SPIT_UP = 126;//done
		$DOUBLE_EDGE = 127;//done
		$SAND_TOMB = 128;//done
		$SAFEGUARD = 129;//done
		$SLASH = 130;//done
		$KNOCK_OFF = 131;//done
		$LEAF_TORNADO = 132;//done
		$AQUA_TAIL = 133;//done
		$SILVER_WIND = 134;//done
		$PIN_MISSILE = 135;//done
		$SUPER_FANG = 136;//done
		$ACID_SPRAY = 137;//done
		$COSMIC_POWER = 138;//done
		$MUD_SHOT = 139;//done
		$FAKE_TEARS = 140;//done
		$SPLASH = 141;//done
		$THUNDERBOLT = 142;//done
		$REST = 143;//done
		$SELFDESTRUCT = 144;//done
		$TAILWIND = 145;//done
		$REFLECT = 146;//done
		$FLAIL = 147;//done
		$PSYCHIC = 148; //done
		$WORRY_SEED = 149; //done
		$SKULL_BASH = 150;//done
		$WILL_O_WISP = 151;//done
		$BELLY_DRUM = 152;//done
		$PETAL_DANCE = 153;//done
		$DYNAMICPUNCH = 154;//done
		$BULLDOZE = 155;//done
		$ICE_FANG = 156;//done
		$SYNTHESIS = 157;//done
		$ROOST = 158;//done
		$MUD_BOMB = 159;//done
		$GYRO_BALL = 160;//done
		$FLATTER = 161;//done
		$ACROBATICS = 162;//done
		$MOONLIGHT = 163;//done
		$SWAGGER = 164;//done
		$IRON_DEFENSE = 165;//done
		$RAGE_POWDER = 166;//done
		$ENDEAVOR = 167;//done
		$FEINT = 168;//done
		$PAYBACK = 169;//done
		$TELEKINESIS = 170;//done
		$THRASH = 171;//done
		$GASTRO_ACID = 172;//done
		$STEALTH_ROCK = 173;//done
		$REST_KYOGRE = 174;//BOSS
		$FAKE_OUT = 175;//done
		$FAINT_ATTACK = 176;//done
		$TAUNT = 177;//done
		$PAY_DAY = 178;//done
		$POWER_GEM = 179;//done
		$SWORDS_DANCE = 180;//done
		$NIGHT_SLASH = 181;//done
		$POISON_JAB = 182;//done
		$SEED_BOMB = 183;//done
		$FLAMETHROWER = 184;//done
		$DRILL_PECK = 185;//done
		$SANDSTORM = 186;//done
		$GRAVITY = 187;//done
		$POISON_FANG = 188;//done
		$GIGA_DRAIN = 189;//done
		$CROSS_CHOP = 190;//done
		$NASTY_PLOT = 191;//done
		$EXTREMESPEED = 192;//done
		$HYDRO_PUMP = 193;//done
		$EARTHQUAKE = 194;//done
		$AVALANCHE = 195;//done
		$PSYCHO_CUT = 196;//done
		$BARRIER = 197;//done
		$BIND = 198;//done
		$ROCK_TOMB = 199;//done
		$CAMOUFLAGE = 200;//done
		$CONSTRICT = 201;//done
		$THUNDER = 202;//done
		$FIRE_BLAST = 203;//done
		$BLIZZARD = 204;//done
		$MAGNITUDE_2 = 205;//BOSS
		$EARTH_POWER = 206;//done
		$DIG = 207;//done
		$POISON_GAS = 208;//done
		$MEDITATE = 209;//done
		$PSYCH_UP = 210;//done
		$SYNCHRONOISE = 211;//done
		$LIGHT_SCREEN = 212;//done
		$SUBSTITUTE = 213;//done
		$MIMIC = 214;//done
		$COPYCAT = 215;//done
		$HEADBUTT = 216;//done
		$MUD_SLAP = 217;//done
		$BUG_BUZZ = 218;//done
		$DISCHARGE = 219;//done
		$CALM_MIND = 220;//done
		$HAZE = 221;//done
		$DRAGONBREATH = 222;//done
		$ZEN_HEADBUTT = 223;//done
		$CUT = 224;//done
		$FLASH = 225;//done
		$LOW_SWEEP = 226;//done
		$FORESIGHT = 227;//done
		$REVENGE = 228;//done
		$VITAL_THROW = 229;//done
		$SUBMISSION = 230;//done
		$SONICBOOM = 231;//done
		$SPARK = 232;//done
		$CHARGE_BEAM = 233;//done
		$MAGNET_RISE = 234;//done
		$AROMATHERAPY = 235;//done
		$MIND_READER = 236;//done
		$HEX = 237;//done
		$EXPLOSION = 238;//done
		$STORED_POWER = 239;//done
		$SOLARBEAM = 240;//done
		$COIL = 241;//done
		$INFERNO = 242;//done
		$DRAGON_DANCE = 243;//done
		$DRAGON_CLAW = 244;//done
		$SHADOW_CLAW = 245;//done
		$AIR_SLASH = 246;//done
		$FLASH_CANNON = 247;//done
		$PLUCK = 248;//done
		$THUNDER_FANG = 249;//done
		$CROSS_POISON = 250;//done
		$TRI_ATTACK = 251;//done
		$KINESIS = 252;//done
		$WIDE_GUARD = 253;//done
		$NIGHTMARE = 254;//done
		$MAGICAL_LEAF = 255;//done
		$QUICK_GUARD = 256;//done
		$CHARGE = 257;//done
		$GUARD_SWAP = 258;//done
		$POWER_SWAP = 259;//done
		$AURORA_BEAM = 260;//done
		$AQUA_RING = 261;//done
		$BATON_PASS = 262;//done
		$CONVERSION = 263;//done
		$CONVERSION_2 = 264;//done
		$SIGNAL_BEAM = 265;//done
		$VACUUM_WAVE = 266;//done
		$RAZOR_WIND = 267;//done
		$X_SCISSOR = 268;//done
		$VICEGRIP = 269;//done
		$BRICK_BREAK= 270;//done
		$DRAGON_TAIL= 271;//done
		$DRAGON_RUSH = 272;//done
		$FALSE_SWIPE = 273;//done
		$CELEBI_HEAL = 274;//boss
		$HORN_DRILL = 275;//done
		$FISSURE = 276;//done
		$PUNISHMENT = 277;//done
		$LOCK_ON = 278;//done
		$QUIVER_DANCE = 279;//done
		$CURSE = 280;//done
		$HYPER_BEAM = 281;//done
		$LEAF_STORM = 282;//done
		$WRING_OUT = 283;//done
		$GUILLOTINE = 284;//done
		$LEAF_BLADE = 285;//done
		$FUTURE_SIGHT = 286;//done
		$RETALIATE = 287;//done
		$GUNK_SHOT = 288;//done
		$IRON_TAIL = 289;//done
		$CLOSE_COMBAT = 290;//done
		$HYPER_VOICE = 291;//done
		$HEALING_WISH = 292;//done
		$DOUBLE_HIT = 293;//done
		$MIRROR_COAT = 294;//done
		$LAST_RESORT = 295;//done
		$ANCIENTPOWER = 296;//done
		$STONE_EDGE = 297;//done
		$SLUDGE_WAVE = 298;//done
		$METAL_SOUND = 299;//done
		$MAGNET_BOMB = 300;//done
		$MIRROR_SHOT = 301;//done
		$EXTRASENSORY = 302;//done
		$HEAT_WAVE = 303;//done
		$AFTER_YOU = 304;//done
		$SUPERPOWER = 305;//done
		$STEAMROLLER = 306;//done
		$NIGHT_SHADE = 307;//done
		$SHADOW_BALL = 308;//done
		$DREAM_EATER = 309;//done
		$DARK_PULSE = 310;//done
		$DESTINY_BOND = 311;//done
		$BONE_CLUB = 312;//done
		$BONEMERANG = 313;//done
		$BONE_RUSH = 314;//done
		$SHADOW_PUNCH = 315;//done
		$THUNDERPUNCH = 316;//done
		$FIRE_PUNCH = 317;//done
		$HURRICANE = 318;//done
		$DRILL_RUN = 319;//done
		$FINAL_GAMBIT = 320;//done
		$CIRCLE_THROW = 321;//done
		$METEOR_MASH = 322;//done
		$BRAVE_BIRD = 323;//done
		$PERISH_SONG = 324;//done
		$OUTRAGE = 325;//done
		$METAL_CLAW = 326;//done
		$STOMP = 327;//done
		$BRINE = 328;//done
		$CRABHAMMER = 329;//done
		$FLARE_BLITZ = 330;//done
		$MAGIC_COAT = 331;//done
		$PSYSHOCK = 332;//done
		$TRUMP_CARD = 333;//done
		$SMOG = 334;//done
		$MEGAHORN = 335;//done
		$ZAP_CANNON = 336;//done
		$AMNESIA = 337;//done
		$WATERFALL = 338;//done
		$SOAK = 339;//done
		$YAWN = 340;//done
		$SNORE = 341;//done
		$SLEEP_TALK = 342;//done
		$HEAVY_SLAM = 343;//done
		$GIGA_IMPACT = 344;//done
		$ICE_SHARD = 345;//done
		$ICE_BEAM = 346;//done
		$SHEER_COLD = 347;//done
		$ACID_ARMOR = 348;//done
		$DRAGON_PULSE = 349;//done
		$ME_FIRST = 350;//done
		$LAVA_PLUME = 351;//done
		$SEARING_SHOT = 352;//done
		$INCINERATE = 353; //done
		$ENDURE = 354;//done
		$FLAME_CHARGE = 355;//done
		$ROLLING_KICK = 356;//done
		$HI_JUMP_KICK = 357;//done
		$JUMP_KICK = 358;//done
		$BLAZE_KICK = 359;//done
		$MEGA_KICK = 360;//done
		$COMET_PUNCH = 361;//done
		$MACH_PUNCH = 362;//done
		$BULLET_PUNCH = 363;//done
		$SKY_UPPERCUT = 364;//done
		$DETECT = 365;//done
		$COUNTER = 366;//done
		$FOCUS_PUNCH = 367;//done
		$SHADOW_RUSH = 368;//PTD1 ONLY
		$ICICLE_SPEAR = 369;//done
		$CLAMP = 370;//done
		$RAZOR_SHELL = 371;//done
		$WHIRLPOOL = 372;//done
		$SHELL_SMASH = 373;//done
		$SPIKE_CANNON = 374;//done
		$SPIKES = 375;//done
		$ICICLE_CRASH = 376;//done
		$WONDER_ROOM = 377;//done
		$AQUA_JET = 378;//done
		$MUDDY_WATER = 379;//done
		$MUKS_GAS = 380;//BOSS
		$SLUDGE = 381;//done
		$SLUDGE_BOMB = 382;//done
		$UPROAR = 383;//done
		$ACUPRESSURE = 384;//done
		$SLACK_OFF = 385;//done
		$HEAL_PULSE = 386;//done
		$MEMENTO  = 387;//done
		$WOOD_HAMMER = 388;//done
		$EGG_BOMB = 389;//done
		$BULLET_SEED = 390;//done
		$BARRAGE = 391;//done
		$SOFTBOILED = 392;//done
		$REFRESH = 393;//done
		$HAMMER_ARM = 394;//done
		$DIZZY_PUNCH = 395;//done
		$WORK_UP = 396; // done
		$JOEYS_ROCK = 397;//BOSS
		$JOEYS_BAIT = 398;//BOSS
		$POWER_WHIP = 399;//done
		$TICKLE = 400;//done
		$INGRAIN = 401;//done
		$ERUPTION = 402;//done
		$BOUNCE = 403;//done
		$GARY_POTION = 404;//BOSS
		$ZAPDOS_DRILL_PECK = 405;//BOSS
		$ZAPDOS_THUNDER = 406;//BOSS
		$ICY_WIND = 407;//done
		$DIVE = 408;//done
		$HAIL = 409;//done
		$CLEAR_SMOG = 410;//done
		$SUNNY_DAY = 411;//done
		$SKY_DROP = 412;//done
		$IRON_HEAD = 413;//done
		$ROCK_SLIDE = 414;//done
		$TOXIC = 415;//done
		$SHOCK_WAVE = 416;//done
		$AURA_SPHERE = 417;//done
		$OVERHEAT = 418;//done
		$V_CREATE = 419;//done
		$FUSION_BOLT = 420;//done
		$FUSION_FLARE = 421;//done
		$SKY_ATTACK = 422;//done
		$SHARPEN = 423;//done
		$PSYSTRIKE = 424;//done
		$SURF = 425;//done
		$STRENGTH = 426;///////////////////////////////////////////////////////////
		$FLY = 427;//done
		$FREEZE_ALL_TOWERS = 428;//BOSS
		$SLIPPERY_FLOOR = 429;//BOSS
		$BRAIN_FREEZE = 430;//BOSS
		$ANTI_POISON = 431;//BOSS
		$TOWER_TELEPORT = 432;//BOSS
		$RETURN_TO_POKEBALL = 433;//BOSS
		$NATURAL_GIFT = 434;//done
		$ECHOED_VOICE = 435;//done
		$PSYCHO_SHIFT = 436;//done
		$ONIX_BOULDER_THROW = 437; //BOSS
		$FOUL_PLAY = 438;//done
		$TORMENT = 439;//done
		$EMBARGO = 440;//done
		$IMPRISON = 441;//done
		$NIGHT_DAZE = 442;//done
		$U_TURN = 443;//done
		$HONE_CLAWS = 444;//done
		$COTTON_SPORE = 445;//done
		$ZAI_POTION = 446;//BOSS
		$ZAPDOS_WEAK_THUNDERSHOCK = 447;//BOSS
		$ZAI_POTION_2 = 448;//BOSS
		$FORCE_PALM = 449;//done
		$SHADOW_SNEAK = 450;//done
		$SPIDER_WEB = 451;//done
		$MIST = 452;//done
		$WEATHER_BALL = 453;//done
		$MEAN_LOOK = 454;//done
		$SPITE = 455;//done
		$CHARM = 456;//done
		$WILD_CHARGE = 457;//done
		$PIDGEY_SAND_ATTACK = 458;//BOSS
		$PIDGEOTTO_GUST = 459;//BOSS
		$PIDGEOT_GUST = 460;//BOSS
		$COTTON_GUARD = 461;//done
		$WISH = 462;//done
		$DOOM_DESIRE = 463;//done
		$SWEET_KISS = 464;//done
		$FACADE = 465;//done
		$PIDGEY_GUST_2 = 466;//BOSS
		$PIDGEOTTO_GUST_2 = 467;//BOSS
		$PIDGEOT_GUST_2 = 468;//BOSS
		$BELLSPROUT_WRAP = 469;//BOSS
		$BELLSPROUT_RAZOR_LEAF = 470; //BOSS
		$ENERGY_BALL = 471;//done
		$WEAK_GUST = 472;//BOSS
		$LUGIA_GUST = 473;//BOSS
		$ZAI_POTION_3 = 474;//BOSS
		$LUGIA_GUST_2 = 475;//BOSS
		$VENOSHOCK = 476;//done
		$HEAD_SMASH = 477;//done
		$BIDE = 478;//done
		$STRUGGLE_BUG = 479;//done
		$HIDDEN_POWER = 480;//done
		$ATTRACT = 481;//done
		$CAPTIVATE = 482;//done
		$DUAL_CHOP = 483;//done
		$PAIN_SPLIT = 484;//done
		$AUTOTOMIZE = 485;//done
		$METAL_BURST= 486;//done
		$ICE_BALL = 487;//done
		$ENTEI_FIREBALL = 488;//BOSS
		$OMINOUS_WIND = 489;//done
		$ROCK_CLIMB = 490;//done
		$MORNING_SUN = 491;//done
		$BESTOW = 492;//done
		$FLING = 493;//done
		$FRUSTRATION = 494;//done
		$RETURN_1 = 495;//done
		$MAGMA_STORM = 496;//done
		$ENTRAINMENT = 497;//done
		$PSYWAVE = 498;//done
		$SNATCH = 499;//done
		$SKILL_SWAP = 500;//done
		$HEAL_BLOCK = 501;//done
		$TRICK = 502;//done
		$SWITCHEROO = 503;//done
		$COVET = 504;//done
		$GRUDGE = 505;//done
		$ROLE_PLAY = 506;//done
		$BULK_UP = 507;//done
		$DRAIN_PUNCH = 508;//done
		$MEWTHREE_ATTACK = 509;//BOSS
		$SHADOW_BLITZ = 510;//done
		$SHADOW_WAVE = 511;//done
		$HEAL_BELL = 512;//done
		$BLOCK = 513;//done
		$POISON_TAIL = 514;//done
		$ARIADOS_SPIDER_WEB = 515;//BOSS
		$SPINARAK_BUG_BITE = 516;//BOSS
		$DEFEND_ORDER = 517;//done
		$HEAL_ORDER = 518;//done
		$ATTACK_ORDER = 519;//done
		$SIMPLE_BEAM = 520;//done
		$GUARD_SPLIT = 521;//done
		$POWER_SPLIT = 522;//done
		$HEAT_CRASH = 523;//done
		$ARM_THRUST = 524;//done
		$MAGIC_ROOM = 525;//done
		$HOWL = 526;//done
		$RECYCLE = 527;//done
		$TRIPLE_KICK = 528;//done
		$MILTANK_ROLLOUT = 529;//BOSS
		$MILTANK_BODY_SLAM = 530;//BOSS
		$MILTANK_MILK_DRINK = 531;//BOSS
		$TEMP_ATTACK = 532;//BOSS
		$MILTANK_DEFENSE_CURL = 533;//BOSS
		$GEAR_GRIND = 534;//done
		$SHIFT_GEAR = 535;//done
		$GRASSWHISTLE = 536;//done
		$QUASH = 537;//done
		$BEAT_UP = 538;//done
		$ROCK_SMASH = 539;//done
		$SMELLING_SALT = 540;//done
		$VOLT_SWITCH = 541;//done
		$DEFOG = 542;//done
		$ROCK_WRECKER = 543;//done
		$HOUNDOOM_FLAMETHROWER = 544;//done
		$FOCUS_BLAST = 545;//done
		$FIERY_DANCE = 546;//done
		$HEAD_CHARGE = 547;//done
		$MILK_DRINK = 548;//done
		$GEOMANCY = 549;//done
		$MOONBLAST = 550;//done
		$MYSTY_TERRAIN = 551;//done
		$HORN_LEECH = 552;//done
		$NATURE_POWER = 553;//done
		$SNARL = 554;//done
		$OBLIVION_WING = 555;//done
		$PHANTOM_FORCE = 556;//done
		$GLACIATE = 557;//done
		$HITMONCHAN_PUNCH = 558;//done
		$STEELIX_ROCK_SLIDE = 559;//done
		$SKETCH = 560;//done
		$WATER_SPOUT = 561;//done
		$PRESENT = 562;//done
		$OCTAZOOKA = 563;//done
		$STEEL_WING = 564;//done
		$DISARMING_VOICE = 565;//done
		$FOREST_CURSE = 566;//done
		$GENERATOR_ELECTRIC = 567;//BOSS
		$MASSIVE_ROAR = 568;//BOSS
		$BLINDING_FLASH = 569;//BOSS
		$MACHINE_TAKEOVER = 570;//BOSS
		$MASSIVE_EXPLODE = 571;//BOSS
		$MAGNETIC_PULL = 572;//BOSS
		$AGILITY_DISCHARGE = 573;//BOSS
		$POWER_TRICK = 574;//done
		$SACRED_SWORD = 575;//done
		$KINGS_SHIELD = 576;//done
		$FAIRY_LOCK = 577;//done
		$FAIRY_WIND = 578;//done
		$DRAINING_KISS = 579;//done
		$CRAFTY_SHIELD = 580;//done
		$PLAY_ROUGH = 581;//done
		$ONIX_TUNNEL = 582;//BOSS
		$TUNNEL_MAGIC = 583;//BOSS
		$SUDOWOODO_TRICK = 584;//BOSS
		$DARK_VOID = 585;//done
		$LIFE_BEAM = 586;//BOSS
		$ROCK_TOSS = 587;//BOSS
		$DESTRUCTION_HEAD = 588;//BOSS
		$BOULDER_DUTY = 589;//BOSS;
		$MYSTICAL_FIRE = 590;//done
		$TRICK_ROOM = 591;//done
		$VENUSAUR_BOSS = 592;//BOSS
		$TREE_BOSS = 593;//BOSS
		$SCEPTILE_BOSS = 594;//BOSS
		$TORTERRA_BOSS = 595;//BOSS
		$SIMISAGE_BOSS = 596;//BOSS
		$SERPERIOR_BOSS = 597;//BOSS
		$MEGANIUM_BOSS = 598;//BOSS
		$ODDISH_BOSS = 599;//BOSS (NOT USED)
		$VICTREEBEL_BOSS = 600;//BOSS (NOT USED)
		$NEEDLE_ARM = 601;//done
		$SPIKY_SHIELD = 602;//done
		$MAT_BLOCK = 603;//done
		$WATER_SHURIKEN = 604;//done
		$PARTING_SHOT = 605;//done
		$SNORLAX_BOSS_SCARED_THOUGHTS = 606;//BOSS
		$LUGIA_BOSS_DEADLY_GUST = 607;//BOSS
		$MISDREAVUS_BOSS_SELF_IMAGE = 608;//BOSS
		$WOBBUFFET_BOSS_COUNTER_SPIN = 609;//BOSS
		$FRENZY_PLANT = 610;//done
		$BLAST_BURN = 611;//done
		$HYDRO_CANNON = 612;//done
		$DRACO_METEOR = 613;//done
		$GIGA_BLAST = 614;//done
		$SACRED_FIST = 615;//done
		$FLOCK_FLIGHT = 616;//done
		$SLUDGE_BLAST = 617;//done
		$SAND_BLAST = 618;//done
		$ROCK_MISSILES = 619;//done
		$WEB_BLAST = 620;//done
		$IMAGE_BLAST = 621;//done
		$METAL_BLAST = 622;//done
		$ZAP_BLAST = 623;//done
		$PSYCHIC_BLAST = 624;//done
		$ICE_BLAST = 625;//done
		$SHADOW_BLAST = 626;//done
		$DARK_BLAST = 627;//done
		$FAIRY_BLAST = 628;//done
		$FLYING_PRESS = 629;//done
		$AEROBLAST = 630;//done
		$SACRED_FIRE = 631;//done
		$THIEF = 632;//done
		$LUSTER_PURGE = 633;//done
		$MIST_BALL = 634;//done
		$PSYCHO_BOOST = 635;//done
		$CRUSH_GRIP = 636;//done
		$ASSIST = 637;//done
		$LUNAR_DANCE = 638;//done
		$TEETER_DANCE = 639;//done
		$SOLID_PROTECTION = 640;//BOSS
		$DEADLY_SANDSTORM = 641;//BOSS
		$TECHNO_BLAST = 642;//done
		$TAIL_GLOW = 643;//done
		$HEART_SWAP = 644;//done
		$LANDS_WRATH = 645;//done
		$ROAR_OF_TIME = 646;//done
		$FLASH_CORE = 647;//BOSS
		$FISH_STARMIE = 648;//BOSS
		$SPACIAL_REND = 649;//done
		$SHADOW_FORCE = 650;//done
		$SPEED_SPARK = 651;//BOSS
		$HEAL_SPARK = 652;//BOSS
		$AGGRESSIVE_SPARK = 653;//BOSS
		$BOLT_STRIKE = 654;//done
		$BLUE_FLARE = 655;//done
		$DAZZLING_GLEAM = 656;//done
		$SEED_FLARE = 657;//done
		$WEEZING_BOSS_GAS = 658;//BOSS
		$STORM_THROW = 659;//done
		$CHATTER = 660;//done
		$SECRET_POWER = 661;//done
		$GRASS_KNOT = 662;//done
		$SCALD = 663;//done
		$TAIL_SLAP = 664;//done
		$ELECTROWEB = 665;//done
		$PARABOLIC_CHARGE = 666;//done
		$NUZZLE = 667;//done
		$DIAMOND_STORM = 668;//done
		$BOSS_ALAKAZAM_GYM_CHALLENGE_ATTACK = 669;//BOSS
		$NOBLE_ROAR = 670;//done
		$ROTOTILLER = 671;//done
		$BOSS_MAGMORTAR_GYM_CHALLENGE_ATTACK = 672;//BOSS
		$TOPSY_TURVY = 673;//done
		$BABY_DOLL_EYES = 674;//done
		$JUDGMENT = 675;//done
		$ELECTRIFY = 676;//done
		$EERIE_IMPULSE = 677;//done
		$BOOMBURST = 678;//done
		$FREEZE_DRY = 679;//done
		$TRICK_OR_TREAT = 680;//done
		$GRASSY_TERRAIN = 681;//NOT DONE
	
	if ($pokeNum == $caterpie) {//10
		//NONE
	}else if ($pokeNum == $bulbasaur) {//1
		array_push($moveList, $AMNESIA, $CHARM, $CURSE, $ENDURE, $GIGA_DRAIN, $GRASSWHISTLE, $GRASSY_TERRAIN, $INGRAIN, $LEAF_STORM, $MAGICAL_LEAF, $NATURE_POWER, $PETAL_DANCE, $POWER_WHIP, $SKULL_BASH, $SLUDGE);
	}else if ($pokeNum == $charmander) {//4
		array_push($moveList, $AIR_CUTTER, $ANCIENTPOWER, $BEAT_UP, $BELLY_DRUM, $BITE, $COUNTER, $CRUNCH, $DRAGON_DANCE, $DRAGON_PULSE, $DRAGON_RUSH, $FLARE_BLITZ, $FOCUS_PUNCH, $METAL_CLAW, $OUTRAGE);
	}else if ($pokeNum == $squirtle) {//7
		array_push($moveList, $AQUA_JET, $AQUA_RING, $AURA_SPHERE, $BRINE, $DRAGON_PULSE, $FAKE_OUT, $FLAIL, $FORESIGHT, $HAZE, $MIRROR_COAT, $MIST, $MUD_SPORT, $MUDDY_WATER, $REFRESH, $WATER_SPOUT, $YAWN);
	}else if ($pokeNum == $weedle) {//13
		//NONE
	}else if ($pokeNum == $pidgey) {//16
		array_push($moveList, $AIR_CUTTER, $AIR_SLASH, $BRAVE_BIRD, $DEFOG, $FAINT_ATTACK, $FORESIGHT, $PURSUIT, $STEEL_WING, $UPROAR);
	}else if ($pokeNum == $rattata) {//19
		array_push($moveList, $BITE, $COUNTER, $FINAL_GAMBIT, $FLAME_WHEEL, $FURY_SWIPES, $LAST_RESORT, $ME_FIRST, $REVENGE, $REVERSAL, $SCREECH, $UPROAR);
	}else if ($pokeNum == $spearow) {//21
		array_push($moveList, $ASTONISH, $FEATHERDANCE, $FAINT_ATTACK, $QUICK_ATTACK, $RAZOR_WIND, $SCARY_FACE, $SKY_ATTACK, $STEEL_WING, $TRI_ATTACK, $UPROAR, $WHIRLWIND);
	}else if ($pokeNum == $ekans) {//23
		array_push($moveList, $BEAT_UP, $DISABLE, $IRON_TAIL, $POISON_FANG, $POISON_TAIL, $PURSUIT, $SCARY_FACE, $SLAM, $SNATCH, $SPITE, $SUCKER_PUNCH, $SWITCHEROO);
	}else if ($pokeNum == $sandshrew) {//27
		array_push($moveList, $CHIP_AWAY, $COUNTER, $CRUSH_CLAW, $ENDURE, $FLAIL, $METAL_CLAW, $MUD_SHOT, $NIGHT_SLASH, $RAPID_SPIN, $ROCK_CLIMB, $ROTOTILLER);
	}else if ($pokeNum == $nidoran_f) { //29
		array_push($moveList, $BEAT_UP, $CHARM, $CHIP_AWAY, $COUNTER, $DISABLE, $ENDURE, $FOCUS_ENERGY, $IRON_TAIL, $POISON_TAIL, $PURSUIT, $SKULL_BASH, $SUPERSONIC, $TAKE_DOWN);
	}else if ($pokeNum == $nidoran_m) { //32
		array_push($moveList, $AMNESIA, $BEAT_UP, $CHIP_AWAY, $CONFUSION, $COUNTER, $DISABLE, $ENDURE, $HEAD_SMASH, $IRON_TAIL, $POISON_TAIL, $SUCKER_PUNCH, $SUPERSONIC, $TAKE_DOWN);
	}else if ($pokeNum == $vulpix) {//37
		array_push($moveList, $CAPTIVATE, $DISABLE, $EXTRASENSORY, $FAINT_ATTACK, $FLAIL, $FLARE_BLITZ, $HEAT_WAVE, $HEX, $HOWL, $HYPNOSIS, $POWER_SWAP, $SECRET_POWER, $SPITE, $TAIL_SLAP);
	}else if ($pokeNum == $zubat) {
		array_push($moveList, $BRAVE_BIRD, $CURSE, $DEFOG, $FAINT_ATTACK, $GIGA_DRAIN, $GUST, $HYPNOSIS, $NASTY_PLOT, $PURSUIT, $QUICK_ATTACK, $STEEL_WING, $WHIRLWIND, $ZEN_HEADBUTT);
	}else if ($pokeNum == $oddish) {//43
		array_push($moveList, $AFTER_YOU, $CHARM, $FLAIL, $INGRAIN, $NATURE_POWER, $RAZOR_LEAF, $SECRET_POWER, $SYNTHESIS, $TEETER_DANCE, $TICKLE);
	}else if ($pokeNum == $paras) {//46
		array_push($moveList, $AGILITY, $BUG_BITE, $COUNTER, $CROSS_POISON, $ENDURE, $FLAIL, $LEECH_SEED, $METAL_CLAW, $NATURAL_GIFT, $PSYBEAM, $PURSUIT, $ROTOTILLER, $SCREECH, $SWEET_SCENT, $WIDE_GUARD);
	}else if ($pokeNum == $venonat) {//48
		array_push($moveList, $AGILITY, $BATON_PASS, $BUG_BITE, $GIGA_DRAIN, $MORNING_SUN, $RAGE_POWDER, $SCREECH, $SECRET_POWER, $SIGNAL_BEAM, $SKILL_SWAP, $TOXIC_SPIKES);
	}else if ($pokeNum == $diglett) {//50
		array_push($moveList, $ANCIENTPOWER, $ASTONISH, $BEAT_UP, $ENDURE, $FAINT_ATTACK, $FINAL_GAMBIT, $HEADBUTT, $MEMENTO, $MUD_BOMB, $PURSUIT, $REVERSAL, $SCREECH, $UPROAR);
	}else if ($pokeNum == $meowth) {//52
		array_push($moveList, $AMNESIA, $ASSIST, $CHARM, $FLAIL, $FOUL_PLAY, $HYPNOSIS, $IRON_TAIL, $LAST_RESORT, $ODOR_SLEUTH, $PUNISHMENT, $SNATCH, $SPITE, $TAIL_WHIP);
	}else if ($pokeNum == $psyduck) {//54
		array_push($moveList, $CLEAR_SMOG, $CONFUSE_RAY, $CROSS_CHOP, $ENCORE, $FORESIGHT, $FUTURE_SIGHT, $HYPNOSIS, $MUD_BOMB, $PSYBEAM, $REFRESH, $SECRET_POWER, $SIMPLE_BEAM, $SLEEP_TALK, $SYNCHRONOISE, $YAWN);
	}else if ($pokeNum == $mankey) {//56
		array_push($moveList, $BEAT_UP, $CLOSE_COMBAT, $COUNTER, $ENCORE, $FOCUS_PUNCH, $FORESIGHT, $MEDITATE, $NIGHT_SLASH, $REVENGE, $REVERSAL, $SLEEP_TALK, $SMELLING_SALT);
	}else if ($pokeNum == $growlithe) {//58
		array_push($moveList, $BODY_SLAM, $CLOSE_COMBAT, $COVET, $CRUNCH, $DOUBLE_EDGE, $DOUBLE_KICK, $FIRE_SPIN, $FLARE_BLITZ, $HEAT_WAVE, $HOWL, $IRON_TAIL, $MORNING_SUN, $THRASH);
	}else if ($pokeNum == $poliwag) {//60
		array_push($moveList, $BUBBLEBEAM, $ENCORE, $ENDEAVOR, $ENDURE, $HAZE, $ICE_BALL, $MIND_READER, $MIST, $MUD_SHOT, $REFRESH, $SPLASH, $WATER_PULSE, $WATER_SPORT);
	}else if ($pokeNum == $abra) {//63
		array_push($moveList, $ALLY_SWITCH, $BARRIER, $ENCORE, $FIRE_PUNCH, $GUARD_SPLIT, $GUARD_SWAP, $ICE_PUNCH, $KNOCK_OFF, $POWER_TRICK, $PSYCHO_SHIFT, $SKILL_SWAP, $THUNDERPUNCH);
	}else if ($pokeNum == $machop) {//64
		array_push($moveList, $BULLET_PUNCH, $CLOSE_COMBAT, $COUNTER, $ENCORE, $FIRE_PUNCH, $HEAVY_SLAM, $ICE_PUNCH, $KNOCK_OFF, $MEDITATE, $POWER_TRICK, $QUICK_GUARD, $ROLLING_KICK, $SMELLING_SALT, $THUNDERPUNCH, $TICKLE);
	}else if ($pokeNum == $bellsprout) {//69
		array_push($moveList, $ACID_SPRAY, $BULLET_SEED, $CLEAR_SMOG, $ENCORE, $GIGA_DRAIN, $INGRAIN, $LEECH_LIFE, $MAGICAL_LEAF, $NATURAL_GIFT, $POWER_WHIP, $SYNTHESIS, $TICKLE, $WEATHER_BALL, $WORRY_SEED);
	}else if ($pokeNum == $tentacool) {//72
		array_push($moveList, $ACUPRESSURE, $AURORA_BEAM, $AQUA_RING, $BUBBLE, $CONFUSE_RAY, $HAZE, $KNOCK_OFF, $MIRROR_COAT, $MUDDY_WATER, $RAPID_SPIN, $TICKLE);
	}else if ($pokeNum == $geodude) {//74
		array_push($moveList, $AUTOTOMIZE, $BLOCK, $CURSE, $ENDURE, $FLAIL, $FOCUS_PUNCH, $HAMMER_ARM, $MEGA_PUNCH, $ROCK_CLIMB, $WIDE_GUARD);
	}else if ($pokeNum == $ponyta) {//77
		array_push($moveList, $ALLY_SWITCH, $CAPTIVATE, $CHARM, $DOUBLE_EDGE, $DOUBLE_KICK, $FLAME_WHEEL, $HORN_DRILL, $HYPNOSIS, $LOW_KICK, $MORNING_SUN, $THRASH);
	}else if ($pokeNum == $slowpoke) {//79
		array_push($moveList, $BELLY_DRUM, $BLOCK, $FUTURE_SIGHT, $ME_FIRST, $MUD_SPORT, $SLEEP_TALK, $SNORE, $STOMP, $WONDER_ROOM, $ZEN_HEADBUTT);
	}else if ($pokeNum == $magnemite) {//81
		//NONE
	}else if ($pokeNum == $farfetchd) {//83
		array_push($moveList, $COVET, $CURSE, $FEATHERDANCE, $FORESIGHT, $GUST, $LEAF_BLADE, $MIRROR_MOVE, $MUD_SLAP, $NIGHT_SLASH, $QUICK_ATTACK, $REVENGE, $ROOST, $SIMPLE_BEAM, $STEEL_WING, $TRUMP_CARD);
	}else if ($pokeNum == $doduo) {//84
		array_push($moveList, $ASSURANCE, $BRAVE_BIRD, $ENDEAVOR, $FAINT_ATTACK, $FLAIL, $HAZE, $MIRROR_MOVE, $NATURAL_GIFT, $QUICK_ATTACK, $SUPERSONIC);
	}else if ($pokeNum == $seel) {//86
		array_push($moveList, $DISABLE, $ENCORE, $FAKE_OUT, $ENTRAINMENT, $HORN_DRILL, $ICICLE_SPEAR, $IRON_TAIL, $LICK, $PERISH_SONG, $SIGNAL_BEAM, $SLAM, $SLEEP_TALK, $SPIT_UP, $STOCKPILE, $SWALLOW, $WATER_PULSE);
	}else if ($pokeNum == $grimer) {//88
		array_push($moveList, $ACID_SPRAY, $CURSE, $HAZE, $IMPRISON, $LICK, $MEAN_LOOK, $SCARY_FACE, $SHADOW_PUNCH, $SHADOW_SNEAK, $SPIT_UP, $STOCKPILE, $SWALLOW);
	}else if ($pokeNum == $shellder) {//90
		array_push($moveList, $ICICLE_SPEAR, $ROCK_BLAST, $TAKE_DOWN, $TWINEEDLE);
	}else if ($pokeNum == $gastly) {//92
		array_push($moveList, $ASTONISH, $CLEAR_SMOG, $DISABLE, $FIRE_PUNCH, $GRUDGE, $HAZE, $ICE_PUNCH, $PERISH_SONG, $PSYWAVE, $REFLECT_TYPE, $SCARY_FACE, $SMOG, $THUNDERPUNCH);
	}else if ($pokeNum == $onix) {//95
		array_push($moveList, $BLOCK, $DEFENSE_CURL, $FLAIL, $HEAVY_SLAM, $ROCK_BLAST, $ROCK_CLIMB, $ROLLOUT, $ROTOTILLER, $STEALTH_ROCK);
	}else if ($pokeNum == $drowzee) {//96
		array_push($moveList, $ASSIST, $BARRIER, $FIRE_PUNCH, $FLATTER, $GUARD_SWAP, $ICE_PUNCH, $NASTY_PLOT, $PSYCHO_CUT, $ROLE_PLAY, $SECRET_POWER, $SKILL_SWAP, $THUNDERPUNCH);
	}else if ($pokeNum == $krabby) {//98
		array_push($moveList, $AGILITY, $ALLY_SWITCH, $AMNESIA, $ANCIENTPOWER, $BIDE, $CHIP_AWAY, $ENDURE, $FLAIL, $HAZE, $KNOCK_OFF, $SLAM, $TICKLE);
	}else if ($pokeNum == $voltorb) {//100
		//NONE
	}else if ($pokeNum == $exeggcute) {//102
		array_push($moveList, $ANCIENTPOWER, $BLOCK, $CURSE, $GIGA_DRAIN, $GRASSY_TERRAIN, $INGRAIN, $LEAF_STORM, $LUCKY_CHANT, $MOONLIGHT, $NATURAL_GIFT, $NATURE_POWER, $POWER_SWAP, $SKILL_SWAP, $SYNTHESIS);
	}else if ($pokeNum == $cubone) {//104
		array_push($moveList, $ANCIENTPOWER, $BELLY_DRUM, $CHIP_AWAY, $DETECT, $DOUBLE_KICK, $ENDURE, $IRON_HEAD, $PERISH_SONG, $SCREECH, $SKULL_BASH);
	}else if ($pokeNum == $lickitung) {//108
		array_push($moveList, $BELLY_DRUM, $BODY_SLAM, $CURSE, $HAMMER_ARM, $MAGNITUDE, $MUDDY_WATER, $SLEEP_TALK, $SMELLING_SALT, $SNORE, $ZEN_HEADBUTT);
	}else if ($pokeNum == $koffing) {//109
		array_push($moveList, $CURSE, $DESTINY_BOND, $GRUDGE, $PAIN_SPLIT, $PSYBEAM, $PSYWAVE, $SCREECH, $SPITE, $SPIT_UP, $STOCKPILE, $SWALLOW, $TOXIC_SPIKES);
	}else if ($pokeNum == $rhyhorn) {//111
		array_push($moveList, $COUNTER, $CRUNCH, $CRUSH_CLAW, $CURSE, $DRAGON_RUSH, $FIRE_FANG, $GUARD_SPLIT, $ICE_FANG, $IRON_TAIL, $MAGNITUDE, $METAL_BURST, $ROTOTILLER, $REVERSAL, $ROCK_CLIMB, $SKULL_BASH, $THUNDER_FANG);
	}else if ($pokeNum == $chansey) { //113
		array_push($moveList, $AROMATHERAPY, $COUNTER, $ENDURE, $GRAVITY, $HEAL_BELL, $HELPING_HAND, $LAST_RESORT, $METRONOME, $MUD_BOMB, $NATURAL_GIFT, $PRESENT, $SEISMIC_TOSS);
	}else if ($pokeNum == $tangela) {//114
		array_push($moveList, $AMNESIA, $CONFUSION, $ENDEAVOR, $FLAIL, $GIGA_DRAIN, $LEAF_STORM, $LEECH_SEED, $MEGA_DRAIN, $NATURAL_GIFT, $NATURE_POWER, $POWER_SWAP, $RAGE_POWDER);
	}else if ($pokeNum == $kangaskhan) {//115
		array_push($moveList, $CIRCLE_THROW, $COUNTER, $CRUSH_CLAW, $DISABLE, $DOUBLE_EDGE, $ENDEAVOR, $FOCUS_ENERGY, $FOCUS_PUNCH, $FORESIGHT, $HAMMER_ARM, $STOMP, $TRUMP_CARD, $UPROAR);
	}else if ($pokeNum == $horsea) {//116
		array_push($moveList, $AURORA_BEAM, $CLEAR_SMOG, $DISABLE, $DRAGONBREATH, $DRAGON_RAGE, $FLAIL, $MUDDY_WATER, $OCTAZOOKA, $OUTRAGE, $RAZOR_WIND, $SIGNAL_BEAM, $SPLASH, $WATER_PULSE);
	}else if ($pokeNum == $goldeen) { //118
		array_push($moveList, $AQUA_TAIL, $BODY_SLAM, $HAZE, $HYDRO_PUMP, $MUD_SHOT, $MUD_SLAP, $MUD_SPORT, $PSYBEAM, $SIGNAL_BEAM, $SKULL_BASH, $SLEEP_TALK);
	}else if ($pokeNum == $staryu) { //120
		//NONE
	}else if ($pokeNum == $mrmime) {//122
		array_push($moveList, $CHARM, $CONFUSE_RAY, $FAKE_OUT, $FUTURE_SIGHT, $HEALING_WISH, $HYPNOSIS, $ICY_WIND, $MAGIC_ROOM, $MIMIC, $NASTY_PLOT, $POWER_SPLIT, $TEETER_DANCE, $TRICK, $WAKE_UP_SLAP);
	}else if ($pokeNum == $scyther) { //123
		array_push($moveList, $BATON_PASS, $BUG_BUZZ, $COUNTER, $DEFOG, $ENDURE, $NIGHT_SLASH, $QUICK_GUARD, $RAZOR_WIND, $REVERSAL, $SILVER_WIND, $STEEL_WING);
	}else if ($pokeNum == $pinsir) {//127
		array_push($moveList, $BUG_BITE, $CLOSE_COMBAT, $FAINT_ATTACK, $FEINT, $FLAIL, $FURY_ATTACK, $ME_FIRST, $QUICK_ATTACK, $SUPERPOWER);
	}else if ($pokeNum == $tauros) {//128
		//NONE
	}else if ($pokeNum == $magikarp) {//129
		//NONE
	}else if ($pokeNum == $lapras) {//131
		array_push($moveList, $ANCIENTPOWER, $AVALANCHE, $CURSE, $DRAGON_DANCE, $DRAGON_PULSE, $FISSURE, $FORESIGHT, $FREEZE_DRY, $FUTURE_SIGHT, $HORN_DRILL, $REFRESH, $SLEEP_TALK, $TICKLE, $WHIRLPOOL);
	}else if ($pokeNum == $ditto) { //132
		//NONE
	}else if ($pokeNum == $eevee) { //133
		array_push($moveList, $CAPTIVATE, $CHARM, $COVET, $CURSE, $DETECT, $ENDURE, $FAKE_TEARS, $FLAIL, $NATURAL_GIFT, $STORED_POWER, $SYNCHRONOISE, $TICKLE, $TRUMP_CARD, $WISH, $YAWN);
	}else if ($pokeNum == $porygon) {//137
		//NONE
	}else if ($pokeNum == $omanyte) {//138
		array_push($moveList, $AURORA_BEAM, $BIDE, $BUBBLEBEAM, $HAZE, $KNOCK_OFF, $MUDDY_WATER, $REFLECT_TYPE, $SLAM, $SPIKES, $SUPERSONIC, $TOXIC_SPIKES, $WATER_PULSE, $WHIRLPOOL, $WRING_OUT);
	}else if ($pokeNum == $kabuto) {//140
		array_push($moveList, $AURORA_BEAM, $BUBBLEBEAM, $CONFUSE_RAY, $FLAIL, $FORESIGHT, $GIGA_DRAIN, $ICY_WIND, $KNOCK_OFF, $MUD_SHOT, $RAPID_SPIN, $SCREECH, $TAKE_DOWN);
	}else if ($pokeNum == $aerodactyl) {//142
		array_push($moveList, $ASSURANCE, $CURSE, $DRAGONBREATH, $FORESIGHT, $PURSUIT, $ROOST, $STEEL_WING, $TAILWIND, $WHIRLWIND, $WIDE_GUARD);
	}else if ($pokeNum == $snorlax) {//143
		array_push($moveList, $AFTER_YOU, $CHARM, $COUNTER, $CURSE, $DOUBLE_EDGE, $FISSURE, $LICK, $NATURAL_GIFT, $PURSUIT, $SELFDESTRUCT, $WHIRLWIND, $ZEN_HEADBUTT);
	}else if ($pokeNum == $articuno) { //144
		//NONE
	}else if ($pokeNum == $zapdos) { //145
		//NONE
	}else if ($pokeNum == $moltres) { //146
		//NONE
	}else if ($pokeNum == $dratini) {//147
		array_push($moveList, $AQUA_JET, $DRAGONBREATH, $DRAGON_DANCE, $DRAGON_PULSE, $DRAGON_RUSH, $EXTREMESPEED, $HAZE, $IRON_TAIL, $MIST, $SUPERSONIC, $WATER_PULSE);
	}else if ($pokeNum == $mewtwo) { //150
		//NONE
	}else if ($pokeNum == $mew) { //151
		//NONE
	}else if ($pokeNum == $chikorita) {//152
		array_push($moveList, $ANCIENTPOWER, $AROMATHERAPY, $BODY_SLAM, $COUNTER, $FLAIL, $GRASSWHISTLE, $GRASSY_TERRAIN, $HEAL_PULSE, $INGRAIN, $LEAF_STORM, $LEECH_SEED, $NATURE_POWER, $REFRESH, $VINE_WHIP, $WRING_OUT);
	}else if ($pokeNum == $cyndaquil) {//155
		array_push($moveList, $COVET, $CRUSH_CLAW, $DOUBLE_EDGE, $DOUBLE_KICK, $EXTRASENSORY, $FLAME_BURST, $FLARE_BLITZ, $FORESIGHT, $FURY_SWIPES, $HOWL, $NATURE_POWER, $QUICK_ATTACK, $REVERSAL, $THRASH);
	}else if ($pokeNum == $totodile) {//158
		array_push($moveList, $ANCIENTPOWER, $AQUA_JET, $BLOCK, $CRUNCH, $DRAGON_DANCE, $FLATTER, $HYDRO_PUMP, $ICE_PUNCH, $METAL_CLAW, $MUD_SPORT, $THRASH, $WATER_PULSE, $WATER_SPORT);
	}else if ($pokeNum == $sentret) {//161
		array_push($moveList, $ASSIST, $CAPTIVATE, $CHARM, $COVET, $DOUBLE_EDGE, $FOCUS_ENERGY, $IRON_TAIL, $LAST_RESORT, $NATURAL_GIFT, $PURSUIT, $REVERSAL, $SLASH, $TRICK);
	}else if ($pokeNum == $hoothoot) {//163
		array_push($moveList, $tackle, $AGILITY, $DEFOG, $FEATHERDANCE, $FAINT_ATTACK, $MIRROR_MOVE, $NIGHT_SHADE, $SKY_ATTACK, $SUPERSONIC, $WHIRLWIND, $WING_ATTACK);
	}else if ($pokeNum == $ledyba) {//165
		array_push($moveList, $BIDE, $BUG_BITE, $BUG_BUZZ, $DIZZY_PUNCH, $DRAIN_PUNCH, $ENCORE, $FOCUS_PUNCH, $KNOCK_OFF, $PSYBEAM, $SCREECH, $SILVER_WIND, $TAILWIND);
	}else if ($pokeNum == $spinarak) {//167
		array_push($moveList, $BATON_PASS, $DISABLE, $ELECTROWEB, $MEGAHORN, $NIGHT_SLASH, $PSYBEAM, $PURSUIT, $RAGE_POWDER, $SIGNAL_BEAM, $SONICBOOM, $TOXIC_SPIKES, $TWINEEDLE);
	}else if ($pokeNum == $chinchou) { //170
		array_push($moveList, $AGILITY, $AMNESIA, $BRINE, $FLAIL, $MIST, $PSYBEAM, $SCREECH, $SHOCK_WAVE, $SOAK, $WATER_PULSE, $WHIRLPOOL);
	}else if ($pokeNum == $pichu) { //172
		array_push($moveList, $BESTOW, $BIDE, $CHARGE, $DISARMING_VOICE, $DOUBLE_SLAP, $ENCORE, $ENDURE, $FAKE_OUT, $FLAIL, $LUCKY_CHANT, $PRESENT, $REVERSAL, $THUNDERPUNCH, $TICKLE, $WISH);
	}else if ($pokeNum == $cleffa) { //173
		array_push($moveList, $AMNESIA, $AROMATHERAPY, $BELLY_DRUM, $COVET, $FAKE_TEARS, $HEAL_PULSE, $METRONOME, $MIMIC, $PRESENT, $SPLASH, $STORED_POWER, $TICKLE, $WISH);
	}else if ($pokeNum == $igglybuff) {//174
		array_push($moveList, $CAPTIVATE, $COVET, $FAKE_TEARS, $FAINT_ATTACK, $GRAVITY, $HEAL_PULSE, $LAST_RESORT, $PERISH_SONG, $PRESENT, $PUNISHMENT, $SLEEP_TALK, $WISH);
	}else if ($pokeNum == $togepi) {//175
		array_push($moveList, $EXTRASENSORY, $FORESIGHT, $FUTURE_SIGHT, $LUCKY_CHANT, $MIRROR_MOVE, $MORNING_SUN, $NASTY_PLOT, $PECK, $PRESENT, $PSYCHO_SHIFT, $SECRET_POWER, $STORED_POWER);
	}else if ($pokeNum == $natu) {//177
		array_push($moveList, $ALLY_SWITCH, $DRILL_PECK, $FEATHERDANCE, $FAINT_ATTACK, $HAZE, $QUICK_ATTACK, $REFRESH, $ROOST, $SIMPLE_BEAM, $SKILL_SWAP, $STEEL_WING, $SUCKER_PUNCH, $SYNCHRONOISE, $ZEN_HEADBUTT);
	}else if ($pokeNum == $mareep) {//179
		array_push($moveList, $AFTER_YOU, $AGILITY, $BODY_SLAM, $CHARGE, $EERIE_IMPULSE, $FLATTER, $IRON_TAIL, $ODOR_SLEUTH, $SAND_ATTACK, $SCREECH, $TAKE_DOWN);
	}else if ($pokeNum == $marill) {//183
		array_push($moveList, $AMNESIA, $AQUA_JET, $BELLY_DRUM, $BODY_SLAM, $CAMOUFLAGE, $COPYCAT, $ENCORE, $FAKE_TEARS, $FUTURE_SIGHT, $MUDDY_WATER, $PERISH_SONG, $PRESENT, $REFRESH, $SING, $SLAM, $SOAK, $SUPERPOWER, $SUPERSONIC, $TICKLE, $WATER_SPORT);
	}else if ($pokeNum == $sudowoodo) {//185
		array_push($moveList, $CURSE, $DEFENSE_CURL, $ENDURE, $HARDEN, $HEADBUTT, $ROLLOUT, $SAND_TOMB, $SELFDESTRUCT, $STEALTH_ROCK);
	}else if ($pokeNum == $hoppip) {//187
		array_push($moveList, $AMNESIA, $AROMATHERAPY, $CONFUSION, $COTTON_GUARD, $DOUBLE_EDGE, $ENCORE, $GRASSY_TERRAIN, $HELPING_HAND, $SEED_BOMB, $WORRY_SEED);
	}else if ($pokeNum == $aipom) {//190
		array_push($moveList, $AGILITY, $BOUNCE, $COUNTER, $COVET, $DOUBLE_SLAP, $FAKE_OUT, $PURSUIT, $QUICK_GUARD, $REVENGE, $SCREECH, $SLAM, $SPITE, $SWITCHEROO);
	}else if ($pokeNum == $sunkern) {//191
		array_push($moveList, $BIDE, $CURSE, $ENCORE, $ENDURE, $GRASSWHISTLE, $GRASSY_TERRAIN, $HELPING_HAND, $INGRAIN, $LEECH_SEED, $MORNING_SUN, $NATURAL_GIFT, $NATURE_POWER, $SWEET_SCENT);
	}else if ($pokeNum == $yanma) {//193
		array_push($moveList, $DOUBLE_EDGE, $FEINT, $FAINT_ATTACK, $LEECH_LIFE, $PURSUIT, $REVERSAL, $SECRET_POWER, $SIGNAL_BEAM, $SILVER_WIND, $WHIRLWIND);
	}else if ($pokeNum == $wooper) {//194
		array_push($moveList, $ACID_SPRAY, $AFTER_YOU, $ANCIENTPOWER, $BODY_SLAM, $COUNTER, $CURSE, $DOUBLE_KICK, $EERIE_IMPULSE, $ENCORE, $GUARD_SWAP, $MUD_SPORT, $RECOVER, $SLEEP_TALK, $SPIT_UP, $STOCKPILE, $SWALLOW);
	}else if ($pokeNum == $murkrow) {//198
		array_push($moveList, $ASSURANCE, $BRAVE_BIRD, $CONFUSE_RAY, $DRILL_PECK, $FEATHERDANCE, $FAINT_ATTACK, $FLATTER, $MIRROR_MOVE, $PERISH_SONG, $PSYCHO_SHIFT, $ROOST, $SCREECH, $SKY_ATTACK, $WHIRLWIND, $WING_ATTACK);
	}else if ($pokeNum == $misdreavus) {//200
		array_push($moveList, $CURSE, $DESTINY_BOND, $IMPRISON, $ME_FIRST, $MEMENTO, $NASTY_PLOT, $OMINOUS_WIND, $SCREECH, $SHADOW_SNEAK, $SKILL_SWAP, $SPITE, $SUCKER_PUNCH, $WONDER_ROOM);
	}else if ($pokeNum == $unown) {//201
		//none
	}else if ($pokeNum == $wobbuffet) {//202
		//none
	}else if ($pokeNum == $girafarig) {//203
		array_push($moveList, $AMNESIA, $BEAT_UP, $DOUBLE_KICK, $FORESIGHT, $FUTURE_SIGHT, $MAGIC_COAT, $MEAN_LOOK, $MIRROR_COAT, $RAZOR_WIND, $SECRET_POWER, $SKILL_SWAP, $TAKE_DOWN, $WISH);
	}else if ($pokeNum == $pineco) {//204
		array_push($moveList, $COUNTER, $DOUBLE_EDGE, $ENDURE, $FLAIL, $PIN_MISSILE, $POWER_TRICK, $REVENGE, $SAND_TOMB, $STEALTH_ROCK, $SWIFT, $TOXIC_SPIKES);
	}else if ($pokeNum == $dunsparce) {
		array_push($moveList, $AGILITY, $ANCIENTPOWER, $ASTONISH, $BITE, $BIDE, $CURSE, $HEADBUTT, $HEX, $MAGIC_COAT, $SECRET_POWER, $SLEEP_TALK, $SNORE, $TRUMP_CARD);
	}else if ($pokeNum == $gligar) {//207
		array_push($moveList, $AGILITY, $BATON_PASS, $COUNTER, $CROSS_POISON, $DOUBLE_EDGE, $FEINT, $METAL_CLAW, $NIGHT_SLASH, $POISON_TAIL, $POWER_TRICK, $RAZOR_WIND, $ROCK_CLIMB, $SAND_TOMB, $WING_ATTACK);
	}else if ($pokeNum == $snubbull) {//209
		array_push($moveList, $CLOSE_COMBAT, $CRUNCH, $DOUBLE_EDGE, $FAKE_TEARS, $FAINT_ATTACK, $FIRE_FANG, $FOCUS_PUNCH, $HEAL_BELL, $ICE_FANG, $METRONOME, $MIMIC, $PRESENT, $SMELLING_SALT, $SNORE, $THUNDER_FANG);
	}else if ($pokeNum == $qwilfish) {//211
		array_push($moveList, $ACID_SPRAY, $AQUA_JET, $ASTONISH, $BRINE, $BUBBLEBEAM, $FLAIL, $HAZE, $SIGNAL_BEAM, $SUPERSONIC, $WATER_PULSE);
	}else if ($pokeNum == $shuckle) {//213
		array_push($moveList, $ACID, $ACUPRESSURE, $FINAL_GAMBIT, $HELPING_HAND, $KNOCK_OFF, $MUD_SLAP, $ROCK_BLAST, $SAND_TOMB, $SWEET_SCENT);
	}else if ($pokeNum == $heracross) {//214
		array_push($moveList, $BIDE, $DOUBLE_EDGE, $FLAIL, $FOCUS_PUNCH, $HARDEN, $MEGAHORN, $PURSUIT, $REVENGE, $ROCK_BLAST, $SEISMIC_TOSS);
	}else if ($pokeNum == $sneasel) {//215
		array_push($moveList, $ASSIST, $AVALANCHE, $BITE, $COUNTER, $CRUSH_CLAW, $DOUBLE_HIT, $FAKE_OUT, $FEINT, $FORESIGHT, $ICE_PUNCH, $ICE_SHARD, $ICICLE_CRASH, $PUNISHMENT, $PURSUIT, $SPITE);
	}else if ($pokeNum == $teddiursa) { //216
		array_push($moveList, $BELLY_DRUM, $CHIP_AWAY, $CLOSE_COMBAT, $COUNTER, $CROSS_CHOP, $CRUNCH, $DOUBLE_EDGE, $FAKE_TEARS, $METAL_CLAW, $NIGHT_SLASH, $PLAY_ROUGH, $SEISMIC_TOSS, $SLEEP_TALK, $TAKE_DOWN, $YAWN);
	}else if ($pokeNum == $slugma) {//218
		array_push($moveList, $ACID_ARMOR, $CURSE, $GUARD_SWAP, $HEAT_WAVE, $INFERNO, $MEMENTO, $ROLLOUT, $SMOKESCREEN, $SPIT_UP, $STOCKPILE, $SWALLOW);
	}else if ($pokeNum == $swinub) {//220
		array_push($moveList, $ANCIENTPOWER, $AVALANCHE, $BITE, $BODY_SLAM, $CURSE, $DOUBLE_EDGE, $FISSURE, $FREEZE_DRY, $ICICLE_CRASH, $ICICLE_SPEAR, $MUD_SHOT, $STEALTH_ROCK, $TAKE_DOWN);
	}else if ($pokeNum == $corsola) {//222
		array_push($moveList, $AMNESIA, $AQUA_RING, $BARRIER, $BIDE, $CAMOUFLAGE, $CONFUSE_RAY, $CURSE, $HEAD_SMASH, $ICICLE_SPEAR, $INGRAIN, $MIST, $NATURE_POWER, $SCREECH, $WATER_PULSE);
	}else if ($pokeNum == $remoraid) {//223
		array_push($moveList, $ACID_SPRAY, $AURORA_BEAM, $ENTRAINMENT, $FLAIL, $HAZE, $MUD_SHOT, $OCTAZOOKA, $ROCK_BLAST, $SCREECH, $SNORE, $SUPERSONIC, $SWIFT, $WATER_PULSE, $WATER_SPOUT);
	}else if ($pokeNum == $delibird) {//225
		array_push($moveList, $AURORA_BEAM, $BESTOW, $DESTINY_BOND, $FREEZE_DRY, $ICE_BALL, $ICE_SHARD, $RAPID_SPIN, $SPIKES);
	}else if ($pokeNum == $mantine) {//226
		array_push($moveList, $AMNESIA, $HAZE, $HYDRO_PUMP, $MIRROR_COAT, $MUD_SPORT, $SIGNAL_BEAM, $SLAM, $SPLASH, $TAILWIND, $TWISTER, $WATER_SPORT, $WIDE_GUARD);
	}else if ($pokeNum == $skarmony) {//227
		array_push($moveList, $ASSURANCE, $BRAVE_BIRD, $CURSE, $DRILL_PECK, $ENDURE, $GUARD_SWAP, $PURSUIT, $SKY_ATTACK, $STEALTH_ROCK, $WHIRLWIND);
	}else if ($pokeNum == $houndour) {//228
		array_push($moveList, $BEAT_UP, $COUNTER, $DESTINY_BOND, $FEINT, $FIRE_FANG, $FIRE_SPIN, $NASTY_PLOT, $PUNISHMENT, $PURSUIT, $RAGE, $REVERSAL, $SPITE, $SUCKER_PUNCH, $THUNDER_FANG);
	}else if ($pokeNum == $phanpy) {//231
		array_push($moveList, $ANCIENTPOWER, $BODY_SLAM, $COUNTER, $ENDEAVOR, $FISSURE, $FOCUS_ENERGY, $HEAD_SMASH, $HEAVY_SLAM, $ICE_SHARD, $MUD_SLAP, $PLAY_ROUGH, $SNORE);
	}else if ($pokeNum == $stantler) {//234
		array_push($moveList, $BITE, $DISABLE, $DOUBLE_KICK, $EXTRASENSORY, $MEGAHORN, $ME_FIRST, $MUD_SPORT, $RAGE, $SPITE, $THRASH, $ZEN_HEADBUTT);
	}else if ($pokeNum == $smeargle) { //235
		//NONE
	}else if ($pokeNum == $tyrogue) {//236
		array_push($moveList, $BULLET_PUNCH, $COUNTER, $ENDURE, $FEINT, $HELPING_HAND, $HI_JUMP_KICK, $MACH_PUNCH, $MIND_READER, $PURSUIT, $RAPID_SPIN, $VACUUM_WAVE);
	}else if ($pokeNum == $smoochum) {//238
		array_push($moveList, $CAPTIVATE, $FAKE_OUT, $ICE_PUNCH, $MEDITATE, $MIRACLE_EYE, $NASTY_PLOT, $WAKE_UP_SLAP, $WISH);
	}else if ($pokeNum == $elekid) {//239
		array_push($moveList, $BARRIER, $CROSS_CHOP, $DYNAMICPUNCH, $FEINT, $FIRE_PUNCH, $FOCUS_PUNCH, $HAMMER_ARM, $ICE_PUNCH, $KARATE_CHOP, $MEDITATE, $ROLLING_KICK);
	}else if ($pokeNum == $magby) {//240
		array_push($moveList, $BARRIER, $BELLY_DRUM, $CROSS_CHOP, $DYNAMICPUNCH, $FLARE_BLITZ, $FOCUS_ENERGY, $IRON_TAIL, $KARATE_CHOP, $MACH_PUNCH, $MEGA_PUNCH, $POWER_SWAP, $SCREECH, $THUNDERPUNCH);
	}else if ($pokeNum == $miltank) {//241
		array_push($moveList, $CURSE, $DIZZY_PUNCH, $DOUBLE_EDGE, $ENDURE, $HAMMER_ARM, $HEART_STAMP, $HELPING_HAND, $NATURAL_GIFT, $PRESENT, $PUNISHMENT, $REVERSAL, $SEISMIC_TOSS, $SLEEP_TALK);
	}else if ($pokeNum == $raikou) {//243
		//none
	}else if ($pokeNum == $entei) {//244
		//none
	}else if ($pokeNum == $suicune) {//245
		//none
	}else if ($pokeNum == $larvitar) {//246
		array_push($moveList, $ANCIENTPOWER, $ASSURANCE, $CURSE, $DRAGON_DANCE, $FOCUS_ENERGY, $IRON_DEFENSE, $IRON_HEAD, $IRON_TAIL, $OUTRAGE, $PURSUIT, $STEALTH_ROCK, $STOMP);
	}else if ($pokeNum == $lugia) {//249
		//none
	}else if ($pokeNum == $ho_oh) {//250
		//none
	}else if ($pokeNum == $celebi) { //251
		//none
	}else if ($pokeNum == $azurill) {//298
		array_push($moveList, $BODY_SLAM, $CAMOUFLAGE, $COPYCAT, $ENCORE, $FAKE_TEARS, $MUDDY_WATER, $REFRESH, $SING, $SLAM, $SOAK, $TICKLE, $WATER_SPORT);
	}else if ($pokeNum == $wynaut) {//360
		//none
	}else if ($pokeNum == $bonsly) {//438
		array_push($moveList, $CURSE, $DEFENSE_CURL, $ENDURE, $HARDEN, $HEADBUTT, $ROLLOUT, $SAND_TOMB, $SELFDESTRUCT, $STEALTH_ROCK);
	}else if ($pokeNum == $mimejr) {//439
		array_push($moveList, $CHARM, $CONFUSE_RAY, $FAKE_OUT, $FUTURE_SIGHT, $HEALING_WISH, $HYPNOSIS, $ICY_WIND, $MAGIC_ROOM, $MIMIC, $NASTY_PLOT, $POWER_SPLIT, $TEETER_DANCE, $TRICK, $WAKE_UP_SLAP);
	}else if ($pokeNum == $happiny) {//440
		array_push($moveList, $AROMATHERAPY, $COUNTER, $ENDURE, $GRAVITY, $HEAL_BELL, $HELPING_HAND, $LAST_RESORT, $METRONOME, $MUD_BOMB, $NATURAL_GIFT, $PRESENT);
	}else if ($pokeNum == $munchlax) {//446
		array_push($moveList, $AFTER_YOU, $CHARM, $COUNTER, $CURSE, $DOUBLE_EDGE, $FISSURE, $LICK, $NATURAL_GIFT, $PURSUIT, $SELFDESTRUCT, $WHIRLWIND, $ZEN_HEADBUTT);
	}else if ($pokeNum == $mantyke) {//458
		array_push($moveList, $AMNESIA, $HAZE, $HYDRO_PUMP, $MIRROR_COAT, $MUD_SPORT, $SIGNAL_BEAM, $SLAM, $SPLASH, $TAILWIND, $TWISTER, $WATER_SPORT, $WIDE_GUARD);
	}
	return $moveList;
}
function is_Same_Family($maleNum, $femaleNum) {
	$familyList = get_Family($maleNum);
	return is_In_This_Family($femaleNum, $familyList);
}
function get_New_Egg_Moves($pokeNum) {
	require 'pokeList.php';
	$moveList = array();
	$tackle = 1;
	$sand_attack = 2;
	$tail_whip = 3;
	$quick_attack = 4;
	$growl = 5;
	$scratch = 6;
	$string_shot = 7;
	$poison_sting = 8;
	$leech_seed = 9;
	$ember = 10;
	$bubble = 11;
	$focus_energy = 12;
	$harden = 14;
	$gust = 15;
	$vine_whip = 16;
	$smokescreen = 17;
	$withdraw = 18;
	$bite = 19;
	$confusion = 20;
	$thundershock = 22;
	$thunderwave = 23;
	$defense_curl = 25;
	$take_down = 27;
	$dragon_rage = 29;
	$scary_face = 30;
	$water_gun = 31;
	$pursuit = 33;
	$whirlwind = 36;
	$supersonic = 38;
	$rage = 40;
	$peck = 42;
	$leer = 43;
	$sing = 47;
	$pound = 48;
	$disable = 49;
	$round = 50;
	$low_kick = 51;
	$seismic_toss = 53;
	$leech_life = 54;
	$fury_cutter = 55;
	$transform = 56;
	$reflect_type = 57;
	$minimize = 63;
	$mud_sport = 64;
	$rollout = 68;
	$wrap = 70;
	$glare = 71;
	$screech = 72;
	$astonish = 74;
	$wing_attack = 75;
	$sweet_scent = 76;
	$twister = 77;
	$double_team = 79;
	$horn_attack = 80;
	$confuse_ray = 81;
	$protect = 83;
	$growth = 91;
	$fire_fang = 92;
	$water_pulse = 93;
	$agility = 97;
	$helping_hand = 99;
	$roar = 101;
	$odor_sleuth = 102;
	$absorb = 107;
	$water_sport = 109;
	$hypnosis = 110;
	$recover = 115;
	$teleport = 116;
	$miracle_eye = 117;
	$lick = 119;
	$powder_snow = 121;
	$ice_punch = 122;
	$safeguard = 129;
	$knock_off = 131;
	$cosmic_power = 138;
	$mud_shot = 139;
	$splash = 141;
	$rest = 143;
	$flail = 147;
	$bulldoze = 155;
	$roost = 158;
	$iron_defense = 165;
	$feint = 168;
	$fake_out = 175;
	$taunt = 177;
	$swords_dance = 180;
	$barrier = 197;
	$bind = 198;
	$rock_tomb = 199;
	$camouflage = 200;
	$constrict = 201;
	$poison_gas = 208;
	$copycat = 215;
	$headbutt = 216;
	$mud_slap = 217;
	$dragonbreath = 222;
	$flash = 225;
	$foresight = 227;
	$sonicboom = 231;
	$spark = 232;
	$charge_beam = 233;
	$magnet_rise = 234;
	$aromatheraphy = 235;
	$explosion = 238;
	$thunder_fang = 249;
	$magical_leaf = 255;
	$charge = 257;
	$vacuum_wave = 266;
	$razor_wind = 267;
	$vicegrip = 269;
	$punishment = 277;
	$curse = 280;
	$mirror_coat = 294;
	$ancientpower = 296;
	$metal_sound = 299;
	$night_shade = 307;
	$destiny_bond = 311;
	$thunderpunch = 316;
	$fire_punch = 317;
	$hurricane = 318;
	$metal_claw = 326;
	$stomp = 327;
	$smog = 334;
	$yawn = 340;
	$searing_shot = 352;
	$incinerate = 353;
	$endure = 354;
	$comet_punch = 361;
	$detect = 365;
	$counter = 366;
	$icicle_spear = 369;
	$clamp = 370;
	$whirlpool = 372;
	$shell_smash = 373;
	$spikes = 375;
	$aqua_jet = 378;
	$uproar = 383;
	$heal_pulse = 386;
	$wood_hammer = 388;
	$barrage = 391;
	$dizzy_punch = 395;
	$ingrain = 401;
	$icy_wind = 407;
	$sharpen = 423;
	$natural_gift = 434;
	$hone_claws = 444;
	$spider_web = 451;
	$weather_ball = 453;
	$spite = 455;
	$charm = 456;
	$wish = 462;
	$bide = 478;
	$struggle_bug = 479;
	$ominous_wind = 489;
	$morning_sun = 491;
	$psywave = 498;
	$trick = 502;
	$heal_bell = 512;
	$block = 513;
	$rock_smash = 539;
	$sketch = 560;
	$present = 562;
	$fairy_lock = 577;
	$fairy_wind = 578;
	$tail_glow = 643;
	
	if ($pokeNum == $caterpie) {
		array_push($moveList, $tackle, $string_shot, 0, 0);
	}else if ($pokeNum == $bulbasaur) {
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $charmander) {
		array_push($moveList, $scratch, $growl, 0, 0);
	}else if ($pokeNum == $squirtle) {
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $weedle) {
		array_push($moveList, $poison_sting, $string_shot, 0, 0);
	}else if ($pokeNum == $pidgey) {
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $rattata) {
		array_push($moveList, $tackle, $tail_whip, 0, 0);
	}else if ($pokeNum == $spearow) {
		array_push($moveList, $peck, $growl, 0, 0);
	}else if ($pokeNum == $ekans) {
		array_push($moveList, $wrap, $leer, 0, 0);
	}else if ($pokeNum == $sandshrew) {//27
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $nidoran_f) { //29
		array_push($moveList, $scratch, $growl, 0, 0);
	}else if ($pokeNum == $nidoran_m) { //32
		array_push($moveList, $peck, $leer, 0, 0);
	}else if ($pokeNum == $igglybuff) {
		array_push($moveList, $sing, $charm, 0, 0);
	}else if ($pokeNum == $rattata) {
		array_push($moveList, $tackle, $tail_whip, 0, 0);
	}else if ($pokeNum == $vulpix) {//37
		array_push($moveList, $ember, 0, 0, 0);
	}else if ($pokeNum == $zubat) {
		array_push($moveList, $leech_life, 0, 0, 0);
	}else if ($pokeNum == $oddish) {//43
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $paras) {//46
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $venonat) {//48
		array_push($moveList, $tackle, $disable, $foresight, 0);
	}else if ($pokeNum == $diglett) {//50
		array_push($moveList, $scratch, $sand_attack, 0, 0);
	}else if ($pokeNum == $meowth) {//52
		array_push($moveList, $scratch, $growl, 0, 0);
	}else if ($pokeNum == $psyduck) {//54
		array_push($moveList, $scratch, $water_sport, 0, 0);
	}else if ($pokeNum == $mankey) {//56
		array_push($moveList, $scratch, $low_kick, $leer, $focus_energy);
	}else if ($pokeNum == $growlithe) {//58
		array_push($moveList, $bite, $roar, 0, 0);
	}else if ($pokeNum == $poliwag) {//60
		array_push($moveList, $bubble, $water_sport, 0, 0);
	}else if ($pokeNum == $abra) {//63
		array_push($moveList, $teleport, 0, 0, 0);
	}else if ($pokeNum == $machop) {//64
		array_push($moveList, $low_kick, $leer, 0, 0);
	}else if ($pokeNum == $bellsprout) {//69
		array_push($moveList, $vine_whip, 0, 0, 0);
	}else if ($pokeNum == $tentacool) {//72
		array_push($moveList, $poison_sting, 0, 0, 0);
	}else if ($pokeNum == $geodude) {//74
		array_push($moveList, $tackle, $defense_curl, 0, 0);
	}else if ($pokeNum == $ponyta) {//77
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $slowpoke) {//79
		array_push($moveList, $tackle, $curse, $yawn, 0);
	}else if ($pokeNum == $magnemite) {//81
		array_push($moveList, $tackle, $metal_sound, 0, 0);
	}else if ($pokeNum == $farfetchd) {//83
		array_push($moveList, $peck, $sand_attack, $leer, $fury_cutter);
	}else if ($pokeNum == $doduo) {//84
		array_push($moveList, $peck, $growl, 0, 0);
	}else if ($pokeNum == $seel) {//86
		array_push($moveList, $headbutt, 0, 0, 0);
	}else if ($pokeNum == $grimer) {//88
		array_push($moveList, $pound, $poison_gas, 0, 0);
	}else if ($pokeNum == $shellder) {//90
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $gastly) {//92
		array_push($moveList, $hypnosis, $lick, 0, 0);
	}else if ($pokeNum == $onix) {//95
		array_push($moveList, $tackle, $mud_sport, $harden, $bind);
	}else if ($pokeNum == $drowzee) {//96
		array_push($moveList, $pound, $hypnosis, 0, 0);
	}else if ($pokeNum == $krabby) {//98
		array_push($moveList, $bubble, $mud_sport, 0, 0);
	}else if ($pokeNum == $voltorb) {//100
		array_push($moveList, $tackle, $charge, 0, 0);
	}else if ($pokeNum == $exeggcute) {//102
		array_push($moveList, $barrage, $uproar, $hypnosis, 0);
	}else if ($pokeNum == $cubone) {//104
		array_push($moveList, $growl, 0, 0, 0);
	}else if ($pokeNum == $lickitung) {//108
		array_push($moveList, $lick, 0, 0, 0);
	}else if ($pokeNum == $koffing) {//109
		array_push($moveList, $tackle, $poison_gas, 0, 0);
	}else if ($pokeNum == $rhyhorn) {//111
		array_push($moveList, $horn_attack, $tail_whip, 0, 0);
	}else if ($pokeNum == $chansey) { //113
		array_push($moveList, $pound, $defense_curl, $growl, 0);
	}else if ($pokeNum == $tangela) {//114
		array_push($moveList, $constrict, $ingrain, 0, 0);
	}else if ($pokeNum == $kangaskhan) {//115
		array_push($moveList, $comet_punch, $leer, 0, 0);
	}else if ($pokeNum == $horsea) {//116
		array_push($moveList, $bubble, 0, 0, 0);
	}else if ($pokeNum == $goldeen) { //118
		array_push($moveList, $peck, $tail_whip, $water_sport, 0);
	}else if ($pokeNum == $staryu) { //120
		array_push($moveList, $tackle, $harden, 0, 0);
	}else if ($pokeNum == $mrmime) {//122
		array_push($moveList, $confusion, $barrier, 0, 0);
	}else if ($pokeNum == $scyther) { //123
		array_push($moveList, $vacuum_wave, $quick_attack, $leer, 0);
	}else if ($pokeNum == $pinsir) {//127
		array_push($moveList, $vicegrip, $focus_energy, 0, 0);
	}else if ($pokeNum == $tauros) {//128
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $magikarp) {//129
		array_push($moveList, $splash, 0, 0, 0);
	}else if ($pokeNum == $lapras) {//131
		array_push($moveList, $water_gun, $sing, $growl, 0);
	}else if ($pokeNum == $ditto) { //132
		array_push($moveList, $transform, 0, 0, 0);
	}else if ($pokeNum == $eevee) { //133
		array_push($moveList, $tackle, $helping_hand, $tail_whip, 0);
	}else if ($pokeNum == $porygon) {//137
		array_push($moveList, $tackle, $sharpen, 0, 0);
	}else if ($pokeNum == $omanyte) {//138
		array_push($moveList, $constrict, $withdraw, 0, 0);
	}else if ($pokeNum == $kabuto) {//140
		array_push($moveList, $scratch, $harden, 0, 0);
	}else if ($pokeNum == $aerodactyl) {//142
		array_push($moveList, $bite, $scary_face, 0, 0);
	}else if ($pokeNum == $snorlax) {//143
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $articuno) { //144
		array_push($moveList, $gust, $powder_snow, 0, 0);
	}else if ($pokeNum == $zapdos) { //145
		array_push($moveList, $peck, $thundershock, 0, 0);
	}else if ($pokeNum == $moltres) { //146
		array_push($moveList, $wing_attack, $ember, 0, 0);
	}else if ($pokeNum == $dratini) {//147
		array_push($moveList, $wrap, $leer, 0, 0);
	}else if ($pokeNum == $mewtwo) { //150
		array_push($moveList, $confusion, $disable, $barrier, 0);
	}else if ($pokeNum == $mew) { //151
		array_push($moveList, $pound, $reflect_type, $transform, 0);
	}else if ($pokeNum == $chikorita) {//152
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $cyndaquil) {
		array_push($moveList, $tackle, $leer, 0, 0);
	}else if ($pokeNum == $totodile) {
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $sentret) {
		array_push($moveList, $scratch, $foresight, 0, 0);
	}else if ($pokeNum == $hoothoot) {
		array_push($moveList, $tackle, $growl, $foresight, 0);
	}else if ($pokeNum == $ledyba) {
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $spinarak) {
		array_push($moveList, $poison_sting, $string_shot, 0, 0);
	}else if ($pokeNum == $chinchou) { //170
		array_push($moveList, $bubble, $supersonic, 0, 0);
	}else if ($pokeNum == $pichu) { //172
		array_push($moveList, $thundershock, 0, 0, 0);
	}else if ($pokeNum == $cleffa) { //173
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $togepi) { //175
		array_push($moveList, $growl, $charm, 0, 0);
	}else if ($pokeNum == $natu) { //177
		array_push($moveList, $peck, $leer, 0, 0);
	}else if ($pokeNum == $mareep) {//179
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $marill) {//183
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $sudowoodo) {//185
		array_push($moveList, $low_kick, $copycat, $wood_hammer, $flail);
	}else if ($pokeNum == $aipom) {//190
		array_push($moveList, $scratch, $tail_whip, 0, 0);
	}else if ($pokeNum == $sunkern) {//191
		array_push($moveList, $absorb, $growth, 0, 0);
	}else if ($pokeNum == $yanma) {//193
		array_push($moveList, $tackle, $foresight, 0, 0);
	}else if ($pokeNum == $hoppip) {
		array_push($moveList, $splash, 0, 0, 0);
	}else if ($pokeNum == $wooper) {
		array_push($moveList, $water_gun, $tail_whip, 0, 0);
	}else if ($pokeNum == $murkrow) {//198
		array_push($moveList, $peck, $astonish, 0, 0);
	}else if ($pokeNum == $misdreavus) {//200
		array_push($moveList, $psywave, $growl, 0, 0);
	}else if ($pokeNum == $wobbuffet) {//202
		array_push($moveList, $counter, $mirror_coat, $safeguard, $destiny_bond);
	}else if ($pokeNum == $girafarig) {//203
		array_push($moveList, $tackle, $confusion, $astonish, $growl);
	}else if ($pokeNum == $pineco) {//204
		array_push($moveList, $tackle, $protect, 0, 0);
	}else if ($pokeNum == $dunsparce) {
		array_push($moveList, $rage, 0, 0, 0);
	}else if ($pokeNum == $gligar) {//207
		array_push($moveList, $poison_sting, 0, 0, 0);
	}else if ($pokeNum == $snubbull) {//209
		array_push($moveList, $tackle, $scary_face, $tail_whip, $charm);
	}else if ($pokeNum == $qwilfish) {//211
		array_push($moveList, $tackle, $poison_sting, $spikes, 0);
	}else if ($pokeNum == $shuckle) {//213
		array_push($moveList, $struggle_bug, $withdraw, $constrict, $bide);
	}else if ($pokeNum == $heracross) {//214
		array_push($moveList, $tackle, $leer, $horn_attack, $endure);
	}else if ($pokeNum == $sneasel) {//215
		array_push($moveList, $scratch, $leer, $taunt, 0);
	}else if ($pokeNum == $teddiursa) { //216
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $slugma) {//218
		array_push($moveList, $smog, $yawn, 0, 0);
	}else if ($pokeNum == $swinub) {//220
		array_push($moveList, $tackle, $odor_sleuth, 0, 0);
	}else if ($pokeNum == $corsola) {//222
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $remoraid) {//223
		array_push($moveList, $water_gun, 0, 0, 0);
	}else if ($pokeNum == $delibird) {//225
		array_push($moveList, $present, 0, 0, 0);
	}else if ($pokeNum == $mantine) {//226
		array_push($moveList, $tackle, $bubble, 0, 0);
	}else if ($pokeNum == $skarmony) {//227
		array_push($moveList, $peck, $leer, 0, 0);
	}else if ($pokeNum == $houndour) {//228
		array_push($moveList, $ember, $leer, 0, 0);
	}else if ($pokeNum == $phanpy) {//231
		array_push($moveList, $odor_sleuth, $tackle, $growl, $defense_curl);
	}else if ($pokeNum == $stantler) {//234
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $smeargle) { //235
		array_push($moveList, $sketch, 0, 0, 0);
	}else if ($pokeNum == $tyrogue) {//236
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $smoochum) {//238
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $elekid) {//239
		array_push($moveList, $quick_attack, $leer, 0, 0);
	}else if ($pokeNum == $magby) {//240
		array_push($moveList, $smog, $leer, 0, 0);
	}else if ($pokeNum == $miltank) {//241
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $raikou) {//243
		array_push($moveList, $bite, $leer, 0, 0);
	}else if ($pokeNum == $entei) {//244
		array_push($moveList, $bite, $leer, 0, 0);
	}else if ($pokeNum == $suicune) {//245
		array_push($moveList, $bite, $leer, 0, 0);
	}else if ($pokeNum == $larvitar) {//246
		array_push($moveList, $bite, $leer, 0, 0);
	}else if ($pokeNum == $lugia) {//249
		array_push($moveList, $weather_ball, $whirlwind, 0, 0);
	}else if ($pokeNum == $ho_oh) {//250
		array_push($moveList, $weather_ball, $whirlwind, 0, 0);
	}else if ($pokeNum == $celebi) { //251
		array_push($moveList, $confusion, $leech_seed, $recover, $heal_bell);
	}else if ($pokeNum == $treecko) {//252
		array_push($moveList, $pound, $leer,0, 0);
	}else if ($pokeNum == $torchic) {//255
		array_push($moveList, $scratch, $growl,0, 0);
	}else if ($pokeNum == $mudkip) {//258
		array_push($moveList, $tackle, $growl,0, 0);
	}else if ($pokeNum == $poochyena) {//261
		array_push($moveList, $tackle, 0,0, 0);
	}else if ($pokeNum == $zigzagoon) {//263
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $wurmple) {//265
		array_push($moveList, $tackle, $string_shot, 0, 0);
	}else if ($pokeNum == $lotad) {//270
		array_push($moveList, $astonish, 0,0, 0);
	}else if ($pokeNum == $seedot) {//273
		array_push($moveList, $bide, 0,0, 0);
	}else if ($pokeNum == $taillow) {//276
		array_push($moveList, $peck, $growl, 0, 0);
	}else if ($pokeNum == $wingull) {//278
		array_push($moveList, $water_gun, $growl, 0, 0);
	}else if ($pokeNum == $ralts) {//280
		array_push($moveList, $growl, 0, 0, 0);
	}else if ($pokeNum == $surskit) {//283
		array_push($moveList, $bubble, 0, 0, 0);
	}else if ($pokeNum == $shroomish) {//285
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $slakoth) {//287
		array_push($moveList, $scratch, $yawn, 0, 0);
	}else if ($pokeNum == $nincada) {//290
		array_push($moveList, $scratch, $harden, 0, 0);
	}else if ($pokeNum == $whismur) {//293
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $makuhita) {//296
		array_push($moveList, $tackle, $focus_energy, 0, 0);
	}else if ($pokeNum == $nosepass) {//299
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $skitty) {//300
		array_push($moveList, $tackle, $tail_whip, $growl, $fake_out);
	}else if ($pokeNum == $sableye) {//302
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $mawile) {//303
		array_push($moveList, $astonish, 0, 0, 0);
	}else if ($pokeNum == $aron) {//304
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $meditite) {//307
		array_push($moveList, $bide, 0, 0, 0);
	}else if ($pokeNum == $electrike) {//309
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $plusle) {//311
		array_push($moveList, $growl, 0, 0, 0);
	}else if ($pokeNum == $minun) {//312
		array_push($moveList, $growl, 0, 0, 0);
	}else if ($pokeNum == $volbeat) {//313
		array_push($moveList, $tackle, $flash, 0, 0);
	}else if ($pokeNum == $illumise) {//314
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $roselia) {//315
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $gulpin) {//316
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $carvanha) {//318
		array_push($moveList, $bite, $leer, 0, 0);
	}else if ($pokeNum == $wailmer) { //320
		array_push($moveList, $splash, 0, 0, 0);
	}else if ($pokeNum == $numel) { //322
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $torkoal) { //324
		array_push($moveList, $ember, 0, 0, 0);
	}else if ($pokeNum == $spoink) { //325
		array_push($moveList, $splash, 0, 0, 0);
	}else if ($pokeNum == $spinda) { //327
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $trapinch) {
		array_push($moveList, $bite, 0, 0, 0);
	}else if ($pokeNum == $cacnea) {//331
		array_push($moveList, $poison_sting, $leer, 0, 0);
	}else if ($pokeNum == $swablu) {//333
		array_push($moveList, $peck, $growl, 0, 0);
	}else if ($pokeNum == $zangoose) {//335
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $seviper) {//336
		array_push($moveList, $wrap, 0, 0, 0);
	}else if ($pokeNum == $lunatone) {//337
		array_push($moveList, $tackle, $harden, $confusion, 0);
	}else if ($pokeNum == $solrock) {//338
		array_push($moveList, $tackle, $harden, $confusion, 0);
	}else if ($pokeNum == $barboach) {//339
		array_push($moveList, $mud_slap, 0, 0, 0);
	}else if ($pokeNum == $corphish) { //341
		array_push($moveList, $bubble, 0, 0, 0);
	}else if ($pokeNum == $baltoy) { //343
		array_push($moveList, $confusion, 0, 0, 0);
	}else if ($pokeNum == $lileep) { //345
		array_push($moveList, $astonish, $constrict, 0, 0);
	}else if ($pokeNum == $anorith) {//347
		array_push($moveList, $scratch, $harden, 0, 0);
	}else if ($pokeNum == $feebas) { //349
		array_push($moveList, $splash, 0, 0, 0);
	}else if ($pokeNum == $castform) { //351
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $kecleon) { //352
		array_push($moveList, $scratch, $lick, $astonish, $tail_whip);
	}else if ($pokeNum == $shuppet) { //353
		array_push($moveList, $knock_off, 0, 0, 0);
	}else if ($pokeNum == $duskull) {//355
		array_push($moveList, $night_shade, $leer, 0, 0);
	}else if ($pokeNum == $tropius) {//357
		array_push($moveList, $gust, $leer, 0, 0);
	}else if ($pokeNum == $chimecho) { //358
		array_push($moveList, $wrap, 0, 0, 0);
	}else if ($pokeNum == $absol) {//359
		array_push($moveList, $scratch, $feint, 0, 0);
	}else if ($pokeNum == $snorunt) { //361
		array_push($moveList, $powder_snow, $leer, 0, 0);
	}else if ($pokeNum == $spheal) { //363
		array_push($moveList, $powder_snow, $defense_curl, $water_gun, $growl);
	}else if ($pokeNum == $clamperl) { //366
		array_push($moveList, $clamp, $water_gun, $whirlpool, $iron_defense);
	}else if ($pokeNum == $relicanth) { //369
		array_push($moveList, $tackle, $harden, 0, 0);
	}else if ($pokeNum == $luvdisc) {//370
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $bagon) {//371
		array_push($moveList, $rage, 0, 0, 0);
	}else if ($pokeNum == $beldum) { //374
		array_push($moveList, $take_down, 0, 0, 0);
	}else if ($pokeNum == $regirock) { //377
		array_push($moveList, $stomp, $explosion, 0, 0);
	}else if ($pokeNum == $regice) { //378
		array_push($moveList, $stomp, $explosion, 0, 0);
	}else if ($pokeNum == $registeel) { //379
		array_push($moveList, $stomp, $explosion, 0, 0);
	}else if ($pokeNum == $latias) {//380
		array_push($moveList, $psywave, 0, 0, 0);
	}else if ($pokeNum == $latios) {//381
		array_push($moveList, $psywave, 0, 0, 0);
	}else if ($pokeNum == $kyogre) {//382
		array_push($moveList, $water_pulse, 0, 0, 0);
	}else if ($pokeNum == $groudon) {//383
		array_push($moveList, $mud_shot, 0, 0, 0);
	}else if ($pokeNum == $rayquaza) {//384
		array_push($moveList, $twister, 0, 0, 0);
	}else if ($pokeNum == $jirachi) {//385
		array_push($moveList, $confusion, $wish, 0, 0);
	}else if ($pokeNum == $deoxys) {//386
		array_push($moveList, $wrap, $leer, 0, 0);
	}else if ($pokeNum == $turtwig) {//387
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $chimchar) {//390
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $piplup) {//393
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $starly) {//396
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $bidoof) {//399
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $kricketot) {//401
		array_push($moveList, $bide, $growl, 0, 0);
	}else if ($pokeNum == $shinx) {//403
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $cranidos) {//408
		array_push($moveList, $headbutt, $leer, 0, 0);
	}else if ($pokeNum == $shieldon) {//410
		array_push($moveList, $tackle, $protect, 0, 0);
	}else if ($pokeNum == $burmy) {//412
		array_push($moveList, $tackle, $protect, 0, 0);
	}else if ($pokeNum == $combee) {//415
		array_push($moveList, $gust, $sweet_scent, 0, 0);
	}else if ($pokeNum == $pachirisu) {//417
		array_push($moveList, $bide, $growl, 0, 0);
	}else if ($pokeNum == $buizel) {//418
		array_push($moveList, $sonicboom, $growl, $water_sport, 0);
	}else if ($pokeNum == $cherubi) {//420
		array_push($moveList, $tackle, $morning_sun, 0, 0);
	}else if ($pokeNum == $shellos) {//422
		array_push($moveList, $mud_slap, 0, 0, 0);
	}else if ($pokeNum == $drifloon) {//425
		array_push($moveList, $constrict, $minimize, 0, 0);
	}else if ($pokeNum == $buneary) {//427
		array_push($moveList, $pound, $splash, $defense_curl, $foresight);
	}else if ($pokeNum == $glameow) {//431
		array_push($moveList, $fake_out, 0, 0, 0);
	}else if ($pokeNum == $stunky) {//434
		array_push($moveList, $scratch, $focus_energy, 0, 0);
	}else if ($pokeNum == $bronzor) {//436
		array_push($moveList, $tackle, $confusion, 0, 0);
	}else if ($pokeNum == $chatot) {//441
		array_push($moveList, $peck, 0, 0, 0);
	}else if ($pokeNum == $spiritomb) {//442
		array_push($moveList, $pursuit, $confuse_ray, $curse, $spite);
	}else if ($pokeNum == $gible) {//443
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $hippopotas) { //449
		array_push($moveList, $tackle, $sand_attack, 0, 0);
	}else if ($pokeNum == $skorupi) {
		array_push($moveList, $bite, $poison_sting, $leer, 0);
	}else if ($pokeNum == $croagunk) {//453
		array_push($moveList, $astonish, 0, 0, 0);
	}else if ($pokeNum == $carnivine) {//455
		array_push($moveList, $bind, $growth, 0, 0);
	}else if ($pokeNum == $finneon) {//456
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $snover) {//459
		array_push($moveList, $powder_snow, $leer, 0, 0);
	}else if ($pokeNum == $rotom) {//479
		array_push($moveList, $thundershock, $thunderwave, $confuse_ray, $astonish);
	}else if ($pokeNum == $riolu) {
		array_push($moveList, $foresight, $quick_attack, $endure, 0);
	}else if ($pokeNum == $uxie) {//480
		array_push($moveList, $confusion, $rest, 0, 0);
	}else if ($pokeNum == $mesprit) {//481
		array_push($moveList, $confusion, $rest, 0, 0);
	}else if ($pokeNum == $azelf) {//482
		array_push($moveList, $confusion, $rest, 0, 0);
	}else if ($pokeNum == $dialga) {//483
		array_push($moveList, $dragonbreath, $scary_face, 0, 0);
	}else if ($pokeNum == $palkia) {//484
		array_push($moveList, $dragonbreath, $scary_face, 0, 0);
	}else if ($pokeNum == $heatran) {//485
		array_push($moveList, $ancientpower, 0, 0, 0);
	}else if ($pokeNum == $regigigas) {//486
		array_push($moveList, $fire_punch, $ice_punch, $thunderpunch, $dizzy_punch);
	}else if ($pokeNum == $giratina) {//487
		array_push($moveList, $dragonbreath, $scary_face, 0, 0);
	}else if ($pokeNum == $cresselia) {//488
		array_push($moveList, $confusion, $double_team, 0, 0);
	}else if ($pokeNum == $phione) {//489
		array_push($moveList, $bubble, $water_sport, 0, 0);
	}else if ($pokeNum == $darkrai) {//491
		array_push($moveList, $ominous_wind, $disable, 0, 0);
	}else if ($pokeNum == $shaymin) { //492
		array_push($moveList, $magical_leaf, $growth, 0, 0);
	}else if ($pokeNum == $arceus) { //493
		array_push($moveList, $seismic_toss, $cosmic_power, $natural_gift, $punishment);
	}else if ($pokeNum == $victini) {//494
		array_push($moveList, $searing_shot, $focus_energy, $confusion, $incinerate);
	}else if ($pokeNum == $snivy) {//495
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $tepig) {//498
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $oshawott) {//501
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $patrat) {//504
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $lillipup) {//506
		array_push($moveList, $tackle, $leer, 0, 0);
	}else if ($pokeNum == $purrloin) {//509
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $pansage) {//511
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $pansear) {//513
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $panpour) {//515
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $munna) {//517
		array_push($moveList, $psywave, $defense_curl, 0, 0);
	}else if ($pokeNum == $pidove) {//519
		array_push($moveList, $gust, 0, 0, 0);
	}else if ($pokeNum == $blitzle) {//522
		array_push($moveList, $quick_attack, 0, 0, 0);
	}else if ($pokeNum == $roggenrolla) {//524
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $woobat) {//527
		array_push($moveList, $confusion, 0, 0, 0);
	}else if ($pokeNum == $drilbur) { //529
		array_push($moveList, $scratch, $mud_sport, 0, 0);
	}else if ($pokeNum == $audino) { //531
		array_push($moveList, $pound, $growl, $helping_hand, 0);
	}else if ($pokeNum == $timburr) { //532
		array_push($moveList, $pound, $leer, 0, 0);
	}else if ($pokeNum == $tympole) {//535
		array_push($moveList, $bubble, $growl, 0, 0);
	}else if ($pokeNum == $throh) {//538
		array_push($moveList, $bind, $leer, 0, 0);
	}else if ($pokeNum == $sawk) {//539
		array_push($moveList, $rock_smash, $leer, 0, 0);
	}else if ($pokeNum == $sewaddle) {//540
		array_push($moveList, $tackle, $string_shot, 0, 0);
	}else if ($pokeNum == $venipede) {//543
		array_push($moveList, $rollout, $defense_curl, 0, 0);
	}else if ($pokeNum == $cottonee) {//546
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $petilil) {//548
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $basculin) {//550
		array_push($moveList, $tackle, $water_gun, 0, 0);
	}else if ($pokeNum == $sandile) { //551
		array_push($moveList, $rage, $leer, 0, 0);
	}else if ($pokeNum == $darumaka) { //554
		array_push($moveList, $tackle, 0, 0, 0);
	}else if ($pokeNum == $maractus) { //556
		array_push($moveList, $peck, $absorb, 0, 0);
	}else if ($pokeNum == $dwebble) { //557
		array_push($moveList, $fury_cutter, 0, 0, 0);
	}else if ($pokeNum == $scraggy) {//559
		array_push($moveList, $low_kick, $leer, 0, 0);
	}else if ($pokeNum == $sigilyph) {//561
		array_push($moveList, $gust, $miracle_eye, 0, 0);
	}else if ($pokeNum == $yamask) {//562
		array_push($moveList, $astonish, $protect, 0, 0);
	}else if ($pokeNum == $tirtouga) { //564
		array_push($moveList, $water_gun, $withdraw, $bide, 0);
	}else if ($pokeNum == $archen) {//566
		array_push($moveList, $quick_attack, $leer, $wing_attack, 0);
	}else if ($pokeNum == $trubbish) {//568
		array_push($moveList, $pound, $poison_gas, 0, 0);
	}else if ($pokeNum == $zorua) {//570
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $minccino) {//572
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $gothita) {//574
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $solosis) {//577
		array_push($moveList, $psywave, 0, 0, 0);
	}else if ($pokeNum == $ducklett) {//580
		array_push($moveList, $water_gun, 0, 0, 0);
	}else if ($pokeNum == $vanillite) {//582
		array_push($moveList, $icicle_spear, 0, 0, 0);
	}else if ($pokeNum == $deerling) {//585
		array_push($moveList, $tackle, $camouflage, 0, 0);
	}else if ($pokeNum == $emolga) {//587
		array_push($moveList, $thundershock, 0, 0, 0);
	}else if ($pokeNum == $karrablast) {//588
		array_push($moveList, $peck, 0, 0, 0);
	}else if ($pokeNum == $foongus) {//590
		array_push($moveList, $absorb, 0, 0, 0);
	}else if ($pokeNum == $frillish) {//592
		array_push($moveList, $bubble, $water_sport, 0, 0);
	}else if ($pokeNum == $alomomola) {//594
		array_push($moveList, $pound, $water_sport, 0, 0);
	}else if ($pokeNum == $joltik) {//595
		array_push($moveList, $leech_life, $string_shot, $spider_web, 0);
	}else if ($pokeNum == $ferroseed) {//597
		array_push($moveList, $tackle, $harden, 0, 0);
	}else if ($pokeNum == $klink) {//599
		array_push($moveList, $vicegrip, 0, 0, 0);
	}else if ($pokeNum == $tynamo) {//602
		array_push($moveList, $tackle, $thunderwave,$spark, $charge_beam);
	}else if ($pokeNum == $elgyem) {//605
		array_push($moveList, $confusion, 0,0, 0);
	}else if ($pokeNum == $litwick) {//607
		array_push($moveList, $ember, $astonish, 0, 0);
	}else if ($pokeNum == $axew) {//610
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $cubchoo) {//613
		array_push($moveList, $powder_snow, 0, 0, 0);
	}else if ($pokeNum == $cryogonal) {//615
		array_push($moveList, $bind, 0, 0, 0);
	}else if ($pokeNum == $shelmet) {//616
		array_push($moveList, $leech_life, 0, 0, 0);
	}else if ($pokeNum == $stunfisk) {//618
		array_push($moveList, $mud_slap, $mud_sport, 0, 0);
	}else if ($pokeNum == $mienfoo) { //619
		array_push($moveList, $pound, 0, 0, 0);
	}else if ($pokeNum == $druddigon) { //621
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $golett) {//622
		array_push($moveList, $pound, $astonish, $defense_curl, 0);
	}else if ($pokeNum == $pawniard) {//624
		array_push($moveList, $scratch, 0, 0, 0);
	}else if ($pokeNum == $bouffalant) {//626
		array_push($moveList, $pursuit, $leer, 0, 0);
	}else if ($pokeNum == $rufflet) {//627
		array_push($moveList, $peck, $leer, 0, 0);
	}else if ($pokeNum == $vullaby) {//629
		array_push($moveList, $gust, $leer, 0, 0);
	}else if ($pokeNum == $heatmor) {//631
		array_push($moveList, $incinerate, $lick, 0, 0);
	}else if ($pokeNum == $durant) {//632
		array_push($moveList, $vicegrip, $sand_attack, 0, 0);
	}else if ($pokeNum == $deino) {//633
		array_push($moveList, $tackle, $dragon_rage, 0, 0);
	}else if ($pokeNum == $larvesta) {//636
		array_push($moveList, $ember, $string_shot, 0, 0);
	}else if ($pokeNum == $cobalion) { //638
		array_push($moveList, $quick_attack, $leer, 0, 0);
	}else if ($pokeNum == $terrakion) { //639
		array_push($moveList, $quick_attack, $leer, 0, 0);
	}else if ($pokeNum == $virizion) { //640
		array_push($moveList, $quick_attack, $leer, 0, 0);
	}else if ($pokeNum == $tornadus) {//641
		array_push($moveList, $uproar, $astonish, $gust, 0);
	}else if ($pokeNum == $thundurus) {//642
		array_push($moveList, $uproar, $astonish, $thundershock, 0);
	}else if ($pokeNum == $reshiram) {//643
		array_push($moveList, $fire_fang, $dragon_rage, 0, 0);
	}else if ($pokeNum == $zekrom) {//644
		array_push($moveList, $thunder_fang, $dragon_rage, 0, 0);
	}else if ($pokeNum == $landorus) {//645
		array_push($moveList, $mud_shot, $rock_tomb, $block, 0);
	}else if ($pokeNum == $kyurem) {//646
		array_push($moveList, $icy_wind, $dragon_rage, 0, 0);
	}else if ($pokeNum == $keldeo) { //647
		array_push($moveList, $aqua_jet, $leer, 0, 0);
	}else if ($pokeNum == $meloetta) { //648
		array_push($moveList, $round, 0, 0, 0);
	}else if ($pokeNum == $genesect) {//649
		array_push($moveList, $quick_attack, $screech, $metal_claw, $magnet_rise);
	}else if ($pokeNum == $chespin) {//650
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $fennekin) {//653
		array_push($moveList, $scratch, $tail_whip, 0, 0);
	}else if ($pokeNum == $froakie) {//656
		array_push($moveList, $pound, $growl, 0, 0);
	}else if ($pokeNum == $bunnelby) {//659
		array_push($moveList, $tackle, $agility, $leer, 0);
	}else if ($pokeNum == $fletchling) {//661
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $scatterbug) {//664
		array_push($moveList, $tackle, $string_shot, 0, 0);
	}else if ($pokeNum == $litleo) {//667
		array_push($moveList, $tackle, $leer, 0, 0);
	}else if ($pokeNum == $flabebe) {//669
		array_push($moveList, $tackle, $vine_whip, 0, 0);
	}else if ($pokeNum == $skiddo) {//672
		array_push($moveList, $tackle, $growth, 0, 0);
	}else if ($pokeNum == $pancham) {//674
		array_push($moveList, $tackle, $leer, 0, 0);
	}else if ($pokeNum == $furfrou) {//676
		array_push($moveList, $tackle, $growl, 0, 0);
	}else if ($pokeNum == $espurr) {//677
		array_push($moveList, $scratch, $leer, 0, 0);
	}else if ($pokeNum == $honedge) {//679
		array_push($moveList, $tackle, $swords_dance, 0, 0);
	}else if ($pokeNum == $klefki) {//677
		array_push($moveList, $tackle, $fairy_lock, 0, 0);
	}else if ($pokeNum == $spritzee) {//682
		array_push($moveList, $fairy_wind, $sweet_scent, 0, 0);
	}else if ($pokeNum == $swirlix) {//684
		array_push($moveList, $tackle, $sweet_scent, 0, 0);
	}else if ($pokeNum == $inkay) {//686
		array_push($moveList, $tackle, $peck, $constrict, 0);
	}else if ($pokeNum == $binacle) {//688
		array_push($moveList, $scratch, $sand_attack, $shell_smash, 0);
	}else if ($pokeNum == $skrelp) {//690
		array_push($moveList, $tackle, $smokescreen, $water_gun, 0);
	}else if ($pokeNum == $clauncher) {//692
		array_push($moveList, $water_gun, $splash, 0, 0);
	}else if ($pokeNum == $helioptile) {//694
		array_push($moveList, $pound, $tail_whip, 0, 0);
	}else if ($pokeNum == $tyrunt) {//696
		array_push($moveList, $tackle, $tail_whip, 0, 0);
	}else if ($pokeNum == $amaura) {//698
		array_push($moveList, $powder_snow, $growl, 0, 0);
	}else if ($pokeNum == $hawlucha) { //701
		array_push($moveList, $tackle, $detect, $hone_claws, 0);
	}else if ($pokeNum == $dedenne) { //702
		array_push($moveList, $tackle, $tail_whip, 0, 0);
	}else if ($pokeNum == $carbink) { //703
		array_push($moveList, $tackle, $harden, 0, 0);
	}else if ($pokeNum == $goomy) { //704
		array_push($moveList, $tackle, $bubble, 0, 0);
	}else if ($pokeNum == $phantump) { //708
		array_push($moveList, $tackle, $confuse_ray, 0, 0);
	}else if ($pokeNum == $pumpkaboo) { //710
		array_push($moveList, $astonish, $confuse_ray, $trick, 0);
	}else if ($pokeNum == $bergmite) { //712
		array_push($moveList, $tackle, $bite, $harden, 0);
	}else if ($pokeNum == $noibat) { //714
		array_push($moveList, $tackle, $screech, $supersonic, 0);
	}else if ($pokeNum == $xerneas) {//716
		array_push($moveList, $take_down, $ingrain, $aromatheraphy, $heal_pulse);
		}else if ($pokeNum == $yveltal) {//717
		array_push($moveList, $hurricane, $razor_wind, $taunt, $roost);
	}else if ($pokeNum == $zygarde) {//718
		array_push($moveList, $bulldoze, $glare, $dragonbreath, $bite);
	}else if ($pokeNum == $diancie) {//719
		array_push($moveList, $tackle, $harden, 0, 0);
	}else if ($pokeNum == $hoopa) {//720
		array_push($moveList, $confusion, 0, 0, 0);
	}else if ($pokeNum == $missingno) {//1010
		array_push($moveList, $water_gun, 0, 0, 0);
	}else if ($pokeNum == $chameleaf) {//2500
		array_push($moveList, $lick, $tail_whip, 0, 0);
	}else if ($pokeNum == $coalla) {//2503
		array_push($moveList, $scratch, $defense_curl, 0, 0);
	}else if ($pokeNum == $bubbull) {//2506
		array_push($moveList, $bubble, $growl, 0, 0);
	}else {
		array_push($moveList, $protect, 0, 0, 0);
	}
	return $moveList;	
}
function get_Family($pokeNum) {
	require 'pokeList.php';
	$allList = array();
	array_push($allList, array($bulbasaur, $ivysaur, $venusaur));
	array_push($allList, array($charmander, $charmeleon, $charizard));
	array_push($allList, array($squirtle, $wartortle, $blastoise));
	array_push($allList, array($caterpie, $metapod, $butterfree));
	array_push($allList, array($weedle, $kakuna, $beedrill));
	array_push($allList, array($pidgey, $pidgeotto, $pidgeot));
	array_push($allList, array($rattata, $raticate));
	array_push($allList, array($spearow, $fearow));
	array_push($allList, array($ekans, $arbok));
	array_push($allList, array($pichu, $pikachu, $raichu)); //25
	array_push($allList, array($sandshrew, $sandslash)); //27
	array_push($allList, array($nidoran_f, $nidorina, $nidoqueen)); //29
	array_push($allList, array($nidoran_m, $nidorino, $nidoking)); //32
	array_push($allList, array($cleffa, $clefairy, $clefable));//35
	array_push($allList, array($vulpix, $ninetales));//37
	array_push($allList, array($igglybuff, $jigglypuff, $wigglytuff));//39
	array_push($allList, array($zubat, $golbat, $crobat));//41
	array_push($allList, array($oddish, $gloom, $vileplume, $bellossom));//43
	array_push($allList, array($paras, $parasect));//46
	array_push($allList, array($venonat, $venomoth));//48
	array_push($allList, array($diglett, $dugtrio));//50
	array_push($allList, array($meowth, $persian));//52
	array_push($allList, array($psyduck, $golduck));//54
	array_push($allList, array($mankey, $primeape));//56
	array_push($allList, array($growlithe, $arcanine));//58
	array_push($allList, array($poliwag, $poliwhirl, $poliwrath, $politoed));//60
	array_push($allList, array($abra, $kadabra, $alakazam));//63
	array_push($allList, array($machop, $machoke, $machamp));//66
	array_push($allList, array($bellsprout, $weepinbell, $victreebel));//69
	array_push($allList, array($tentacool, $tentacruel));//72
	array_push($allList, array($geodude, $graveler, $golem));//74
	array_push($allList, array($ponyta, $rapidash));//77
	array_push($allList, array($slowpoke, $slowbro, $slowking));//79
	array_push($allList, array($magnemite, $magneton, $magnezone));//81
	array_push($allList, array($farfetchd));//83
	array_push($allList, array($doduo, $dodrio));//84
	array_push($allList, array($seel, $dewgong));//86
	array_push($allList, array($grimer, $muk));//88
	array_push($allList, array($shellder, $cloyster));//90
	array_push($allList, array($gastly, $haunter, $gengar));//92
	array_push($allList, array($onix, $steelix));//95
	array_push($allList, array($drowzee, $hypno));//96
	array_push($allList, array($krabby, $kingler));//98
	array_push($allList, array($voltorb, $electrode));//100
	array_push($allList, array($exeggcute, $exeggutor));//102
	array_push($allList, array($cubone, $marowak));//104
	array_push($allList, array($tyrogue, $hitmonlee, $hitmonchan, $hitmontop));//106
	array_push($allList, array($lickitung, $lickilicky));//108
	array_push($allList, array($koffing, $weezing));//109
	array_push($allList, array($rhyhorn, $rhydon, $rhyperior));//111
	array_push($allList, array($chansey, $blissey, $happiny));//113
	array_push($allList, array($tangela, $tangrowth));//114
	array_push($allList, array($kangaskhan));//115
	array_push($allList, array($horsea, $seadra, $kingdra));//116
	array_push($allList, array($goldeen, $seaking));//118
	array_push($allList, array($staryu, $starmie));//120
	array_push($allList, array($mrmime, $mimejr));//122
	array_push($allList, array($scyther, $scizor));//123
	array_push($allList, array($smoochum, $jynx));//124
	array_push($allList, array($elekid, $electabuzz, $electivire));//125
	array_push($allList, array($magby, $magmar, $magmortar));//126
	array_push($allList, array($pinsir));//127
	array_push($allList, array($tauros));//128
	array_push($allList, array($magikarp, $gyarados));//129
	array_push($allList, array($lapras));//131
	array_push($allList, array($ditto));//132
	array_push($allList, array($eevee, $vaporeon, $jolteon, $flareon, $espeon, $umbreon, $glaceon, $leafeon, $sylveon));//133
	array_push($allList, array($porygon, $porygon2, $porygonz));//137
	array_push($allList, array($omanyte, $omastar));//138
	array_push($allList, array($kabuto, $kabutops));//140
	array_push($allList, array($aerodactyl));//142
	array_push($allList, array($snorlax, $munchlax));//143
	array_push($allList, array($articuno));//144
	array_push($allList, array($zapdos));//145
	array_push($allList, array($moltres));//146
	array_push($allList, array($dratini, $dragonair, $dragonite));//147
	array_push($allList, array($mewtwo));//150
	array_push($allList, array($mew));//151
	array_push($allList, array($chikorita, $bayleef, $meganium));//152
	array_push($allList, array($cyndaquil, $quilava, $typhlosion));
	array_push($allList, array($totodile, $croconaw, $feraligatr));
	array_push($allList, array($sentret, $furret));
	array_push($allList, array($hoothoot, $noctowl));
	array_push($allList, array($ledyba, $ledian));
	array_push($allList, array($spinarak, $ariados));
	array_push($allList, array($chinchou, $lanturn));//170
	array_push($allList, array($togepi, $togetic, $togekiss));//175
	array_push($allList, array($natu, $xatu));//177
	array_push($allList, array($marill, $azumarill, $azurill));//183
	array_push($allList, array($sudowoodo, $bonsly));//185
	array_push($allList, array($aipom, $ambipom));//190
	array_push($allList, array($sunkern, $sunflora));//191
	array_push($allList, array($yanma, $yanmega));//193
	array_push($allList, array($murkrow, $honchkrow));//198
	array_push($allList, array($misdreavus, $mismagus));//200
	array_push($allList, array($unown));//201
	array_push($allList, array($wobbuffet, $wynaut));//202
	array_push($allList, array($girafarig));//203
	array_push($allList, array($pineco, $forretress));//204
	array_push($allList, array($mareep, $flaaffy, $ampharos));
	array_push($allList, array($hoppip, $skiploom, $jumpluff));
	array_push($allList, array($wooper, $quagsire));
	array_push($allList, array($dunsparce));
	array_push($allList, array($gligar, $gliscor));//207
	array_push($allList, array($snubbull, $granbull));//209
	array_push($allList, array($qwilfish));//211
	array_push($allList, array($shuckle));//213
	array_push($allList, array($heracross));//214
	array_push($allList, array($sneasel, $weavile));//215
	array_push($allList, array($teddiursa, $ursaring));//216
	array_push($allList, array($slugma, $magcargo));//218
	array_push($allList, array($swinub, $piloswine, $mamoswine));//220
	array_push($allList, array($corsola));//222
	array_push($allList, array($remoraid, $octillery));//223
	array_push($allList, array($delibird));//225
	array_push($allList, array($mantine, $mantyke));//226
	array_push($allList, array($skarmony));//227
	array_push($allList, array($houndour, $houndoom));//228
	array_push($allList, array($phanpy, $donphan));//231
	array_push($allList, array($stantler));//234
	array_push($allList, array($smeargle));//235
	array_push($allList, array($miltank));//241
	array_push($allList, array($raikou));//243
	array_push($allList, array($entei));//244
	array_push($allList, array($suicune));//245
	array_push($allList, array($larvitar, $pupitar, $tyranitar));//246
	array_push($allList, array($lugia));//249
	array_push($allList, array($ho_oh));//250
	array_push($allList, array($celebi));//251
	array_push($allList, array($treecko, $grovyle, $sceptile));//252
	array_push($allList, array($torchic, $combusken, $blaziken));//255
	array_push($allList, array($mudkip, $marshtomp, $swampert));//258
	array_push($allList, array($poochyena, $mightyena)); //261
	array_push($allList, array($zigzagoon, $linoone)); //263
	array_push($allList, array($wurmple, $silcoon, $beautifly, $cascoon, $dustox)); //265
	array_push($allList, array($lotad, $lombre, $ludicolo)); //270
	array_push($allList, array($seedot, $nuzleaf, $shiftry)); //273
	array_push($allList, array($taillow, $swellow)); //276
	array_push($allList, array($wingull, $pelipper)); //278
	array_push($allList, array($ralts, $kirlia, $gardevoir, $gallade));//280
	array_push($allList, array($surskit, $masquerain)); //283
	array_push($allList, array($shroomish, $breloom)); //285
	array_push($allList, array($slakoth, $vigoroth, $slaking)); //287
	array_push($allList, array($nincada, $ninjask, $shedinja)); //290
	array_push($allList, array($whismur, $loudred, $exploud)); //293
	array_push($allList, array($makuhita, $hariyama)); //296
	array_push($allList, array($nosepass, $probopass)); //299
	array_push($allList, array($skitty, $delcatty)); //300
	array_push($allList, array($sableye));//302
	array_push($allList, array($mawile));//303
	array_push($allList, array($aron, $lairon, $aggron)); //304
	array_push($allList, array($meditite, $medicham));//307
	array_push($allList, array($electrike, $manectric));//309
	array_push($allList, array($plusle));//311
	array_push($allList, array($minun));//312
	array_push($allList, array($volbeat));//313
	array_push($allList, array($illumise));//314
	array_push($allList, array($roselia, $roserade, $budew));//315
	array_push($allList, array($gulpin, $swalot));//316
	array_push($allList, array($carvanha, $sharpedo));//318
	array_push($allList, array($wailmer, $wailord));//320
	array_push($allList, array($numel, $camerupt));//322
	array_push($allList, array($torkoal));//324
	array_push($allList, array($spoink, $grumpig));//325
	array_push($allList, array($spinda));//327
	array_push($allList, array($trapinch, $vibrava, $flygon));
	array_push($allList, array($cacnea, $cacturne));//331
	array_push($allList, array($swablu, $altaria));//333
	array_push($allList, array($zangoose));//335
	array_push($allList, array($seviper));//336
	array_push($allList, array($lunatone));//337
	array_push($allList, array($solrock));//338
	array_push($allList, array($barboach, $whiscash));//339
	array_push($allList, array($corphish, $crawdaunt));//341
	array_push($allList, array($baltoy, $claydol));//343
	array_push($allList, array($lileep, $cradily));//345
	array_push($allList, array($anorith, $armaldo));//347
	array_push($allList, array($feebas, $milotic));//349
	array_push($allList, array($castform));//351
	array_push($allList, array($kecleon));//352
	array_push($allList, array($shuppet, $banette));//353
	array_push($allList, array($duskull, $dusclops, $dusknoir));//355
	array_push($allList, array($tropius));//357
	array_push($allList, array($chimecho, $chingling));//358
	array_push($allList, array($absol));//359
	array_push($allList, array($snorunt, $glalie, $froslass));//361
	array_push($allList, array($spheal, $sealeo, $walrein));//363
	array_push($allList, array($clamperl, $huntail, $gorebyss));//366
	array_push($allList, array($relicanth));//369
	array_push($allList, array($luvdisc));//370
	array_push($allList, array($bagon, $shelgon, $salamence)); //371
	array_push($allList, array($beldum, $metang, $metagross)); //374
	array_push($allList, array($regirock));//377
	array_push($allList, array($regice));//378
	array_push($allList, array($registeel));//379
	array_push($allList, array($latias));//380
	array_push($allList, array($latios));//381
	array_push($allList, array($kyogre));//382
	array_push($allList, array($groudon));//383
	array_push($allList, array($rayquaza));//384
	array_push($allList, array($jirachi));//385
	array_push($allList, array($deoxys));//386
	array_push($allList, array($turtwig, $grotle, $torterra));//387
	array_push($allList, array($chimchar, $monferno, $infernape));//390
	array_push($allList, array($piplup, $prinplup, $empoleon));//393
	array_push($allList, array($starly, $staravia, $staraptor));//396
	array_push($allList, array($bidoof, $bibarel));//399
	array_push($allList, array($kricketot, $kricketune));//401
	array_push($allList, array($shinx, $luxio, $luxray));//403
	array_push($allList, array($cranidos, $rampardos));//408
	array_push($allList, array($shieldon, $bastiodon));//410
	array_push($allList, array($burmy, $wormadam, $mothim));//412
	array_push($allList, array($combee, $vespiquen));//415
	array_push($allList, array($pachirisu));//417
	array_push($allList, array($buizel, $floatzel));//418
	array_push($allList, array($cherubi, $cherrim));//420
	array_push($allList, array($shellos, $gastrodon));//422
	array_push($allList, array($drifloon, $drifblim));//425
	array_push($allList, array($buneary, $lopunny));//427
	array_push($allList, array($glameow, $purugly));//431
	array_push($allList, array($stunky, $skuntank));//434
	array_push($allList, array($bronzor, $bronzong));//436
	array_push($allList, array($chatot));//441
	array_push($allList, array($spiritomb));//442
	array_push($allList, array($gible, $gabite, $garchomp));//443
	array_push($allList, array($riolu, $lucario));
	array_push($allList, array($hippopotas, $hippowdon));//449
	array_push($allList, array($skorupi, $drapion));
	array_push($allList, array($croagunk, $toxicroak));//453
	array_push($allList, array($carnivine));//455
	array_push($allList, array($finneon, $lumineon));//456
	array_push($allList, array($snover, $abomasnow));//459
	array_push($allList, array($rotom));//479
	array_push($allList, array($uxie));//480
	array_push($allList, array($mesprit));//481
	array_push($allList, array($azelf));//482
	array_push($allList, array($dialga));//483
	array_push($allList, array($palkia));//484
	array_push($allList, array($heatran));//485
	array_push($allList, array($regigigas));//486
	array_push($allList, array($giratina));//487
	array_push($allList, array($cresselia));//488
	array_push($allList, array($phione, $manaphy));//489
	array_push($allList, array($darkrai));//491
	array_push($allList, array($shaymin));//492
	array_push($allList, array($arceus));//493
	array_push($allList, array($victini));//494
	array_push($allList, array($snivy, $servine, $serperior));//495
	array_push($allList, array($tepig, $pignite, $emboar));//498
	array_push($allList, array($oshawott, $dewott, $samurott));//501
	array_push($allList, array($patrat, $watchog));//504
	array_push($allList, array($lillipup, $herdier, $stoutland));//506
	array_push($allList, array($purrloin, $liepard));//509
	array_push($allList, array($pansage, $simisage));//511
	array_push($allList, array($pansear, $simisear));//513
	array_push($allList, array($panpour, $simipour));//515
	array_push($allList, array($munna, $musharna));//517
	array_push($allList, array($pidove, $tranquill, $unfezant));//519
	array_push($allList, array($blitzle, $zebstrika));//522
	array_push($allList, array($roggenrolla, $boldore, $gigalith));//524
	array_push($allList, array($woobat, $swoobat));//527
	array_push($allList, array($drilbur, $excadrill));//529
	array_push($allList, array($audino));//531
	array_push($allList, array($timburr, $gurdurr, $conkeldurr));//532
	array_push($allList, array($tympole, $palpitoad, $seismitoad));//535
	array_push($allList, array($throh));//538
	array_push($allList, array($sawk));//539
	array_push($allList, array($sewaddle, $swadloon, $leavanny));//540
	array_push($allList, array($venipede, $whirlipede, $scolipede));//543
	array_push($allList, array($cottonee, $whimsicott));//546
	array_push($allList, array($petilil, $lilligant));//548
	array_push($allList, array($basculin));//550
	array_push($allList, array($sandile, $krokorok, $krookodile));//551
	array_push($allList, array($darumaka, $darmanitan));//554
	array_push($allList, array($maractus));//556
	array_push($allList, array($dwebble, $crustle));//557
	array_push($allList, array($scraggy, $scrafty));//559
	array_push($allList, array($sigilyph));//561
	array_push($allList, array($yamask, $cofagrigus));//562
	array_push($allList, array($tirtouga, $carracosta));//564
	array_push($allList, array($archen, $archeops));//566
	array_push($allList, array($trubbish, $garbodor));//568
	array_push($allList, array($zorua, $zoroark));//570
	array_push($allList, array($minccino, $cinccino));//572
	array_push($allList, array($gothita, $gothorita, $gothitelle));//574
	array_push($allList, array($solosis, $duosion, $reuniclus));//577
	array_push($allList, array($ducklett, $swanna));//580
	array_push($allList, array($vanillite, $vanillish, $vanilluxe));//582
	array_push($allList, array($deerling, $sawsbuck));//585
	array_push($allList, array($emolga));//587
	array_push($allList, array($karrablast, $escavalier));//588
	array_push($allList, array($foongus, $amoonguss));//590
	array_push($allList, array($frillish, $jellicent));//592
	array_push($allList, array($alomomola));//594
	array_push($allList, array($joltik, $galvantula));//595
	array_push($allList, array($ferroseed, $ferrothorn));//597
	array_push($allList, array($klink, $klang, $klinklang));//599
	array_push($allList, array($tynamo, $eelektrik, $eelektross)); //602
	array_push($allList, array($elgyem, $beheeyem)); //605
	array_push($allList, array($litwick, $lampent, $chandelure)); //607
	array_push($allList, array($axew, $fraxure, $haxorus)); //610
	array_push($allList, array($cubchoo, $beartic));//613
	array_push($allList, array($cryogonal));//615
	array_push($allList, array($shelmet, $accelgor));//616
	array_push($allList, array($stunfisk));//618
	array_push($allList, array($mienfoo, $mienshao));//619
	array_push($allList, array($druddigon));//621
	array_push($allList, array($golett, $golurk));//622
	array_push($allList, array($pawniard, $bisharp));//624
	array_push($allList, array($bouffalant));//626
	array_push($allList, array($rufflet, $braviary));//627
	array_push($allList, array($vullaby, $mandibuzz));//629
	array_push($allList, array($heatmor));//631
	array_push($allList, array($durant));//632
	array_push($allList, array($deino, $zweilous, $hydreigon));//633
	array_push($allList, array($larvesta, $volcarona));//636
	array_push($allList, array($cobalion));//638
	array_push($allList, array($terrakion));//639
	array_push($allList, array($virizion));//640
	array_push($allList, array($tornadus));//641
	array_push($allList, array($thundurus));//642
	array_push($allList, array($reshiram));//643
	array_push($allList, array($zekrom));//644
	array_push($allList, array($landorus));//645
	array_push($allList, array($kyurem));//646
	array_push($allList, array($keldeo));//647
	array_push($allList, array($meloetta));//648
	array_push($allList, array($genesect));//649
	array_push($allList, array($chespin, $quilladin, $chesnaught));//650
	array_push($allList, array($fennekin, $braixen, $delphox));//653
	array_push($allList, array($froakie, $frogadier, $greninja));//656
	array_push($allList, array($bunnelby, $diggersby));//659
	array_push($allList, array($fletchling, $fletchinder, $talonflame));//661
	array_push($allList, array($scatterbug, $spewpa, $vivillon));//664
	array_push($allList, array($litleo, $pyroar));//667
	array_push($allList, array($flabebe, $floette, $florges));//669
	array_push($allList, array($skiddo, $gogoat));//672
	array_push($allList, array($pancham, $pangoro));//674
	array_push($allList, array($furfrou));//676
	array_push($allList, array($espurr, $meowstic));//677
	array_push($allList, array($honedge, $doublade, $aegislash));//679
	array_push($allList, array($spritzee, $aromatisse));//682
	array_push($allList, array($swirlix, $slurpuff));//684
	array_push($allList, array($inkay, $malamar));//686
	array_push($allList, array($binacle, $barbaracle));//688
	array_push($allList, array($skrelp, $dragalge));//690
	array_push($allList, array($clauncher, $clawitzer));//692
	array_push($allList, array($helioptile, $heliolisk));//694
	array_push($allList, array($tyrunt, $tyrantrum));//696
	array_push($allList, array($amaura, $aurorus));//698
	array_push($allList, array($hawlucha));//701
	array_push($allList, array($dedenne));//702
	array_push($allList, array($carbink));//703
	array_push($allList, array($goomy, $sliggoo, $goodra));//704
	array_push($allList, array($klefki));//707
	array_push($allList, array($phantump, $trevenant));//708
	array_push($allList, array($pumpkaboo, $gourgeist));//710
	array_push($allList, array($bergmite, $avalugg));//712
	array_push($allList, array($noibat, $noivern));//714
	array_push($allList, array($xerneas));//716
	array_push($allList, array($yveltal));//717
	array_push($allList, array($zygarde));//718
	array_push($allList, array($diancie));//719
	array_push($allList, array($hoopa));//720
	array_push($allList, array($missingno));//1010
	array_push($allList, array($chameleaf, $thorneleon, $truffeleon));//2500
	array_push($allList, array($coalla, $cindereus, $blitzupial));//2503
	array_push($allList, array($bubbull, $buffaflow, $watador));//2506
	for ($i=0; $i<count($allList); $i++) {
		for ($b=0; $b<count($allList[$i]); $b++) {
			if ($pokeNum == $allList[$i][$b]) {
				return $allList[$i];
			}
		}
	}
	return NULL;
}
function is_In_This_Family($pokeNum, $familyList) {
	for ($i=0; $i<count($familyList); $i++) {
		if ($familyList[$i] == $pokeNum) {
			return true;
		}
	}
	return false;
}	

function get_Egg_Group_Query($partnerNum) {
	$numList = array();
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Monster());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Water_1());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Water_2());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Water_3());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Bug());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Flying());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Field());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Fairy());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Grass());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Human_Like());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Mineral());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Amorphous());
	$numList = add_Egg_Group($numList, $partnerNum, get_Egg_Group_Dragon());
	$equals = "=";
	$command = "OR";
	$eggQuery = get_Query_These_Nums($numList, $equals, $command);
	return $eggQuery;
}
function add_Egg_Group($numList, $partnerNum, $eggList) {
	if (is_In_Egg_Group($partnerNum, $eggList) == true) {
		$numList = array_merge($numList, $eggList);
	}
	return $numList;
}
function is_In_Egg_Group($partnerNum, $eggList) {
	$currentNum = 0;
	if ($partnerNum == 132) {
		return true;
	}
	for ($i=0; $i<count($eggList); $i++) {
		$currentNum = $eggList[$i];
		if ($currentNum == $partnerNum) {
			return true;
		}
	}
	return false;
}
function get_Egg_Group_Ditto() {
	require 'pokeList.php';
	$numList = array($ditto);
	return $numList;
}
function get_Egg_Group_Monster() {
	require 'pokeList.php';
	$numList = array($chikorita, $bayleef, $meganium, $totodile, $croconaw, $feraligatr, $treecko, $grovyle, $sceptile, $mareep, $flaaffy, $ampharos, $cranidos, $rampardos, $gible, $gabite, $garchomp, $bulbasaur, $ivysaur, $venusaur, $charmander, $charmeleon, $charizard, $squirtle, $wartortle, $blastoise, $nidoran_f, $nidoran_m, $nidorino, $nidoking, $aron, $lairon, $aggron, $lickitung, $lickilicky, $tropius, $shieldon, $bastiodon, $slowpoke, $slowbro, $slowking, $axew, $fraxure, $haxorus, $cubone, $marowak, $rhyhorn, $rhydon, $kangaskhan, $lapras, $snorlax, $mudkip, $marshtomp, $swampert, $larvitar, $pupitar, $tyranitar, $snover, $abomasnow, $turtwig, $grotle, $torterra, $rhyperior, $whismur, $loudred, $exploud, $druddigon, $helioptile, $heliolisk, $bergmite, $avalugg, $amaura, $aurorus, $tyrunt, $tyrantrum);
	return $numList;
}
function get_Egg_Group_Water_1() {
	require 'pokeList.php';
	$numList = array($totodile, $croconaw, $feraligatr, $wooper, $quagsire, $squirtle, $wartortle, $blastoise, $spheal, $sealeo, $walrein, $poliwag, $poliwhirl, $poliwrath, $politoed, $corsola, $psyduck, $golduck, $feebas, $milotic, $buizel, $floatzel, $slowpoke, $slowbro, $slowking, $seel, $dewgong, $horsea, $seadra, $lapras, $omanyte, $omastar, $kabuto, $kabutops, $dratini, $dragonair, $dragonite, $mudkip, $marshtomp, $swampert, $piplup, $prinplup, $empoleon, $tirtouga, $carracosta, $marill, $azumarill, $corphish, $crawdaunt, $delibird, $remoraid, $octillery, $kingdra, $mantine, $froakie, $frogadier, $greninja, $clauncher, $clawitzer, $lotad, $lombre, $ludicolo, $wingull, $pelipper, $surskit, $masquerain, $phione, $manaphy, $clamperl, $huntail, $gorebyss, $relicanth, $alomomola, $bidoof, $bibarel, $shellos, $gastrodon, $tympole, $palpitoad, $seismitoad, $ducklett, $swanna, $stunfisk, $inkay, $malamar, $skrelp, $dragalge);
	return $numList;
}
function get_Egg_Group_Bug() {
	require 'pokeList.php';
	$numList = array($caterpie, $metapod, $butterfree, $weedle, $kakuna, $beedrill, $ledyba, $ledian, $spinarak, $ariados, $trapinch, $vibrava, $flygon, $skorupi, $drapion, $karrablast, $escavalier, $shelmet, $accelgor, $scyther, $scizor, $paras, $parasect, $venonat, $venomoth, $combee, $vespiquen, $nincada, $ninjask, $pinsir, $pineco, $forretress, $heracross, $dwebble, $crustle, $venipede, $whirlipede, $scolipede, $yanma, $yanmega, $larvesta, $volcarona, $gligar, $gliscor, $shuckle, $surskit, $masquerain, $wurmple, $silcoon, $beautifly, $cascoon, $dustox, $volbeat, $illumise, $durant, $kricketot, $kricketune, $burmy, $wormadam, $mothim, $sewaddle, $swadloon, $leavanny, $joltik, $galvantula, $scatterbug, $spewpa, $vivillon);
	return $numList;
}
function get_Egg_Group_Flying() {
	require 'pokeList.php';
	$numList = array($pidgey, $pidgeotto, $pidgeot, $spearow, $fearow, $zubat, $golbat, $crobat, $hoothoot, $noctowl, $swablu, $altaria, $togetic, $togekiss, $archen, $archeops, $starly, $staravia, $staraptor, $farfetchd, $doduo, $dodrio, $aerodactyl, $murkrow, $honchkrow, $rufflet, $braviary, $taillow, $swellow, $natu, $xatu, $sigilyph, $skarmony, $fletchling, $fletchinder, $talonflame, $wingull, $pelipper, $chatot, $pidove, $tranquill, $unfezant, $woobat, $swoobat, $ducklett, $swanna, $vullaby, $mandibuzz, $noibat, $noivern);
	return $numList;
}
function get_Egg_Group_Field() {
	require 'pokeList.php';
	$numList = array($rattata, $raticate, $cyndaquil, $quilava, $typhlosion, $sentret, $furret, $phanpy, $donphan, $shinx, $luxio, $luxray, $zorua, $zoroark, $chimchar, $monferno, $infernape, $lucario, $oshawott, $dewott, $samurott, $ekans, $arbok, $dunsparce, $mareep, $flaaffy, $ampharos, $wooper, $quagsire, $pikachu, $raichu, $sandshrew, $sandslash, $nidoran_f, $nidoran_m, $nidorino, $nidoking, $spheal, $sealeo, $walrein, $scraggy, $scrafty, $absol, $eevee, $vaporeon, $jolteon, $flareon, $espeon, $umbreon, $torkoal, $darumaka, $darmanitan, $buneary, $lopunny, $vulpix, $ninetales, $diglett, $dugtrio, $meowth, $persian, $psyduck, $golduck, $mankey, $primeape, $growlithe, $arcanine, $buizel, $floatzel, $mienfoo, $mienshao, $drilbur, $excadrill, $sandile, $krokorok, $krookodile, $seviper, $slakoth, $vigoroth, $slaking, $zangoose, $torchic, $combusken, $blaziken, $ponyta, $rapidash, $farfetchd, $seel, $dewgong, $electrike, $manectric, $tepig, $pignite, $emboar, $cubchoo, $beartic, $rhyhorn, $rhydon, $tauros, $aipom, $ambipom, $snubbull, $granbull, $piplup, $prinplup, $empoleon, $mawile, $houndour, $houndoom, $emolga, $hippopotas, $hippowdon, $numel, $camerupt, $stunky, $skuntank, $stantler, $miltank, $bouffalant, $pansear, $simisear, $heatmor, $blitzle, $zebstrika, $pachirisu, $teddiursa, $ursaring, $smeargle, $glaceon, $snivy, $servine, $serperior, $wailmer, $wailord, $swinub, $piloswine, $mamoswine, $girafarig, $sneasel, $delibird, $espurr, $meowstic, $fennekin, $braixen, $delphox, $rhyperior, $weavile, $chespin, $quilladin, $chesnaught, $pancham, $pangoro, $poochyena, $mightyena, $zigzagoon, $linoone, $skitty, $delcatty, $spinda, $seedot, $nuzleaf, $shiftry, $whismur, $loudred, $exploud, $spoink, $grumpig, $kecleon, $bidoof, $bibarel, $glameow, $purugly, $patrat, $watchog, $lillipup, $herdier, $stoutland, $purrloin, $liepard, $pansage, $simisage, $panpour, $simipour, $munna, $musharna, $woobat, $swoobat, $minccino, $cinccino, $deerling, $sawsbuck, $dedenne, $sylveon, $litleo, $pyroar, $bunnelby, $diggersby, $skiddo, $gogoat, $furfrou, $leafeon);
	return $numList;
}
function get_Egg_Group_Fairy() {
	require 'pokeList.php';
	$numList = array($jigglypuff, $wigglytuff, $hoppip, $skiploom, $jumpluff, $pikachu, $raichu, $snorunt, $glalie, $froslass, $clefairy, $clefable, $chansey, $blissey, $plusle, $minun, $togetic, $togekiss, $snubbull, $granbull, $mawile, $roselia, $roserade, $pachirisu, $marill, $azumarill, $shroomish, $breloom, $skitty, $delcatty, $phione, $manaphy, $castform, $cherubi, $cherrim, $audino, $carbink, $cottonee, $whimsicott, $dedenne, $flabebe, $floette, $florges, $spritzee, $aromatisse, $swirlix, $slurpuff);
	return $numList;
}
function get_Egg_Group_Grass() {
	require 'pokeList.php';
	$numList = array($bellsprout, $weepinbell, $victreebel, $chikorita, $bayleef, $meganium, $hoppip, $skiploom, $jumpluff, $bulbasaur, $ivysaur, $venusaur, $oddish, $gloom, $vileplume, $bellossom, $paras, $parasect, $tropius, $ferroseed, $ferrothorn, $exeggcute, $exeggutor, $tangela, $tangrowth, $roselia, $roserade, $foongus, $amoonguss, $sunkern, $sunflora, $snover, $abomasnow, $turtwig, $grotle, $torterra, $snivy, $servine, $serperior, $phantump, $trevenant, $lotad, $lombre, $ludicolo, $shroomish, $breloom, $seedot, $nuzleaf, $shiftry, $cacnea, $cacturne, $maractus, $cherubi, $cherrim, $carnivine, $cottonee, $whimsicott, $petilil, $lilligant);
	return $numList;
}
function get_Egg_Group_Undiscovered() {
	require 'pokeList.php';
	$numList = array($articuno, $zapdos, $moltres, $mewtwo, $mew, $raikou, $entei, $suicune, $lugia, $ho_oh, $celebi, $regirock, $regice, $registeel, $latias, $latios, $kyogre, $groudon, $rayquaza, $jirachi, $deoxys, $riolu, $igglybuff, $unown, $rayquaza, $pichu, $nidorina, $nidoqueen, $smoochum, $elekid, $magby, $cleffa, $heatran, $togepi, $victini, $tyrogue, $missingno, $chameleaf, $thorneleon, $truffeleon, $coalla, $cindereus, $blitzupial, $bubbull, $buffaflow, $watador, $xerneas, $yveltal, $kyurem, $darkrai, $budew, $bonsly, $mimejr, $happiny, $munchlax, $wynaut, $azurill, $mantyke, $regigigas, $cresselia, $genesect, $zygarde, $dialga, $palkia, $giratina, $zekrom, $reshiram, $uxie, $mesprit, $azelf, $chingling, $shaymin, $cobalion, $terrakion, $virizion, $keldeo, $tornadus, $thundurus, $landorus, $meloetta, $diancie, $arceus, $hoopa);
	return $numList;
}
function get_Egg_Group_Human_Like() {
	require 'pokeList.php';
	$numList = array($chimchar, $monferno, $infernape, $lucario, $magmar, $electabuzz, $jynx, $buneary, $lopunny, $abra, $kadabra, $alakazam, $machop, $machoke, $machamp, $timburr, $gurdurr, $conkeldurr, $mienfoo, $mienshao, $croagunk, $toxicroak, $elgyem, $beheeyem, $drowzee, $hypno, $gothita, $gothorita, $gothitelle, $hitmonlee, $hitmonchan, $hitmontop, $mrmime, $pawniard, $bisharp, $makuhita, $hariyama, $sawk, $sableye, $magmortar, $electivire, $pancham, $pangoro, $hawlucha, $spinda, $meditite, $medicham, $volbeat, $illumise, $cacnea, $cacturne, $throh);
	return $numList;
}
function get_Egg_Group_Water_3() {
	require 'pokeList.php';
	$numList = array($skorupi, $drapion, $tentacool, $tentacruel, $krabby, $kingler, $staryu, $starmie, $corsola, $archen, $archeops, $shellder, $cloyster, $omanyte, $omastar, $kabuto, $kabutops, $tirtouga, $carracosta, $anorith, $armaldo, $corphish, $crawdaunt, $clauncher, $clawitzer, $lileep, $cradily, $binacle, $barbaracle);
	return $numList;
}
function get_Egg_Group_Mineral() {
	require 'pokeList.php';
	$numList = array($geodude, $graveler, $golem, $onix, $vanillite, $vanillish, $vanilluxe, $golett, $golurk, $beldum, $metang, $metagross, $steelix,
	$snorunt, $glalie, $froslass, $ferroseed, $ferrothorn, $roggenrolla, $boldore, $gigalith, $shedinja, $magnemite, $magneton, $cryogonal, $voltorb, $electrode, $porygon, $yamask, $cofagrigus, $klink, $klang, $klinklang, $dwebble, $crustle, $sudowoodo, $honedge, $doublade, $aegislash, $klefki, $nosepass, $probopass, $magnezone, $porygon2, $porygonz, $lunatone, $solrock, $baltoy, $claydol, $bronzor, $bronzong, $carbink, $trubbish, $garbodor);
	return $numList;
}
function get_Egg_Group_Amorphous() {
	require 'pokeList.php';
	$numList = array($gastly, $haunter, $gengar, $duskull, $dusclops, $dusknoir, $litwick, $lampent, $chandelure, $ralts, $kirlia, $gardevoir, $gallade, $solosis, $duosion, $reuniclus, $rotom, $tynamo, $eelektrik, $eelektross, $grimer, $muk, $koffing, $weezing, $spiritomb, $yamask, $cofagrigus, $slugma, $magcargo, $wobbuffet, $shuppet, $banette, $phantump, $trevenant, $misdreavus, $mismagus, $gulpin, $swalot, $castform, $chimecho, $shellos, $gastrodon, $drifloon, $drifblim, $frillish, $jellicent, $stunfisk, $pumpkaboo, $gourgeist);
	return $numList;
}
function get_Egg_Group_Water_2() {
	require 'pokeList.php';
	$numList = array($luvdisc, $magikarp, $gyarados, $qwilfish, $goldeen, $seaking, $carvanha, $sharpedo, $chinchou, $lanturn, $wailmer, $wailord, $remoraid, $octillery, $barboach, $whiscash, $relicanth, $alomomola, $finneon, $lumineon, $basculin, $inkay, $malamar);
	return $numList;
}
function get_Egg_Group_Dragon() {
	require 'pokeList.php';
	$numList = array($swablu, $altaria, $treecko, $grovyle, $sceptile, $ekans, $arbok, $gible, $gabite, $garchomp, $charmander, $charmeleon, $charizard, $scraggy, $scrafty, $magikarp, $gyarados, $feebas, $milotic, $seviper, $bagon, $shelgon, $salamence, $axew, $fraxure, $haxorus, $horsea, $seadra, $dratini, $dragonair, $dragonite, $deino, $zweilous, $hydreigon, $kingdra, $goomy, $sliggoo, $goodra, $druddigon, $skrelp, $dragalge, $helioptile, $heliolisk, $tyrunt, $tyrantrum);
	return $numList;
}
function get_Query_These_Nums($numList, $equals, $command) {
	$legalQuery = "";
	if ($command == "OR") {
		$legalQuery = " AND (";
	}
	for ($i=0; $i<count($numList); $i++) {
		if ($command == "OR" && $i == 0) {
			$legalQuery = $legalQuery."num ".$equals." ".$numList[$i];
		}else{
			$legalQuery = $legalQuery." ".$command." num ".$equals." ".$numList[$i];
		}
	}
	if ($command == "OR") {
		$legalQuery = $legalQuery.")";
	}
	return $legalQuery;
}
?>