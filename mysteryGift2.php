<?php
	session_start();
	include 'database_connections.php';
	require 'moveList.php';
	include 'ptd2_basic.php';
	$email = $_REQUEST['Email'];
	$pass = $_REQUEST['Pass'];
	$code = $_REQUEST['Code'];
	if (empty($code) || empty($email) || empty($pass)) {
		echo 'Result=Success&Reason=wrong&Extra=Missing';
		exit;
	}
	
	$db = connect_To_Database();
	$query = "select trainerID from poke_accounts WHERE email = ? AND pass = ?";
	$result = $db->prepare($query);
	$result->bind_param("ss", $email, $pass);
	$result->execute();
	$result->store_result();
	$result->bind_result($id);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->free_result();
		$result->close();
		$db->close();
	}else{
		$result->free_result();
		$result->close();
		$db->close();
		echo 'Result=Success&Reason=wrong&Extra=NoUser';
		exit;
	}
	$code = strtolower($code);
	$mgcode = "canoncla";
	$giveawayCode = "gunlobst";
	if ($code == "pikablu") {
		$who = 183;
		$nickname = "Pikablu";
		$move1 = 1;
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == $mgcode ||$code == $giveawayCode) {
		$who = 692;
		$nickname = "Clauncher";
		$move1 = 31;
		$move2 = 141;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == $mgcode ||$code == $giveawayCode) {
		$who = 692;
		$nickname = "Clauncher";
		$move1 = 31;
		$move2 = 141;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "fennecfx") {
		$who = 653;
		$nickname = "Fennekin";
		$move1 = 6;
		$move2 = 3;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "frogfoam") {
		$who = 656;
		$nickname = "Froakie";
		$move1 = 48;
		$move2 = 5;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "chinkapi") {
		$who = 650;
		$nickname = "Chespin";
		$move1 = 1;
		$move2 = 5;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "givegift") {
		$who = 225;
		$nickname = "Delibird";
		$move1 = 562;
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "dinosmon") {
		$who = 696;
		$nickname = "Tyrunt";
		$move1 = 1;
		$move2 = 3;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "bracimon") {
		$who = 698;
		$nickname = "Amaura";
		$move1 = 121;
		$move2 = 5;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "nyaspers") {
		$who = 677;
		$nickname = "Espurr";
		$move1 = 6;
		$move2 = 43;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "swordedg") {
		$who = 679;
		$nickname = "Honedge";
		$move1 = 1;
		$move2 = 180;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "nuranura") {
		$who = 704;
		$nickname = "Goomy";
		$move1 = 1;
		$move2 = 11;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "noisebat") {
		$who = 714;
		$nickname = "Noibat";
		$move1 = 1;
		$move2 = 38;
		$move3 = 72;
		$move4 = 0;
	}else if ($code == "kametete") {
		$who = 688;
		$nickname = "Binacle";
		$move1 = 6;
		$move2 = 373;
		$move3 = 2;
		$move4 = 0;
	}else if ($code == "babyflwr") {
		$who = 669;
		$nickname = "Flabebe";
		$move1 = 1;
		$move2 = 16;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "kfkmushi") {
		$who = 664;
		$nickname = "Scatterbug";
		$move1 = 1;
		$move2 = 7;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "yayakoma") {
		$who = 661;
		$nickname = "Fletchling";
		$move1 = 1;
		$move2 = 5;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "hallween") {
		$who = 710;
		$nickname = "Pumpkaboo";
		$move1 = 74;
		$move2 = 502;
		$move3 = 81;
		$move4 = 0;
	}else if ($code == "kachkoru") {
		$who = 712;
		$nickname = "Bergmite";
		$move1 = 1;
		$move2 = 19;
		$move3 = 14;
		$move4 = 0;
	}else if ($code == "luchlibr") {
		$who = 701;
		$nickname = "Hawlucha";
		$move1 = 1;
		$move2 = 444;
		$move3 = 365;
		$move4 = 0;
	}else if ($code == "sunreptl") {
		$who = 694;
		$nickname = "Helioptile";
		$move1 = 48;
		$move2 = 3;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "ghostump") {
		$who = 708;
		$nickname = "Phantump";
		$move1 = 1;
		$move2 = 81;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "champpan") {
		$who = 674;
		$nickname = "Pancham";
		$move1 = 1;
		$move2 = 43;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "kelpscrp") {
		$who = 690;
		$nickname = "Skrelp";
		$move1 = 1;
		$move2 = 17;
		$move3 = 31;
		$move4 = 0;
	}else if ($code == "tunnbunn") {
		$who = 659;
		$nickname = "Bunnelby";
		$move1 = 1;
		$move2 = 97;
		$move3 = 43;
		$move4 = 0;
	}else if ($code == "gotmobil") {
		$who = 672;
		$nickname = "Skiddo";
		$move1 = 1;
		$move2 = 91;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "simbafir") {
		$who = 667;
		$nickname = "Litleo";
		$move1 = 1;
		$move2 = 43;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "stealkey") {
		$who = 707;
		$nickname = "Klefki";
		$move1 = 1;
		$move2 = 577;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "fancydog") {
		$who = 676;
		$nickname = "Furfrou";
		$move1 = 1;
		$move2 = 5;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "rarerock") {
		$who = 703;
		$nickname = "Carbink";
		$move1 = 1;
		$move2 = 14;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "mousante") {
		$who = 702;
		$nickname = "Dedenne";
		$move1 = 1;
		$move2 = 3;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "ncesprai") {
		$who = 682;
		$nickname = "Spritzee";
		$move1 = 578;
		$move2 = 76;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "cottcand") {
		$who = 684;
		$nickname = "Swirlix";
		$move1 = 1;
		$move2 = 76;
		$move3 = 0;
		$move4 = 0;
	}else if ($code == "finalink") {
		$who = 686;
		$nickname = "Inkay";
		$move1 = 1;
		$move2 = 42;
		$move3 = 201;
		$move4 = 0;
	//}else if ($code == "samsbd15") {
	}else{
		echo 'Result=Success&Reason=wrong&Extra=WrongCode&yourCode='.$code;
		exit;
	}
	$myLevel = 1;
	$isShiny = 0;
	$dayOfWeek = date("w");
	$specificDate = 4;
	if ($dayOfWeek == $specificDate) {
		$isShiny = 2;
	}
	//if ($dayOfWeek == 5) {//REMOVE AFTER THIS WEEK LOLS
		//$isShiny = 2;
	//}
	$item = 100;
	if ($code == "samsbd15") {
		$item = 85;
		$who = 384;
		$nickname = "Rayquaza";
		$move1 = 77;
		$move2 = 0;
		$move3 = 0;
		$move4 = 0;
	}
	$gender = 1;
	$myTag = "n";
	$originalTrainer = -$id;
	$db_New = connect_To_ptd2_Trading();
	if ($code == "pikablu") {
		$isShiny = 2;
	}else if ($code == "bday2886") {
		$isShiny = 1;
		$item = 75;
	}
	if ($code == $giveawayCode) {
		//$code = "cottonmn";
		$isShiny = 1;
		if ($dayOfWeek == $specificDate) {
			//$isShiny = 2;
		}
		//$isShiny = 2;
		if (isset($_COOKIE[$code])) {
			echo 'Result=Success&Reason=used';
			exit;
		}
		$query = "select trainerID from mysteryGift WHERE whichCode = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $code);
		$result->execute();
		$result->store_result();
		$howMany = $result->affected_rows;			
		if ($howMany >= 275) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo 'Result=Success&Reason=out';
			exit;
		}
		$result->free_result();
		$result->close();
		setcookie($code, "uI", time()+3600);
		$query = "select trainerID from mysteryGift WHERE trainerID = ? AND whichCode = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $code);
		$result->execute();
		$result->store_result();
		$howMany = $result->affected_rows;			
		if ($howMany) {
			$result->free_result();
			$result->close();
			$db_New->close();
			echo 'Result=Success&Reason=used';
			exit;
		}
		$db2 = connect_To_ptd2_Story_Database();
		if (get_Trainer_Pass($id, $db2) == 1) {
			$db2->close();
			echo 'Result=Success&Reason=trainer';
			exit;
		}
		$db2->close();
	}
		if ($who == 374 || $who == 479 || $who == 251 || $who == 615 || $who == 599 || $who == 646 || $who == 491 || $who == 337 || $who == 382 || $who == 383 || $who == 338 || $who == 703 || $who == 384) {//Beldum, Rotom, Celebi, Cryogonal, Klink, Kyurem, Darkrai, Lunatone, Kyogre, Groudon, Solrock, Rayquaza
			$gender = -1;
		}else if ($who == 669) { //flabebe
			$gender = 2;
		}
		$query = "INSERT INTO trainer_pickup (num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, currentTrainer, originalTrainer, gender, myTag) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";
		$result = $db_New->prepare($query);
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		$result->execute();
		//$result->execute();
		//$result->execute();
		$gender = 2;
		if ($code == "bday2886") {
			$who = 383;
			$move1 = 139;
			$item = 76;
			$nickname = "Groudon";
		}
		if ($who == 374 || $who == 479 || $who == 251 || $who == 615 || $who == 599 || $who == 646 || $who == 491 || $who == 337 || $who == 382 || $who == 383 || $who == 338 || $who == 703 || $who == 384) {//Beldum, Rotom, Celebi, Cryogonal, Klink, Kyurem, Darkrai, Lunatone, Kyogre, Groudon, Solrock, Rayquaza
			$gender = -1;
		}else if ($who == 539 || $who == 627 || $who == 313 || $who == 538) {//sawk, rufflet, volbeat, throh
			$gender = 1;
		}
		$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
		$result->execute();
		//$result->execute();
		//$result->execute();
		if ($code == "batterys") {
			$who = 312;
			$gender = 1;
			$nickname = "Minusy";
			$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
			$result->execute();
			$gender = 2;
			$result->bind_param("iiisiiiiiiiis", $who, $myLevel, $isShiny, $nickname, $move1, $move2, $move3, $move4, $item, $id, $originalTrainer, $gender, $myTag);
			$result->execute();
		}
		$result->close();
		$query = "INSERT INTO mysteryGift (trainerID, whichCode) VALUES (?,?)";
		$result = $db_New->prepare($query);
		$result->bind_param("is", $id, $code);
		$result->execute();
		$result->close();
		$db_New->close();
		echo 'Result=Success&Reason=right';
		function get_Trainer_Pass($myTrainerID, $dbNew) {
			$returnValue = 0;
			$query = "SELECT trainerID FROM  trainerPass WHERE trainerID = ?";
			$result = $dbNew->prepare($query);
			$result->bind_param("i", $myTrainerID);
			$result->execute();
			$result->store_result();
			$result->bind_result($oldID);	
			$hmp = $result->affected_rows;
			$result->close();
			if ($hmp == 0) {
				//do nothing! this trainer is not trainerPass
			}else{
				$returnValue = 1;
			}
			return $returnValue;
		 }
	//}
	//}else if ($code == "meditate" ||$code == "yogamons") {
