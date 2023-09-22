<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Funding Rewards - Cosmoids Early Alpha";
	$pageMenuset = "extended";
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
				if ($reason != "savedOutside") {
					$myAction = $_REQUEST['action'];
					$myCode = $_REQUEST['myCode'];
					$db = connect_To_Cosmoids();
					$haveCosmoidsAlphaAccess = false;
					$codeIDList = get_code_ID($db, $id);
					$fundingAmount = 0;
					$fundingUserList = array();
					//
					if ($codeIDList[0] != -1) {
						///
						for ($i=0; $i<count($codeIDList); $i++) {
							$fundingUserID = get_UserID_From_CodeID($db, $codeIDList[$i]);
							////
							$continue = false;
							if ($fundingUserID != -1) {
								for ($b=0; $b<count($fundingUserList); $b++) {
									if ($fundingUserID == $fundingUserList[$b]) {
										$continue = true;
										break;
									}
								}
								if ($continue == true) {
									continue;
								}
								array_push($fundingUserList, $fundingUserID);
								$haveCosmoidsAlphaAccess = true;
								$fundingAmount += get_Funding_Amount($db, $fundingUserID);
							}
							////
						}
						///
					}
					//
					$db->close();
				}
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Funding Rewards- Cosmoids Early Alpha - <a href="Rewards2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
				  <div class="content">
                    <?php
					//
					 if ($reason == "savedOutside") { ?>
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    <?php }else {
						///
						if ($haveCosmoidsAlphaAccess == false) {
							echo '<p>You do not have Access to the Cosmoids Early Alpha Version. Go back.</p>';
						}else{
							echo '<p>Cosmoids Early Alpha Version</p>';
							?>
                    A lot of things are missing still, no UI has been implemented no cutscenes or win and lose conditions. Note that the graphics are not finished at all. What I want you guys to see is that there won't be any spots to put your creatures in. You can place them anywhere. The other thing is the ranges, attacks will now have different ranges. Another big thing is that your character will also be a tower. In this version you have all 3 starters available with their first attacks. More updates and information will be out in the future. Stay tuned! If you have any feedback please send it to support@sndgames.com with the Subject "Cosmoids Feedback".
                    <?php
							echo '<p><embed height="480" pluginspage=" http://www.macromedia.com/go/getflashplayer" src="'. get_Graphic_Url().'/games/cosmoids/cosmoids_game_web.swf" type="application/x-shockwave-flash" width="800"></embed></p>';
						}
						///
                          ?>
                           
							
			        <p>&nbsp;</p>
					 <?php
					  }
					 // ?>
				    </p>
				  </div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
	//////////////////////////////////////////////////////////////////////////////////////////////////
	function used_Reward($db, $userID, $rewardID) {
		$query = "SELECT rewardID FROM rewardsRecords WHERE userID = ? AND rewardID = ?";
		$result = $db->prepare($query);
		$result->bind_param("ii", $userID, $rewardID);
		$result->execute();
		$result->store_result();
		$result->bind_result($temp);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return 0;
		}
		$result->close();
		return 1;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
	function get_Funding_Amount($db, $userID) {
		$query = "SELECT amountFunded FROM fundingAmount WHERE userID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $userID);
		$result->execute();
		$result->store_result();
		$result->bind_result($fundingAmount);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return 0;
		}
		$result->fetch();
		$result->close();
		return $fundingAmount;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
	function get_code_ID($db, $id) {
		$codeIDList = array();
		$query = "SELECT codeID FROM ptd2Emails WHERE ptd2ID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $id);
		$result->execute();
		$result->store_result();
		$result->bind_result($codeID);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			array_push($codeIDList, -1);
			return $codeIDList;
		}
		for ($i=0; $i<$hmp; $i++) {
			$result->fetch();
			array_push($codeIDList, $codeID);
		}
		$result->close();
		return $codeIDList;
	 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
 function get_UserID_From_CodeID($db, $codeID) {
	 $query = "SELECT userID FROM accessCode WHERE codeID = ?";
		$result = $db->prepare($query);
		$result->bind_param("i", $codeID);
		$result->execute();
		$result->store_result();
		$result->bind_result($userID);	
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			$result->close();
			return -1;
		}
		$result->fetch();
		$result->close();
		return $userID;
 }
 //////////////////////////////////////////////////////////////////////////////////////////////////
?>
</body>
</html>