<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Transfer to PTD3";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	$whichDB = $profileInfo[5];
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
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Transfer to PTD3 - <a href="checkPokemon2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { ?>
					<div class="content">
						<p>Here is a list of your pokemon from this profile that are eligible to be transfered to PTD3, click on the gender you want it to be to transfer it to the PTD3 Pickup area.</p>
						<p>NOTE: This will remove the pokemon from your profile. <strong>YOU CANNOT TRANSFER THEM BACK!</strong> Also their moves will reset when transfered to PTD3 to the moves they would get at level 1 of their stage 1 evolution.</p>
					</div>
				</div>
                <?php 
$dbActual = get_PTD2_Pokemon_Database($whichDB);

$limit = 250;
$page = mysql_escape_string($_GET['page']);
if ($page) {
	$start = ($page -1) * $limit;
}else{
	$page = 1;
	$start = 0;
}

	$queryTotal = "SELECT COUNT(*) FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ?";
	$resultTotal = $dbActual->prepare($queryTotal);
	$resultTotal->bind_param("ii", $id, $whichProfile);
	$resultTotal->execute();
	$resultTotal->store_result();
	$resultTotal->bind_result($totalPickups);
	$resultTotal->fetch();
	$resultTotal->free_result();
	$resultTotal->close();
	
	if ($start > 0) {
		if ($totalPickups < $start) {
			$start = 0;
			$page = 1;
		}
	}
	if ($page == 0) {$page = 1;};
		$lastpage = ceil($totalPickups/$limit);
		$paginateText = 'Total: '.$totalPickups;
		$didFirst = false;
		$whichURL = "transferTo32.php?";
		
		if ($lastpage > 1) {
			$paginateText .= ' - Pages: ';
			if ($page > 1) {
				$paginateText .= '<a href="'.$whichURL.'page='.($page -1).'&'.$urlValidation.'">Prev</a>';
				$didFirst = true;
			}
			for ($counter = 1; $counter <= $lastpage; $counter++) {
				if ($didFirst == true) {
					$paginateText .= ' - ';
				}
				if ($counter == $page) {
					$paginateText .= $counter;
				}else{
					$paginateText .= '<a href="'.$whichURL.'page='.$counter.'&'.$urlValidation.'">'.$counter.'</a>';
				}
				$didFirst = true;
			}
			if ($page < $lastpage) {
				$paginateText .= ' - ';
				$paginateText .= '<a href="'.$whichURL.'page='.($page +1).'&'.$urlValidation.'">Next</a>';
				$didFirst = true;
			}
		}


	$query = "SELECT num, lvl, shiny, originalOwner, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, happy FROM trainer_pokemons WHERE trainerID = ? AND whichProfile = ? ORDER BY num, lvl LIMIT $start, $limit";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $whichProfile);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $originalOwner, $pokeID, $pokeNickname, $myTag, $move1, $move2, $move3, $move4, $pokeGender, $pokeItem, $pokeHoF);
	if ($hmp == 0) {
		?>
        <div class="block">
        <div class="content">
		<p>You have no pokemon in this profile.</p>
		</div>
		</div>
        <?php
	}else{
		echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
	for ($i=1; $i<=$hmp; $i++) {
		$result->fetch();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " (Hacked Version)";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		$extra = '<a href="transferTo32actual.php?'.$urlValidation.'&pokeID='.$pokeID.'">Transfer to PTD3</a>';
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $move1, $move2, $move3, $move4, $pokeNum, $extra, $pokeGender, $pokeItem, $pokeHoF);
	}
	echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
	}
	$result->free_result();
	$result->close();
	$dbActual->close();
?>
				<?php }?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>