//		$who = 307;
//		$nickname = "Meditite";
//		$move1 = 478;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "murmurin" ||$code == "soundmon") {
//		$who = 293;
//		$nickname = "Whismur";
//		$move1 = 48;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "acornguy" ||$code == "seedotsh") {
//		$who = 273;
//		$nickname = "Seedot";
//		$move1 = 478;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "wurmples" ||$code == "caterpil") {
//		$who = 265;
//		$nickname = "Wurmple";
//		$move1 = 1;
//		$move2 = 7;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "barbcode" ||$code == "bulimimi") {
//		$who = 339;
//		$nickname = "Barboach";
//		$move1 = 217;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "12020020" ||$code == "spinbear") {
//		$who = 327;
//		$nickname = "Spinda";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//}else if ($code == "a10144v4" || $code == "a10154v4" ||$code == "kittycat") {
//		$who = 300;
//		$nickname = "Skitty";
//		$move1 = 1;
//		$move2 = 3;
//		$move3 = 5;
//		$move4 = 175;
	//}else if ($code == "10056625" || $code == "mushroom") {
//		$who = 285;
//		$nickname = "Shroomish";
//		$move1 = 107;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
		//if ($code == "26442tt6") {
//		$who = 570;
//		$nickname = "Zori";
//		$move1 = 6;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "934p2cd4") {
//		$who = 447;
//		$nickname = "Rily";
//		$move1 = 4;
//		$move2 = 227;
//		$move3 = 354;
//		$move4 = 0;
	//if ($code == "9l744s54") {
