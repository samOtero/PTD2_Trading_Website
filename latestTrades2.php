<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Latest Trades";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
	//require 'trade_To_Pickup_By_ID.php';
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
			?>
			<td id="main">
				<div class="block">
                <?php
					$backURL = "checkPokemon2.php";
				?>
					<div class="title"><p>Latest Trades - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
					do_Stuff();
				}
	 			?>
			</td>
		</tr>
	</table>
</div>
<?php
	function do_Stuff() {
		global $id, $urlValidation, $whichProfile, $currentSave;
		$action = $_REQUEST['Action'];
		$expireDate = date( 'Y-m-d', strtotime('-10 days'));
			$whichURL = "latestTrades2.php?";
			
			$db_New = connect_To_ptd2_Trading();
			
			$limit = 50;
			$page = mysql_escape_string($_GET['page']);
			if ($page) {
				$start = ($page -1) * $limit;
			}else{
				$page = 1;
				$start = 0;
			}
			$didPaginate = false;
				$didPaginate = true;
				//PAGINATE CODE				
				//$queryTotal = "SELECT COUNT(*) FROM trainer_trades WHERE lastTimeUsed > ? LIMIT 500";
//				$resultTotal = $db_New->prepare($queryTotal);
//				$resultTotal->bind_param("s",$expireDate);
//				$resultTotal->execute();
//				$resultTotal->store_result();
//				$resultTotal->bind_result($totalPickups);
//				$resultTotal->fetch();
//				$resultTotal->free_result();
//				$resultTotal->close();
				
				$totalPickups = 500;
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
					
					if ($lastpage > 1) {
					$paginateText .= ' - Pages: ';
					if ($page > 1) {
						$paginateText .= '<a href="'.$whichURL.'&page='.($page -1).'&'.$urlValidation.'">Prev</a>';
						$didFirst = true;
					}
					for ($counter = 1; $counter <= $lastpage; $counter++) {
						if ($didFirst == true) {
							$paginateText .= ' - ';
						}
						if ($counter == $page) {
							$paginateText .= $counter;
						}else{
							$paginateText .= '<a href="'.$whichURL.'&page='.$counter.'&'.$urlValidation.'">'.$counter.'</a>';
						}
						$didFirst = true;
					}
					if ($page < $lastpage) {
						$paginateText .= ' - ';
						$paginateText .= '<a href="'.$whichURL.'&page='.($page +1).'&'.$urlValidation.'">Next</a>';
						$didFirst = true;
					}
				}
				echo '</div><div class = "block"><div class="content"><p>'.$paginateText.'</p></div>';
				
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, sndCost, happy FROM trainer_trades WHERE lastTimeUsed > ? ORDER BY tradeUniqueID DESC LIMIT $start, $limit";
				$result = $db_New->prepare($query);
				$result->bind_param("s", $expireDate);		
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $sndCost, $pokeHoF);
			if ($hmp == 0) {
				echo '<div class="content"><p>No Pokemon have been found that match your criteria.</p></div></div>';
				return;
			}
			echo '</div>';
			$db = connect_To_Database();
			for ($i=1; $i<=$hmp; $i++) {
				$result->fetch();
				$pokeNickname = stripslashes($pokeNickname);
				$isHacked = "";
				if ($myTag == "h") {
					$isHacked = " - <b>(Hacked Version)</b>";
				}
				$pokeNickname = $pokeNickname.$isHacked;
				//$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
				//$result2 = $db->prepare($query);
				//$result2->bind_param("i", $currentTrainer);
				//$result2->execute();
				//$result2->store_result();
				//$result2->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
				//$result2->fetch();
				//$result2->free_result();
				//$result2->close();
				$accNickname = "";
				$avatar1 = "";
				$avatar2 = "";
				$avatar3 = "";
				$whichAvatar = "";
				$sndSaleText = "";
				if ($sndCost > 0) {
					$sndSaleText = ' | <a href="adoptTrade2.php?'.$urlValidation.'&tradeID='.$tradeID.'&IDCheck='.$id.'">Adopt Now for ('.$sndCost.') SnD Coins</a>';
				}
				pokeBox_Search2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, '<a href="requestTrade2.php?'.$urlValidation.'&tradeID='.$tradeID.'">Request Trade</a>'.$sndSaleText, ${avatar.$whichAvatar}, $accNickname, $tradeID, $pokeGender, $pokeItem, $pokeHoF);
			}
			//$db->close();
			$result->free_result();
			$result->close();
			$db_New->close();
			if ($didPaginate == true) {
				echo '<div class = "block"><div class="content"><p>'.$paginateText.'</p></div></div>';
			}
	}
	include 'template/footer.php';
?>
</body>
</html>