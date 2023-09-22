<?php
include 'database_connections.php';
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
		$db = connect_To_Database_New();
	 	$query = "SELECT num, level, levelComparison, shiny FROM trade_wants WHERE tradePokeID = ?";
		$result = $db->prepare($query);
		$result->bind_param("s", $tradeID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->bind_result($num, $level, $levelComparison, $shiny);
		for ($i=1; $i<=$hmp; $i++) {
			$result->fetch();
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
			if ($num == 0) {
				$poke = 'Any Pokemon';
			}else{
				$poke = '<img src="'. get_Graphic_Url().'/games/ptd/small/'.$num.'_0.png"/>';
			}
			if (!$madeRequest) {
				$madeRequest = true;
				echo '<div class="block">
				<div class="content middle">
					<table>';
			}
			if ($i == 1 || $i == 3 || $i == 5) {
				echo '<tr>';
			}
			echo '<td nowrap>'.$poke.' - Lvl ('.$myLevel.') '.$isShiny.'</td>';
			if ($i == 2 || $i == 4 || $i == 6) {
				echo '</tr>';
			}
	 	}
	 	if (!$madeRequest) {
			echo '<div class="block tradeRequest"><div class="content middle">This trainer made no request for this trade.</div></div>';
	 	}else{
			echo '</table>
				</div>
			</div>';
	 	}
	}