//		$who = 582;
//		$nickname = "Vanilla";
//		$move1 = 369;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "5543v454") {
//		$who = 403;
//		$nickname = "Shinyx";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "84871514") {
//		$who = 333;
//		$nickname = "Swayell";
//		$move1 = 42;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "3d2c2yz4") {
//		$who = 501;
//		$nickname = "Oshawott";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "bs3032h4") {
//			$who = 252;
//			$nickname = "Treecky";
//			$move1 = 48;
//			$move2 = 43;
//			$move3 = 0;
//			$move4 = 0;
//if ($code == "7d372g14") {
//		$who = 328;
//		$nickname = "Pinchy";
//		$move1 = 19;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	// if ($code == "36b56314") {
//		$who = 451;
//		$nickname = "Skorupy";
//		$move1 = 19;
//		$move2 = 8;
//		$move3 = 43;
//		$move4 = 0;
	//if ($code == "y303n814") {
//		$who = 408;
//		$nickname = "Crany";
//		$move1 = 216;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "55465464") {
//		$who = 588;
//		$nickname = "Karrablasty";
//		$move1 = 42;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "x29633b4") {
//		$who = 304;
//		$nickname = "Irony";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "8355s2b4") {
//		$who = 363;
//		$nickname = "Sphery";
//		$move1 = 121;
//		$move2 = 25;
//		$move3 = 31;
//		$move4 = 5;
//	if ($code == "755a1234") {
//		$who = 359;
//		$nickname = "Absy";
//		$move1 = 6;
//		$move2 = 168;
//		$move3 = 0;
//		$move4 = 0;
//	
	//if ($code == "m64g14z4") {
