<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Trade Setup";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	$pokeID = $_REQUEST['pokeID'];
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
					<div class="title thin"><p>Trade Setup - <a href="createTrade2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
				</div>
                <?php if ($reason == "savedOutside") { ?>
                	<div class="block">
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
$dbActual = get_PTD2_Pokemon_Database($whichDB);
	$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, item, originalOwner, myTag, gender, happy FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $item, $originalOwner, $myTag, $pokeGender, $pokeHoF);
	if ($result->affected_rows == 0) {
		$result->free_result();
		$result->close();
		$dbActual->close();
		?>
        <div class="block">
			<div class="content">
				 <p>You cannot trade this pokemon. <a href="trading.php">Click here to go back.</a></p>
			</div>
        </div>
        <?php
	}else{
		$result->fetch();
		$result->free_result();
		$result->close();
		$dbActual->close();
	$pokeNickname = strip_tags($pokeNickname);
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		pokeBox2($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;", $pokeGender, $item, $pokeHoF);				
				?>
				<div class="block">
					<div class="content">
						<p>You are about to list this pokemon up for trade. In the forms below choose what you want in return for your trade. If another trainer fufills one of the request then the trade will be accepted automatically.</p>
                        <p>You can now create multiple request! Think of it like saying I want a Shiny Pikachu OR a Shiny Zorua instead of saying I want a Shiny Pikachu AND a Shiny Zorua.</p>
                        <p> Each request can contain up to 10 different pokemon that you want. Keep in mind to be reasonable with what you are asking for.</p>
						<p>If you don't want to specify who you want and want to let the trainers offer you different pokemon then leave all the fields empty and press submit.</p>
                        <p>If you don't log on the Pokemon Center for 10 days there is a possibility that your trades will be taken down and your pokemon will return to your pick up area.</p>
					</div>
				</div>
                <div class="block">
					<div class="content" id="newRequest">
						<p> You have (3) request left. <a href="#newRequest" onClick="createNewRequest2('1');return false;">Create New Request</a></p>
					</div>
				</div>
				<form action="tradeMe2.php?<?php echo $urlValidation ?>&Action=setup&pokeID=<?php echo $pokeID ?>" method="post" name="form1" id="form1">
				<div class="block">
					<div class="content">
                    <p>If you wish other trainers to be able to adopt your pokemon with SnD Coins that you will receive change the amount below:</p>
                    <p>
                      <label for="adoptNowPrice">Adopt Now Price:</label>
                      <select name="adoptNowPrice" id="adoptNowPrice">
                      <option value="0" selected="selected">None</option>
                      <?php
					  for ($i=1; $i<=20; $i++) {
					  	echo '<option value="'.$i.'">'.$i.'</option>';
					  }
					  ?>
                      </select>
                    </p>
                    </div>
				</div>
                 <div class="block">
					<div class="content">
                    <div id="requestArea">
                   	  <p> You have not made any request.</p>
                    </div>
                    <div id="requestArea1"></div>
                    <div id="requestArea2"></div>
                    <div id="requestArea3"></div>
						<p><input type="submit" value="Submit" id="submit" name="submit"></p>
					</div>
                    </div>
				</form>
				</div>
                <?php }
				}?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>