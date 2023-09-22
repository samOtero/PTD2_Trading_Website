<?php
if ($item != 13 && $item != 100) {//Everstone and fake item
	if ($pokeNum == 64) {
		if ($pokeNickname == "Kadabra") {
			$pokeNickname = "Alakazam";
		}
		$pokeHoF = 0;
		$pokeNum = 65;
	}else if ($pokeNum == 67) {
		if ($pokeNickname == "Machoke") {
			$pokeNickname = "Machamp";
		}
		$pokeNum = 68;
		$pokeHoF = 0;
	}else if ($pokeNum == 75) {
		if ($pokeNickname == "Graveler") {
			$pokeNickname = "Golem";
		}
		$pokeNum = 76;
		$pokeHoF = 0;
	}else if ($pokeNum == 93) {
		if ($pokeNickname == "Haunter") {
			$pokeNickname = "Gengar";
		}
		$pokeNum = 94;
		$pokeHoF = 0;
	}else if ($pokeNum == 533) {
		if ($pokeNickname == "Gurdurr") {
			$pokeNickname = "Conkeldurr";
		}
		$pokeNum = 534;
		$pokeHoF = 0;
	}else if ($pokeNum == 525) {
		if ($pokeNickname == "Boldore") {
			$pokeNickname = "Gigalith";
		}
		$pokeNum = 526;
		$pokeHoF = 0;
	}else if ($pokeNum == 708) {
		if ($pokeNickname == "Phantump") {
			$pokeNickname = "Trevenant";
		}
		$pokeNum = 709;
		$pokeHoF = 0;
	}else if ($pokeNum == 710) {
		if ($pokeNickname == "Pumpkaboo") {
			$pokeNickname = "Gourgeist";
		}
		$pokeNum = 711;
		$pokeHoF = 0;
	}else if ($pokeNum == 682) {
		if ($item != 0) {
			if ($pokeNickname == "Spritzee") {
				$pokeNickname = "Aromatisse";
			}
			$pokeNum = 683;
			$pokeHoF = 0;
		}
	}else if ($pokeNum == 684) {
		if ($item != 0) {
			if ($pokeNickname == "Swirlix") {
				$pokeNickname = "Slurpuff";
			}
			$pokeNum = 685;
			$pokeHoF = 0;
		}
	}else if ($item == 25) {
		if ($pokeNum == 95) {
			if ($pokeNickname == "Onix") {
				$pokeNickname = "Steelix";
			}
			$pokeNum = 208;
			$item = 0;
			$pokeHoF = 0;
		}else if ($pokeNum == 123) {
			if ($pokeNickname == "Scyther") {
				$pokeNickname = "Scizor";
			}
			$pokeNum = 212;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 32) {
		if ($pokeNum == 79) {
			if ($pokeNickname == "Slowpoke") {
				$pokeNickname = "Slowking";
			}
			$pokeNum = 199;
			$item = 0;
			$pokeHoF = 0;
		}else if ($pokeNum == 61) {
			if ($pokeNickname == "Poliwhirl") {
				$pokeNickname = "Politoed";
			}
			$pokeNum = 186;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 66) {
		if ($pokeNum == 126) {
			if ($pokeNickname == "Magmar") {
				$pokeNickname = "Magmortar";
			}
			$pokeNum = 467;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 67) {
		if ($pokeNum == 112) {
			if ($pokeNickname == "Rhydon") {
				$pokeNickname = "Rhyperior";
			}
			$pokeNum = 464;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 65) {
		if ($pokeNum == 125) {
			if ($pokeNickname == "Electabuzz") {
				$pokeNickname = "Electivire";
			}
			$pokeNum = 466;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 64) {
		if ($pokeNum == 233) {
			if ($pokeNickname == "Porygon2") {
				$pokeNickname = "PorygonZ";
			}
			$pokeNum = 474;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 63) {
		if ($pokeNum == 117) {
			if ($pokeNickname == "Seadra") {
				$pokeNickname = "Kingdra";
			}
			$pokeNum = 230;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 62) {
		if ($pokeNum == 137) {
			if ($pokeNickname == "Porygon") {
				$pokeNickname = "Porygon2";
			}
			$pokeNum = 233;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 77) {
		if ($pokeNum == 366) {
			if ($pokeNickname == "Clamperl") {
				$pokeNickname = "Huntail";
			}
			$pokeNum = 367;
			$item = 0;
			$pokeHoF = 0;
		}
	}else if ($item == 78) {
		if ($pokeNum == 366) {
			if ($pokeNickname == "Clamperl") {
				$pokeNickname = "Gorebyss";
			}
			$pokeNum = 368;
			$item = 0;
			$pokeHoF = 0;
		}
	}
}else if ($item == 100) {
	$item = 0;
}
?>