//		$who = 554;
//		$nickname = "Darumaky";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "cellular") {
//		$who = 577;
//		$nickname = "Solosy";
//		$move1 = 498;
//		$move2 = 0;
//		$move3 = 0;
		//$move4 = 0;
	//if ($code == "fe71b264") {
//		$who = 280;
//		$nickname = "Raltsy";
//		$move1 = 5;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "ptoc58c4") {
//		$who = 479;
//		$nickname = "Rotimy";
//		$move1 = 22;
//		$move2 = 23;
//		$move3 = 81;
//		$move4 = 74;
//}else if ($code == "prtyugly") {
//		$who = 349;
//		$nickname = "Feeby";
//		$move1 = 141;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "4yz52574") {
//		$who = 418;
//		$nickname = "Buizy";
//		$move1 = 231;
//		$move2 = 5;
//		$move3 = 109;
//		$move4 = 0;
//	if ($code == "kf4e1754") {
//		$who = 357;
//		$nickname = "Tropical";
//		$move1 = 15;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "q9a13r44") {
//		$who = 251;
//		$nickname = "Celebi";
//		$move1 = 20;
//		$move2 = 115;
//		$move3 = 9;
//		$move4 = 512;
	//if ($code == "nu353bb4") {
//		$who = 566;
//		$nickname = "Archen";
//		$move1 = 4;
//		$move2 = 43;
//		$move3 = 75;
//		$move4 = 0;
	//if ($code == "6f2b2g84") {
