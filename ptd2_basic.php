<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
function get_Story_Badge($myTrainerID, $whichProfile) {
 	$db = connect_To_ptd2_Story_Extra_Database();
	$myBadge = 0;
	$query = "select whichValue, whichStat from extraInfo WHERE userID = ? AND whichProfile = ? AND (whichStat = 7 OR whichStat = 59 OR whichStat = 64)";
	$result = $db->prepare($query);
	$result->bind_param("ii", $myTrainerID, $whichProfile);
	$result->execute();
	$result->store_result();
	$result->bind_result($currentValue, $currentStat);
	$totalValues = $result->affected_rows;
	for ($i=1; $i<=$totalValues; $i++) {
		$result->fetch();
		if ($currentStat == 7 && $currentValue != -1) {//first Gym
			$myBadge++;
		}else if ($currentStat == 59 && $currentValue != -1) {//second gym
			$myBadge++;
		}else if ($currentStat == 64 && $currentValue != -1) {//third gym
			$myBadge++;
			if ($currentValue >= 7) {// gym 4
				$myBadge++;
			}
			if ($currentValue >= 9) {// gym 5&6
				$myBadge++;
				$myBadge++;
			}
			if ($currentValue >= 11) {// gym 7
				$myBadge++;
			}
			if ($currentValue >= 12) {// gym 8
				$myBadge++;
			}
		}
	}
	$result->free_result();
	$result->close();
	$db->close;
	return $myBadge;
 }
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_Extra_Value($db_New, $id, $whichProfile, $whichExtra) {
	$whichValue = "-1";
	$query = "select whichValue from extraInfo WHERE userID = ? AND whichProfile = ? AND whichStat = ?";
	$result = $db_New->prepare($query);
	$result->bind_param("iis", $id, $whichProfile, $whichExtra);
	$result->execute();
	$result->store_result();
	$result->bind_result($myValue);
	$totalValues = $result->affected_rows;
	$result->fetch();
	$result->close();
	if ($totalValues > 0) {
		$whichValue = $myValue;
	}
	return $whichValue;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function trade_Successful($db_New, $id, $otherTrainer, $transactionFlag=true) {
	if ($id == $otherTrainer) {
		return $transactionFlag;
	}
	$todayFormat = date('Y-m-d');
	$query = "DELETE from tradeRecords WHERE trainerID = ? AND tradedOn != ? AND used != 0";
	$result = $db_New->prepare($query);
	$result->bind_param("is", $id, $todayFormat);
	if (!$result->execute()) {
		return false;
	}
	$result->close();
	$query = "select trainerID, tradedOn from tradeRecords WHERE trainerID = ? AND traderID = ? AND tradedOn = ?";
	$result = $db_New->prepare($query);
	$today = strtotime($todayFormat);
	$result->bind_param("iis", $id, $otherTrainer, $todayFormat);
	$result->execute();
	$result->store_result();
	$result->bind_result($tempTrainerID, $dateTraded);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
		$dayTraded = strtotime($dateTraded);
		if ($dayTraded >= $today) {
			return $transactionFlag;
		}	
	}else{
		$result->close();
	}
	$query = "INSERT INTO tradeRecords(trainerID, traderID, tradedOn) VALUES (?,?,?)";
	$result = $db_New->prepare($query);
	$result->bind_param("iis", $id, $otherTrainer,$todayFormat);
	if (!$result->execute()) {
		return false;
	}
	$result->close();
	$query = "INSERT INTO tradeRecords(trainerID, traderID, tradedOn) VALUES (?,?,?)";
	$result = $db_New->prepare($query);
	$result->bind_param("iis", $otherTrainer, $id, $todayFormat);
	if (!$result->execute()) {
		return false;
	}
	$result->close();
	return $transactionFlag;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function itemBox2($itemNum, $dailyCost, $sndCost, $dailyAmount, $sndAmount, $urlValidation) {
	$itemName = get_Item_Name($itemNum);
	$itemDescription = get_Item_Description($itemNum);
	?>
    <div class="block item">
					<div class="image_holder">
						<div class="image_center">
							<img src="trading_center/item/<?php echo $itemNum ?>.png" alt="" class="image" />
						</div>
					</div>
					<span class="name"><?php echo $itemName ?></span>
					<span class="total"></span>
					<div class="description">
						<?php echo $itemDescription ?>
					</div>
                    <div class="cost">
						<?php echo '<a href="item_Buy2.php?'.$urlValidation.'&num='.$itemNum.'&type=snd">Buy ('.$sndAmount.') for '.$sndCost.' Snd Coin</a><br> <a href="item_Buy2.php?'.$urlValidation.'&num='.$itemNum.'&type=daily">Buy ('.$dailyAmount.') for '.$dailyCost.' Daily Coins</a>' ?>
					</div>
				</div>
    <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function itemBox_Simple2($itemNum) {
	$itemName = get_Item_Name($itemNum);
	$itemDescription = get_Item_Description($itemNum);
	?>
    <div class="block item">
					<div class="image_holder">
						<div class="image_center">
							<img src="trading_center/item/<?php echo $itemNum ?>.png" alt="" class="image" />
						</div>
					</div>
					<span class="name"><?php echo $itemName ?></span>
					<span class="total"></span>
					<div class="description">
						<?php echo $itemDescription ?>
					</div>
				</div>
    <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function pokeBox_Request2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $extra, $whichAvatar, $ownerName, $gender, $item, $currentTrainer, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	$divType = "block pokemon_compact wide shiny clear";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact wide clear";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact wide shadow clear";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
	<div class="<?php echo $divType?>">
    <div class="menu_box">
						<div class="menu">
							<p>Current Owner: <?php echo $currentTrainer ?></p>
							<p class="middle"><img src="trading_center/avatar/<?php echo $whichAvatar?>.png"> <?php echo $ownerName?></p>
						</div>
					</div>
                    <img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions">
                    	<?php echo $extra?>
					</div>
				</div>
                 <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function pokeBox_Your_Trade2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $numRequest, $tradeID, $urlValidation, $gender, $item, $cost, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	$divType = "block pokemon_compact wide shiny clear";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact wide clear";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact wide shadow clear";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
    <div class="<?php echo $divType?>">
					<div class="menu_box">
						<div class="menu">
							<p>TradeID (<?php echo $tradeID?>)</p>
                            <?php if ($cost > 0) { echo '<p>Adopt Now for ('.$cost.')</p>'; } ?>
							<p><a href="viewRequest2.php?<?php echo $urlValidation ?>&tradeID=<?php echo $tradeID ?>">View Request (<?php echo $numRequest ?>)</a></p>
							<p><a href="callBack2.php?<?php echo $urlValidation ?>&tradeID=<?php echo $tradeID ?>">Call Pokemon Back To Profile</a></p>
						</div>
					</div>
					<img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions">
						&nbsp;
					</div>
				</div>
                 <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function pokeBox2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $extra, $gender, $item, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	$divType = "block pokemon_compact shiny";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact shadow";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
	<div class="<?php echo $divType?>">
                    <img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions">
						<?php echo $extra?>
					</div>
				</div>
                 <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function pokeBox_Search2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $extra, $whichAvatar, $ownerName, $tableID, $gender, $item, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	//$divType = "block pokemon_compact wide shiny clear";
	$divType = "block pokemon_compact shiny wide2 clear";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact wide2 clear";
		//$divType = "block pokemon_compact wide clear";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact shadow wide2 clear";
		//$divType = "block pokemon_compact wide shadow clear";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
	<div class="<?php echo $divType?>" id = "<?php echo $tableID?>">
    <!--<div class="menu_box">
						<div class="menu">
							<p>Current Owner:</p>
							<p class="middle"><img src="trading_center/avatar/<?php// echo $whichAvatar?>.png"> <?php// echo $ownerName?></p>
						</div>
					</div>-->
                    <img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level2">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions">
                    	<?php echo $extra?><a href="#<?php echo $tableID?>" id="getTradeRequestBtn_<?php echo $tableID?>" onClick="getTradeRequest2('<?php echo $tableID?>');return false;"> | View Trade Request</a>
					</div>
				</div>
                <div id="requestLoad_<?php echo $tableID?>" class="tradeRequest"></div>
                 <?php
}
function pokeBoxCreateTrade2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $extra, $pokeID, $whichProfile, $gender, $item, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	$divType = "block pokemon_compact shiny";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact shadow";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
    <div id="pickupBox_<?php echo $pokeID ?>">
	<div class="<?php echo $divType?>">
                    <img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions" id="create_<?php echo $pokeID ?>">
						<?php echo $extra?>
                         | <a href="#abandon<?php echo $pokeID?>" onClick="abandonPokeCreate2('<?php echo $pokeID?>', '<?php echo $whichProfile?>');return false;">Abandon</a>
					</div>
				</div>
                </div>
                 <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function pokeBoxPickup2($nickname, $level, $isShiny, $move1, $move2, $move3, $move4, $who, $extra, $pokeID, $whichProfile, $gender, $item, $happy=0) {
	$genderName = get_Gender($gender);
	$genderIcon = "";
	if ($genderName != "none") {
		$genderIcon = '<img src = "trading_center/images/'.$genderName.'.png"/>';
	}
	$divType = "block pokemon_compact shiny";
	$starIcon = '<img src = "images/star_small.png"/>';
	if ($isShiny == 0) {
		$divType = "block pokemon_compact";
		$starIcon = '';
	}else if ($isShiny == 2) {
		$divType = "block pokemon_compact shadow";
		$starIcon = '';
		$isShiny = 0;
	}
	$hallOfFame = '';
	if ($happy != 0) {
		$hallOfFame = '<img src = "images/ribbon_smaller.png"/>HoF';
	}
	?> 
    <div id="pickupBox_<?php echo $pokeID ?>">
	<div class="<?php echo $divType?>">
                    <img src="games/ptd/small/<?php echo $who?>_<?php echo $isShiny?>.png" class="image" />
					<span class="name"><?php echo $nickname.$genderIcon.$starIcon.$hallOfFame?></span>
					<span class="level">Lvl <?php echo $level?></span>
					<div class="moves">
						<table>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move1)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move2)?></td>
							</tr>
							<tr>
								<td class="left"><?php echo get_Move_Name_By_ID($move3)?></td>
								<td class="right"><?php echo get_Move_Name_By_ID($move4)?></td>
							</tr>
						</table>
					</div>
                    <p>Item: <?php echo get_Item_Name($item)?></p>
					<div class="actions" id="pickup_<?php echo $pokeID ?>">
						<a href="#pickup<?php echo $pokeID?>" id="quickPickup_<?php echo $pokeID?>" onClick="pickupPoke2('<?php echo $pokeID?>', '<?php echo $whichProfile?>');return false;">Send To Profile</a> | <a href="#abandon<?php echo $pokeID?>" onClick="abandonPoke2('<?php echo $pokeID?>', '<?php echo $whichProfile?>');return false;">Abandon</a>
					</div>
				</div>
                </div>
                 <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function update_Current_Save($db, $id, $currentSave) {
	//echo "updated Current SAve";
	$newCurrentSave = uniqid(true);
	$reason = "success";
	$query = "UPDATE poke_accounts SET currentSave2 = ? WHERE trainerID = ? AND currentSave2 = ?";
	$result = $db->prepare($query);
	$result->bind_param("sis", $newCurrentSave, $id, $currentSave);
	$result->execute();
	if ($result->sqlstate=="00000") {
		$currentSave = $newCurrentSave;
		$_SESSION['currentSave2'] = $currentSave;		
	}else{
		$reason = "error";
	}
	$result->close();
	return array($reason, $currentSave);
}
/////////////////////////////////////////////////////////////////////////////////////////////////
function get_Current_Save_Status($id, $currentSave) { //Connect to original user database and confirm that their currentSave has not changed.
	$reason = "good";
	$db = connect_To_Database();
	$query = "select trainerID from poke_accounts WHERE trainerID = ? AND currentSave2 = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($tempAvatar);			
	if ($result->affected_rows) {
		$result->fetch();		
	}else{
		echo "user not found $id $currentSave";
		$reason = "savedOutside";
	}
	$result->free_result();
	$result->close();
	$db->close();
	return $reason;
}
 /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Basic_Profile_Info($id, $whichProfile) {
 	$db = connect_To_ptd2_Story_Database();
	$query = "select Version, Nickname, gender, Money, avatar, whichDB from poke_accounts WHERE trainerID = ? AND whichProfile = ?";
	$result = $db->prepare($query);
	$result->bind_param("ii", $id, $whichProfile);
	$result->execute();
	$result->store_result();
	$result->bind_result($myVersion2, $myNickname2, $myGender2, $myMoney2, $myAvatar2, $myDB);
	$totalProfiles = $result->affected_rows;
	if ($totalProfiles == 1) {
		$result->fetch();
		$myAvatarName = get_Avatar_Name($myGender2, $myAvatar2);
		$myVersionName = get_Version_Name($myVersion2);
		$result->free_result();
		$result->close();
		$db->close();
		$myBadge2 = get_Story_Badge($id, $whichProfile);
		return array($myVersionName, $myNickname2, $myBadge2, $myMoney2, $myAvatarName, $myDB);
	}
	$result->free_result();
	$result->close();
	$db->close();
	return NULL;	
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Item_Name($itemNum) {
	 if ($itemNum == 18) {
	 	return "Fire Stone";
	 }
	 if ($itemNum == 19) {
	 	return "Water Stone";
	 }
	 if ($itemNum == 20) {
	 	return "Thunder Stone";
	 }
	 if ($itemNum == 21) {
	 	return "Moon Stone";
	 }
	 if ($itemNum == 22) {
	 	return "Leaf Stone";
	 }
	 if ($itemNum == 23) {
	 	return "Dusk Stone";
	 }
	 if ($itemNum == 24) {
	 	return "Reaper Cloth";
	 }
	 if ($itemNum == 25) {
	 	return "Metal Coat";
	 }
	  if ($itemNum == 26) {
	 	return "Dawn Stone";
	 }
	 if ($itemNum == 27) {
	 	return "Sun Stone";
	 }
	 if ($itemNum == 17) {
	 	return "Friendship Doll";
	 }
	 if ($itemNum == 11) {
	 	return "Oran Berry";
	 }
	 if ($itemNum == 12) {
	 	return "Chilan Berry";
	 }
	 if ($itemNum == 13) {
	 	return "Everstone";
	 }
	 if ($itemNum == 14) {
	 	return "Neeverstone";
	 }
	 if ($itemNum == 15) {
	 	return "Gold Incense";
	 }
	 if ($itemNum == 16) {
	 	return "Dark Incense";
	 }
	 if ($itemNum == 32) {
	 	return "King's Rock";
	 }
	 if ($itemNum == 34) {
	 	return "Shiny Stone";
	 }
	 if ($itemNum == 35) {
	 	return "Silk Scarf";
	 }
	 if ($itemNum == 36) {
	 	return "Wide Lens";
	 }
	 if ($itemNum == 37) {
	 	return "Zoom Lens";
	 }
	 if ($itemNum == 38) {
	 	return "Smoke Ball";
	 }
	 if ($itemNum == 39) {
	 	return "Charcoal";
	 }
	 if ($itemNum == 40) {
	 	return "Miracle Seed";
	 }
	 if ($itemNum == 41) {
	 	return "Mystic Water";
	 }
	 if ($itemNum == 42) {
	 	return "Yellow Flute";
	 }
	 if ($itemNum == 43) {
	 	return "Metronome";
	 }
	 if ($itemNum == 53) {
	 	return "Manectite";
	 }
	 if ($itemNum == 54) {
	 	return "Wave Incense";
	 }
	 if ($itemNum == 55) {
	 	return "Sea Incense";
	 }
	 if ($itemNum == 56) {
	 	return "Lax Incense";
	 }
	 if ($itemNum == 57) {
	 	return "Full Incense";
	 }
	 if ($itemNum == 58) {
	 	return "Luck Incense";
	 }
	 if ($itemNum == 59) {
	 	return "Odd Incense";
	 }
	 if ($itemNum == 60) {
	 	return "Rock Incense";
	 }
	 if ($itemNum == 61) {
	 	return "Rose Incense";
	 }
	 if ($itemNum == 62) {
	 	return "Up-grade";
	 }
	 if ($itemNum == 63) {
	 	return "Dragon Scale";
	 }
	 if ($itemNum == 64) {
	 	return "Dubious Disc";
	 }
	 if ($itemNum == 65) {
	 	return "Electirizer";
	 }
	 if ($itemNum == 66) {
	 	return "Magmarizer";
	 }
	 if ($itemNum == 67) {
	 	return "Protector";
	 }
	 if ($itemNum == 68) {
	 	return "Oval Stone";
	 }
	 if ($itemNum == 69) {
	 	return "Razor Claw";
	 }
	 if ($itemNum == 70) {
	 	return "Razor Fang";
	 }
	 if ($itemNum == 71) {
	 	return "Aerodactylite";
	 }
	 if ($itemNum == 72) {
	 	return "Blastoisinite";
	 }
	 if ($itemNum == 73) {
	 	return "Ampharosite";
	 }
	 if ($itemNum == 74) {
	 	return "Venusaurite";
	 }
	 if ($itemNum == 75) {
	 	return "Blue Orb";
	 }
	 if ($itemNum == 76) {
	 	return "Red Orb";
	 }
	 if ($itemNum == 77) {
	 	return "Deep Sea Tooth";
	 }
	 if ($itemNum == 78) {
	 	return "Deep Sea Scale";
	 }
	 if ($itemNum == 79) {
	 	return "Pure Incense";
	 }
	 if ($itemNum == 80) {
	 	return "Beedrillite";
	 }
	 if ($itemNum == 81) {
	 	return "Mewtwonite X";
	 }
	 if ($itemNum == 82) {
	 	return "Charizardite X";
	 }
	 if ($itemNum == 83) {
	 	return "Garchompite";
	 }
	 if ($itemNum == 84) {
	 	return "Prison Bottle";
	 }
	 if ($itemNum == 85) {
	 	return "Meteorite";
	 }
 	return "None";
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Item_Description($itemNum) {
	 if ($itemNum == 17) {
		 return "A cute doll that makes certain species of Pokémon evolve.";
	 }
	 if ($itemNum == 18) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It is colored orange.";
	 }
	 if ($itemNum == 19) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It is a clear, light blue.";
	 }
	 if ($itemNum == 20) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It has a thunderbolt pattern.";
	 }
	 if ($itemNum == 21) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It is as black as the night sky.";
	 }
	 if ($itemNum == 22) {
		 return "A peculiar stone that makes certain species of Pokémon evolve. It has a leaf pattern.";
	 }
	 if ($itemNum == 23) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It is as dark as dark can be.";
	 }
	 if ($itemNum == 24) {
	 	return "A cloth imbued with horrifyingly strong spiritual energy. It is loved by a certain Pokémon.";
	 }
	 if ($itemNum == 25) {
	 	return "An item to be held by a Pokémon. It is a special metallic film that ups the power of Steel-type moves.";
	 }
	 if ($itemNum == 26) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It sparkles like eyes.";
	 }
	  if ($itemNum == 27) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It is as red as the sun.";
	 }
	 if ($itemNum == 28) {
	 	return "A spray-type medicine. It awakens a Pokémon from the clutches of sleep.";
	 }
	 if ($itemNum == 13) {
	 	return "If held, prevents a Pokémon from evolving.";
	 }
	 if ($itemNum == 14) {
	 	return "If held, prevents a Pokémon gaining experience.";
	 }
	 if ($itemNum == 15) {
	 	return "If held, increases the chance of an egg from this Pokémon being Shiny.";
	 }
	 if ($itemNum == 16) {
	 	return "If held, increases the chance of an egg from this Pokémon being Shadow.";
	 }
	 if ($itemNum == 32) {
	 	return "An item to be held by a Pokémon. When the holder inflicts damage, the target may flinch.";
	 }
	 if ($itemNum == 34) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It shines with a dazzling light.";
	 }
	 if ($itemNum == 34) {
	 	return "A peculiar stone that makes certain species of Pokémon evolve. It shines with a dazzling light.";
	 }
	 if ($itemNum == 54) {
	 	return "An item to be held by a Pokémon. This exotic-smelling incense boots the power of Water-type moves.";
	 }
	 if ($itemNum == 55) {
	 	return "An item to be held by a Pokémon. This incense has a curious aroma that boosts the power of Water-type moves.";
	 }
	  if ($itemNum == 56) {
	 	return "An item to be held by a Pokémon. The beguiling aroma of this incense may cause attacks to miss its holder.";
	 }
	  if ($itemNum == 57) {
	 	return "An item to be held by a Pokémon. This exotic-smelling incense makes the holder bloated and slow moving.";
	 }
	  if ($itemNum == 58) {
	 	return "An item to be held by a Pokémon. It doubles any prize money received if the holding Pokémon joins a battle.";
	 }
	  if ($itemNum == 59) {
	 	return "An item to be held by a Pokémon. This exotic-smelling incense boosts the power of Psychic-type moves.";
	 }
	  if ($itemNum == 60) {
	 	return "An item to be held by a Pokémon. This exotic-smelling incense boosts the power of Rock-type moves.";
	 }
	  if ($itemNum == 61) {
	 	return "An item to be held by a Pokémon. This exotic-smelling incense boosts the power of Grass-type moves.";
	 }
	  if ($itemNum == 62) {
	 	return "A transparent device somehow filled with all sorts of data. It was produced by Silph Co.";
	 }
	  if ($itemNum == 63) {
	 	return "A very tough and inflexible scale. Dragon-type Pokémon may be holding this item when caught.";
	 }
	  if ($itemNum == 64) {
	 	return "A transparent device overflowing with dubious data. Its producer is unknown.";
	 }
	  if ($itemNum == 65) {
	 	return "A box packed with a tremendous amount of electric energy. It's loved by a certain Pokémon.";
	 }
	  if ($itemNum == 66) {
	 	return "A box packed with a tremendous amount of magma energy. It's loved by a certain Pokémon.";
	 }
	  if ($itemNum == 67) {
	 	return "A protective item of some sort. It is extremely stiff and heavy. It's loved by a certain Pokémon.";
	 }
	  if ($itemNum == 68) {
	 	return "A peculiar stone that can make certain species of Pokémon evolve. it's as round as a Pokémon Egg.";
	 }
	  if ($itemNum == 69) {
	 	return "An item to be held by a Pokémon. This sharply hooked claw increases the holder's critical-hit ratio.";
	 }
	  if ($itemNum == 70) {
	 	return "An item to be held by a Pokémon. When the holder successfully inflicts damage, the target may also flinch.";
	 }
	  if ($itemNum == 77) {
	 	return "An item to be held by Clamperl. This fang gleams a sharp silver and raises the holder's Sp. Atk stat.";
	 }
	  if ($itemNum == 78) {
	 	return "An item to be held by Clamperl. This scale shines with a faint pink and raises the holder's Sp. Def stat.";
	 }
	  if ($itemNum == 79) {
	 	return "An item to be held by a Pokémon. It helps keep wild Pokémon away if the holder is the head of the party.";
	 }
 	return "None";
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Avatar_Name($myGender, $myAvatar) {
 	$myAvatarName = "";
	$myGenderName = get_Gender($myGender);
	if ($myGenderName == "male") {
		$myAvatarName = "b_";
	}else{
		$myAvatarName = "g_";
	}
	$myAvatarName = $myAvatarName.$myAvatar."_2";
	return $myAvatarName;
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Gender($myGender) {
 	if ($myGender == 1) {
		$myGenderName = "male";
	}else if ($myGender == 2) {
		$myGenderName = "female";
	}else {
		$myGenderName = "none";
	}
	return $myGenderName;
 }
  /////////////////////////////////////////////////////////////////////////////////////////////////
 function get_Version_Name($myVersion) {
	 $versionName = "";
	if ($myVersion == 1) {
		$versionName = "gold";
	}else if ($myVersion == 2) {
		$versionName = "silver";
	}
	return $versionName;
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
?>