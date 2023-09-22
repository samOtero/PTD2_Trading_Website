<?php
include 'database_connections.php';
include 'ptd2_basic.php';
$tradeID = $_GET['tradeID'];

if(isset($tradeID)) {
	show_Trade_Wants($tradeID);
} else {
	echo '<div class="block tradeRequest"><div class="content middle">Error!.</div></div>';
}
function get_Graphic_Url() {
	return 'http://www.ptdtrading.com';
}
function show_Trade_Wants($tradeID) {
		$madeRequest = false;
		$db = connect_To_ptd2_Trading();
	 	$query = "SELECT num, level, levelComparison, shiny, gender, whichRequest FROM trade_wants WHERE tradePokeID = ? ORDER BY whichRequest";
		$result = $db->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($num, $level, $levelComparison, $shiny, $gender, $whichRequest);
		$currentRequest = 1;
		$startRow = 1;
		$endRow = 2;
		$requestCount = 1;
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
			$genderName = get_Gender($gender);
			$genderIcon = "";
			if ($genderName != "none") {
				$genderIcon = '<img src = "'. get_Graphic_Url().'/trading_center/images/'.$genderName.'.png"/>';
			}
			if ($shiny == 1) {
				$isShiny = "<b>Shiny</b>";
			}else if ($shiny == 2) {
				$isShiny = "<b>Shadow</b>";
			}else if ($shiny == -1) {
				$isShiny = "Regular or <b>Shiny</b> or <b>Shadow</b>";
			}else{
				$isShiny = "Regular";
			}
			if ($levelComparison == 1) {
				$comp = "=";
			}else if ($levelComparison == 2) {
				$comp = "<=";
			}else if ($levelComparison == 3) {
				$comp = ">=";
			}else if ($levelComparison == 4) {
				$comp = "<";
			}else if ($levelComparison == 5) {
				$comp = ">";
			}
			if ($level == 0) {
				$myLevel = "Any";
			}else{
				$myLevel = $comp.$level;
			}
			if ($num == -2) {
				$poke = 'Any Pokemon';
			}else{
				$poke = '<img src="'. get_Graphic_Url().'/games/ptd/small/'.$num.'_0.png"/>';
			}
			if (!$madeRequest) {
				$madeRequest = true;
				echo '<div class="block">
				<div class="content middle">
					<table>';
					$currentRequest = $whichRequest;
			}
			if ($currentRequest != $whichRequest) {
				$requestCount = 1;
				$startRow =1;
				$endRow = 2;
				$currentRequest = $whichRequest;
				echo '<tr><td nowrap colspan="2"><div class = "title centerMe">OR</div></td></tr>'; 
			}
			if ($requestCount == $startRow) {
				$startRow+=2;
				echo '<tr>';
			}
			echo '<td nowrap>'.$poke.$genderIcon.' - Lvl ('.$myLevel.') '.$isShiny.'</td>';
			if ($requestCount == $endRow) {
				$endRow+=2;
				echo '</tr>';
			}
			$requestCount++;
	 	}
	 	if (!$madeRequest) {
			echo '<div class="block tradeRequest"><div class="content middle">This trainer made no request for this trade.</div></div>';
	 	}else{
			echo '</table>
				</div>
			</div>';
	 	}
	}