//		$who = 551;
//		$nickname = "Sandile";
//		$move1 = 40;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "z42sgcf4") {
//		$who = 524;
//		$nickname = "Roggenrolla";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "shldface") {
//		$who = 410;
//		$nickname = "Shieldon";
//		$move1 = 1;
//		$move2 = 83;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "qt4aw176" || $code == "qt4aw167") {
//		$who = 453;
//		$nickname = "Croagunk";
//		$move1 = 74;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "serpenty") {
//		$who = 336;
//		$nickname = "Seviper";
//		$move1 = 70;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "390s23ch") {
//			$who = 335;
//			$nickname = "Zangoose";
//			$move1 = 6;
//			$move2 = 43;
//			$move3 = 0;
//			$move4 = 0;
//		}else if ($code == "slackers") {
//			$who = 287;
//			$nickname = "Slakoth";
//			$move1 = 6;
//			$move2 = 340;
//			$move3 = 0;
//			$move4 = 0;
//if ($code == "cg24sz2t") {
//		$who = 290;
//		$nickname = "Nincada";
//		$move1 = 6;
//		$move2 = 14;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "busybees") {
//		$who = 415;
//		$nickname = "Combee";
//		$move1 = 15;
//		$move2 = 76;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "2dl53sdc" || $code == "2d5l3sdc" ) {
//		$who = 610;
//		$nickname = "Axew";
//		$move1 = 6;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "4gd3wbc6") {
//		$who = 255;
//		$nickname = "Torchic";
//		$move1 = 6;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "p6v6773u") {
//		$who = 605;
//		$nickname = "Elgyem";
//		$move1 = 20;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "4rig43k6") {
//		$who = 602;
//		$nickname = "Tynamo";
//		$move1 = 1;
//		$move2 = 23;
//		$move3 = 232;
//		$move4 = 233;
//	}else if ($code == "redpiggy") {
//		$who = 498;
//		$nickname = "Tepig";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "gothgirl") {
//		$who = 574;
//		$nickname = "Gothita";
//		$move1 = 48;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "dogybuzz") {
//		$who = 309;
//		$nickname = "Electrike";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "snoflake") {
//		$who = 615;
//		$nickname = "Cryogonal";
//		$move1 = 198;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "f2cr4353") {
//		$who = 613;
//		$nickname = "Cubchoo";
//		$move1 = 121;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "evilcode") {
//		$who = 442;
//		$nickname = "Spiritomb";
//		$move1 = 33;
//		$move2 = 280;
//		$move3 = 81;
//		$move4 = 455;
//	}else if ($code == "myolmask") {
//		$who = 562;
//		$nickname = "Yamask";
//		$move1 = 74;
//		$move2 = 83;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "6ewdx15z" || $code == "6ewdx156") {
//		$who = 393;
//		$nickname = "Piplup";
//		$move1 = 48;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "ulikemud") {
//		$who = 258;
//		$nickname = "Mudkip";
//		$move1 = 1;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "3aw746s8") {
//		$who = 599;
//		$nickname = "Klink";
//		$move1 = 269;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "megawile") {
//		$who = 303;
//		$nickname = "Mawile";
//		$move1 = 74;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "8t85bw4s") {
//		$who = 590;
//		$nickname = "Foongus";
//		$move1 = 107;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "darkrose") {
//		$who = 315;
//		$nickname = "Roselia";
//		$move1 = 107;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "t25w3hpg") {
//		$who = 539;
//		$nickname = "Sawk";
//		$move1 = 539;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "sumopalm") {
//		$who = 296;
//		$nickname = "Makuhita";
//		$move1 = 1;
//		$move2 = 12;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "5aq36c56") {
//		$who = 543;
//		$nickname = "Venipede";
//		$move1 = 68;
//		$move2 = 25;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "pepelepw") {
//		$who = 434;
//		$nickname = "Stunky";
//		$move1 = 6;
//		$move2 = 12;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "9s5w5066") {
//		$who = 636;
//		$nickname = "Larvesta";
//		$move1 = 10;
//		$move2 = 7;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "abshrimp") {
//		$who = 347;
//		$nickname = "Anorith";
//		$move1 = 6;
//		$move2 = 14;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "4h3a1gmq") {
//		$who = 646;
//		$nickname = "Kyurem";
//		$move1 = 407;
//		$move2 = 29;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "shinykyr") {
//		$who = 646;
//		$nickname = "Kyurem";
//		$move1 = 407;
//		$move2 = 29;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "f34u1944") {
//		$who = 513;
//		$nickname = "Pansear";
//		$move1 = 6;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "darktong") {
//		$who = 631;
//		$nickname = "Heatmor";
//		$move1 = 353;
//		$move2 = 119;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "n1q5al6d" || $code == "n5q2al6d") {
//		$who = 417;
//		$nickname = "Pachirisu";
//		$move1 = 478;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "elezebra") {
//		$who = 522;
//		$nickname = "Blitzle";
//		$move1 = 4;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
	//if ($code == "9f3qst2a") {
