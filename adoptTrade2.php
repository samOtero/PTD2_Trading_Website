<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Adopt Now";
	$pageMenuset = "extended";
	//require 'trade_To_Pickup_By_ID.php';
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
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
//*
if ($reason != "savedOutside") {
	$db = connect_To_Database();
	$tradeID = $_REQUEST['tradeID'];
	$idCheck = $_REQUEST['IDCheck'];
	if ($idCheck != $id) {
		echo 'This page is not available for you. You have been sent here by somebody trying to scam you.';
		exit;
	}
	$query = "select howManyCoins from sndCoins WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("i", $id);
	$result->execute();
	$result->store_result();
	$result->bind_result($howManyCoins);			
	if ($result->affected_rows) {
		$result->fetch();
	}else{
		$howManyCoins = 0;
	}
	$result->free_result();
	$result->close();
	$db_New = connect_To_ptd2_Trading();
	$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4, gender, item, sndCost FROM trainer_trades WHERE uniqueID = ?";
	$result = $db_New->prepare($query);
	$result->bind_param("s", $tradeID);
	$result->execute();
	$result->store_result();
	$hmp = $result->affected_rows;
	$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4, $pokeGender, $pokeItem, $sndCost);
	//**
	if ($hmp == 0) {
		$reason = "notFound";
	}else{
		$result->fetch();
		//***
		if ($sndCost == 0 || $sndCost < 0) {
			$reason = "notFound";
		}else if ($sndCost > $howManyCoins) {
			$reason = "notEnoughCoins";
		}
		//***
	}
	//**
}
//*
			?>
      <td id="main"><div class="block">
          <div class="title">
            <p>Adopt Now - <a href="searchTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p>
          </div>
          <div class="content">
          <p>You have (<?php echo $howManyCoins ?>) Sam and Dan Coins to use in adopting a pokémon. <a href="http://samdangames.blogspot.com/p/get-snd-coins.html">Click here to get more coins.</a></p>
           <?php if ($reason == "savedOutside") { ?>
           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
           <?php }else if ($reason == "notFound") { ?>
           <p>No pokemon up for adoption found for that id number. Press back to continue.</p>
           <?php }else if ($reason == "notEnoughCoins") { ?>
           <p>You don't have enough coins to make this adoption. Press back to continue.</p>
           <?php }else if ($reason == "error") { ?>
           <p>Error in the database. <a href="trading.php">Click here to go back.</a></p>
           <?php } else { 
		    $reason = "success";
		   ?>
            <p>Are you sure you want to adopt the following pokemon:</p>
            <?php } ?>
          </div>
        </div>
        <?php 
		if ( $reason == "success") {
			pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "Cost: $sndCost SnD Coins", $pokeGender, $pokeItem);
		?>
        <div class="block">
          <div class="content">
            <p><a href="adoptTrade3.php?<?php echo $urlValidation.'&tradeID='.$tradeID.'&IDCheck='.$idCheck ?>">Yes, Adopt this Pokemon.</a> If you don't want to adopt this pokemon press back.</p>
          </div>
        </div>
        <?php } ?>
        </td>
    </tr>
  </table>
</div>
<?php
	include 'template/footer.php';
	//////////////////////////////////////////////
?>
</body>
</html>