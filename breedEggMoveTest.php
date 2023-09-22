<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php 
	require 'moveIDList.php';
	if ($pokeNum == $bonsly) {//438
		array_push($moveList, $CURSE, $DEFENSE_CURL, $ENDURE, $HARDEN, $HEADBUTT, $ROLLOUT, $SAND_TOMB, $SELFDESTRUCT, $STEALTH_ROCK);
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
	}
?>
</body>
</html>