//		$who = 302;
//		$nickname = "Sableye";
//		$move1 = 6;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "darkbite") {
//		$who = 318;
//		$nickname = "Carvanha";
//		$move1 = 19;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "l6n34j9w") {
//		$who = 471;
//		$nickname = "Glaceon";
//		$move1 = 1;
//		$move2 = 3;
//		$move3 = 99;
//		$move4 = 0;
//	if ($code == "p4vyw21b") {
//		$who = 495;
//		$nickname = "Snivy";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "purpturt") {
//		$who = 387;
//		$nickname = "Turtwig";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	if ($code == "d0qz63c5") {
//		$who = 341;
//		$nickname = "Corphish";
//		$move1 = 11;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "brhtorca") {
//		$who = 320;
//		$nickname = "Wailmer";
//		$move1 = 141;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//if ($code == "u4h75s1e") {
//		$who = 677;
//		$nickname = "Espurr";
//		$move1 = 6;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "oddbirdy") {
//		$who = 561;
//		$nickname = "Sigilyph";
//		$move1 = 15;
//		$move2 = 117;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "41510733") {
//		$who = 679;
//		$nickname = "Honedge";
//		$move1 = 1;
//		$move2 = 180;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "da151004") {
//		$who = 491;
//		$nickname = "Darkrai";
//		$move1 = 489;
//		$move2 = 49;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "justakey") {
//		$who = 707;
//		$nickname = "Klefki";
//		$move1 = 1;
//		$move2 = 577;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "15181220") {
//		$who = 299;
//		$nickname = "Nosepass";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "magneose") {
//		$who = 299;
//		$nickname = "Nosepass";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "1050020k") {
//		$who = 653;
//		$nickname = "Fennekin";
//		$move1 = 6;
//		$move2 = 3;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "firefoxy") {
//		$who = 653;
//		$nickname = "Fennekin";
//		$move1 = 6;
//		$move2 = 3;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "sn348010") {
//		$who = 650;
//		$nickname = "Chespin";
//		$move1 = 1;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//	}else if ($code == "woodball") {
//		$who = 650;
//		$nickname = "Chespin";
//		$move1 = 1;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "50014250" || $code == "ninjfrog") {
//		$who = 656;
//		$nickname = "Froakie";
//		$move1 = 48;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;\
//}else if ($code == "jp175500" || $code == "samupand") {
//		$who = 674;
//		$nickname = "Pancham";
//		$move1 = 1;
//		$move2 = 43;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "35020075" || $code == "slimedrg") {
//		$who = 704;
//		$nickname = "Goomy";
//		$move1 = 1;
//		$move2 = 11;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "33150150" || $code == "firebird") {
//		$who = 661;
//		$nickname = "Fletchling";
//		$move1 = 1;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "1530gog7" || $code == "luchalib") {
//		$who = 701;
//		$nickname = "Hawlucha";
//		$move1 = 1;
//		$move2 = 365;
//		$move3 = 444;
//		$move4 = 0;
//}else if ($code == "10125020" || $code == "darkpupy") {
//		$who = 261;
//		$nickname = "Poochyena";
//		$move1 = 1;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "19241210" || $code == "lilypads") {
//		$who = 270;
//		$nickname = "Lotad";
//		$move1 = 74;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "11304518" || $code == "araiguma") {
//		$who = 263;
//		$nickname = "Zigzagoon";
//		$move1 = 1;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "25215020" || $code == "seagulls") {
//		$who = 278;
//		$nickname = "Wingull";
//		$move1 = 31;
//		$move2 = 5;
//		$move3 = 0;
//		$move4 = 0;
//}else if ($code == "35030010" || $code == "gerridae") {
//		$who = 283;
//		$nickname = "Surskit";
//		$move1 = 11;
//		$move2 = 0;
//		$move3 = 0;
//		$move4 = 0;
				
			?>