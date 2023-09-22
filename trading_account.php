<?php
	session_start();
	include 'database_connections.php';
	$loggedIn = false;
	$pageMenuset = "account";
	$pageTitle = "Your Account";
	$showTopAd = "yes";
	$showSideAd = "yes";
	$action = $_REQUEST['Action'];
	$email = $_REQUEST['Email'];
	$pass = $_REQUEST['Pass'];
	$db = connect_To_Database();
	setcookie("ver", 0, $expireTime);
	include 'template/head.php';
	include 'ptd2_basic.php';
?>
<body>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($action == "logged") {
		$myTrainerID = $_SESSION['myID'];
		$currentSave = $_SESSION['currentSave'];
		$currentSave2 = $_SESSION['currentSave2'];
		$currentSave3 = $_SESSION['currentSave3'];
		$query = "select trainerID, currentSave, currentSave2, Nickname_1, Nickname_2, Nickname_3, Badge_1, Badge_2, Badge_3, Money_1, Money_2, Money_3, avatar1, avatar2, avatar3, whichAvatar, accNickname from poke_accounts WHERE trainerID = ? AND currentSave = ?";
		$result = $db->prepare($query);
		$result->bind_param("is", $myTrainerID, $currentSave);
		$result->execute();
		$result->store_result();
		$result->bind_result($myTrainerID, $currentSave, $currentSave2, $nickname1, $nickname2, $nickname3, $badge1, $badge2, $badge3, $money1, $money2, $money3, $avatar1, $avatar2, $avatar3, $whichAvatar, $accNickname);			
		if ($result->affected_rows) {
			$result->fetch();
			$result->close();
			$loggedIn = true;
		}else{
			$result->close();
			$pageMenuset = "";
			$showAdTop = "no";
			$showSideAd = "no";
			$reason = "savedOutside";
		}
	}else{
		$query = "select trainerID, currentSave, currentSave2, Nickname_1, Nickname_2, Nickname_3, Badge_1, Badge_2, Badge_3, Money_1, Money_2, Money_3, avatar1, avatar2, avatar3, whichAvatar, accNickname from poke_accounts WHERE email = ? AND pass = ?";
		$result = $db->prepare($query);
		$result->bind_param("ss", $email, $pass);
		$result->execute();
		$result->store_result();
		$result->bind_result($myTrainerID, $currentSave, $currentSave2, $nickname1, $nickname2, $nickname3, $badge1, $badge2, $badge3, $money1, $money2, $money3, $avatar1, $avatar2, $avatar3, $whichAvatar, $accNickname);			
		if ($result->affected_rows) {
			$result->fetch();
			$result->close();
			$loggedIn = true;
		}else{
		$result->close();
			$pageMenuset = "";
			$showAdTop = "no";
			$showSideAd = "no";
			$reason = "notFound";
		}
}
if ($reason != "savedOutside" && $reason != "notFound") {
	$newCurrentSave = uniqid(true);
	$newCurrentSave2 = uniqid(true);
	$newCurrentSave3 = uniqid(true);
	$query = "UPDATE poke_accounts SET currentSave = ?, currentSave2 = ? WHERE trainerID = ?";
	$result = $db->prepare($query);
	$result->bind_param("ssi", $newCurrentSave, $newCurrentSave2, $myTrainerID);
	$result->execute();
	if ($result->sqlstate=="00000") {
		$currentSave = $newCurrentSave;
		$currentSave2 = $newCurrentSave2;
		$currentSave3 = $newCurrentSave3;
		$result->close();
		$_SESSION['myID'] = $myTrainerID;
		$_SESSION['currentSave'] = $currentSave;
		$_SESSION['currentSave2'] = $currentSave2;
		$_SESSION['currentSave3'] = $currentSave3;
		$query = "UPDATE sndgame_ptd3_basic.currentSave SET currentSave = ? WHERE trainerID = ?";
		$result = $db->prepare($query);
		$result->bind_param("si", $currentSave3, $myTrainerID);
		$result->execute();
		$result->close();
 		$db_New = connect_To_Database_New();
 		$newTime = date( 'Y-m-d');
		$query = "UPDATE trainer_trades SET lastTimeUsed = ? WHERE currentTrainer = ? AND pickup = 0";
		$result = $db_New->prepare($query);
		$result->bind_param("si", $newTime, $myTrainerID);
		$result->execute();
		$result->close();	
		$db_New->close();
		$db_New = connect_To_ptd2_Trading();
		$query = "UPDATE trainer_trades SET lastTimeUsed = ? WHERE currentTrainer = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("si", $newTime, $myTrainerID);
		$result->execute();
		$result->close();	
		$db_New->close();
		$urlValidation = "live=true";
	}else{
		$result->close();
		$pageMenuset = "";
		$showAdTop = "no";
		$showSideAd = "no";
		$reason = "savedOutside";
	}
}
	include 'template/header.php';
?>
<div id="content">
	<?php
		include 'template/navbar.php';
	?>
	<table id="content_table">
		<tr>
			<?php
				include 'template/sidebar.php';
			?>
			<td id="main">
            	<?php if ($reason == "savedOutside") { ?>
               		 <div class="block">
						<div class="title"><p>Error:</p></div>
						<div class="content">
							<p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
						</div>
					</div>
                <?php } else if ($reason == "notFound") { ?>
                		<div class="block">
						<div class="title"><p>Error:</p></div>
						<div class="content">
							<p>Could not find an account with your email and password. <a href="http://www.sndgames.com/games/ptd/password.php">Go here if you lost your password</a>.</p>
						</div>
					</div>
                <?php } else {
				
				?>
				<div class="block">
					<div class="title"><p>Select Profile</p></div>
					<div class="content">
						<p>Pick which profile you wish to trade with:</p>
					</div>
				</div>
				<div class="profile">
					<h2>PTD1 Profiles:</h2>
					<a href="checkPokemon.php?<?php echo $urlValidation ?>&whichProfile=1">
						<div class="block">
							<div class="title"><p>Current Profile:</p></div>
							<div class="content">
								<div class="profile_top">
									<div class="avatar">
										<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar1 ?>.png" alt="" />
									</div>
									<div class="name">
										<?php echo $nickname1 ?>
									</div>
								</div>
								<div class="profile_middle">
									<span class="info_text">Badges:</span> <?php echo $badge1 ?><br />
									<span class="info_text">Money:</span> <?php echo $money1 ?><br />
								</div>
							</div>
						</div>
					</a>
					<a href="checkPokemon.php?<?php echo $urlValidation ?>&whichProfile=2">
						<div class="block">
							<div class="title"><p>Current Profile:</p></div>
							<div class="content">
								<div class="profile_top">
									<div class="avatar">
										<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar2 ?>.png" alt="" />
									</div>
									<div class="name">
										<?php echo $nickname2 ?>
									</div>
								</div>
								<div class="profile_middle">
									<span class="info_text">Badges:</span> <?php echo $badge2 ?><br />
									<span class="info_text">Money:</span> <?php echo $money2 ?><br />
								</div>
							</div>
						</div>
					</a>
					<a href="checkPokemon.php?<?php echo $urlValidation ?>&whichProfile=3">
						<div class="block">
							<div class="title"><p>Current Profile:</p></div>
							<div class="content">
								<div class="profile_top">
									<div class="avatar">
										<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar3 ?>.png" alt="" />
									</div>
									<div class="name">
										<?php echo $nickname3 ?>
									</div>
								</div>
								<div class="profile_middle">
									<span class="info_text">Badges:</span> <?php echo $badge3 ?><br />
									<span class="info_text">Money:</span> <?php echo $money3 ?><br />
								</div>
							</div>
						</div>
					</a>
                    <br>
                    <?php 
						$dbPTD2 = connect_To_ptd2_Story_Database();
						$query = "select Version, Nickname, Badge, gender, whichProfile, Money, avatar, whichDB from poke_accounts WHERE trainerID = ? ORDER BY whichProfile ASC";
						$result = $dbPTD2->prepare($query);
						$result->bind_param("i", $myTrainerID);
						$result->execute();
						$result->store_result();
						$result->bind_result($myVersion2, $myNickname2, $myBadge2, $myGender2, $myProfile2, $myMoney2, $myAvatar2, $myPokeDB);
						$totalProfiles = $result->affected_rows;
						echo '<h2>PTD2 Profiles:</h2>';
						for ($i=0; $i<$totalProfiles; $i++) {
							$result->fetch(); 
							$dbPTD2_2 = get_PTD2_Pokemon_Database($myPokeDB);
							$query2 = "select num from trainer_pokemons WHERE trainerID = ? AND whichProfile = ? LIMIT 2";
							 $result2 = $dbPTD2_2->prepare($query2);
							 $result2->bind_param("ii", $myTrainerID, $myProfile2);
							$result2->execute();
							$result2->store_result();
							$result2->bind_result($pokeNum);
							$totalValues = $result2->affected_rows;
							$result2->free_result();
							if ($totalValues < 1) {
								$result2->close();
								$dbPTD2_2->close();
								continue;
							}
							$myBadge2 = get_Story_Badge($myTrainerID, $myProfile2);
							$versionClass = " ".get_Version_Name($myVersion2);
							$myAvatarName2 = get_Avatar_Name($myGender2, $myAvatar2);
					?>
                    <a href="checkPokemon2.php?<?php echo $urlValidation ?>&whichProfile=<?php echo $myProfile2?>">
						<div class="block<?php echo $versionClass?>">
							<div class="title"><p>Current Profile:</p></div>
							<div class="content">
								<div class="profile_top">
									<div class="avatar">
										<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $myAvatarName2?>.png" alt="" />
									</div>
									<div class="name">
										<?php echo $myNickname2 ?>
									</div>
								</div>
								<div class="profile_middle">
									<span class="info_text">Badges:</span> <?php echo $myBadge2 ?><br />
									<span class="info_text">Money:</span> <?php echo $myMoney2 ?><br />
								</div>
							</div>
						</div>
					</a>
                   <?php
						}
						$result->free_result();
						$result->close();
						$dbPTD2->close();
					?>
                     <br> Note: PTD 2 Profiles will only show up once you have saved your starter pokemon in Story Mode for that profile.<br/>
                <?php
					//only show the profiles for me testing purposes
					//if ($myTrainerID === 1) {
						$dbPTD2 = connect_To_ptd2_Story_Database();
						$query = "select Version, Nickname, whichProfile, Money, whichDB from sndgame_ptd3_basic.trainerProfiles WHERE trainerID = ? ORDER BY whichProfile ASC";
						$result = $dbPTD2->prepare($query);
						$result->bind_param("i", $myTrainerID);
						$result->execute();
						$result->store_result();
						$result->bind_result($myVersion3, $myNickname3, $myProfile3, $myMoney3, $myPokeDB);
						$totalProfiles = $result->affected_rows;
						echo '<h2>PTD3 Profiles:</h2>';
						if ($totalProfiles <= 0) {
							echo 'No profiles found for PTD3 on this account. What are you waiting for? Starting playing!';
						}
						for ($i=0; $i<$totalProfiles; $i++) {
							$result->fetch(); 
							$versionClass = " ".get_Version_Name($myVersion3);
							?>
							<a href="ptd3/main.php?<?php echo $urlValidation ?>&whichProfile=<?php echo $myProfile3?>">
								<div class="block<?php echo $versionClass?>">
									<div class="title"><p>Current Profile:</p></div>
									<div class="content">
										<div class="profile_top">
											<div class="avatar">
											</div>
											<div class="name">
												<?php echo $myNickname3 ?>
											</div>
										</div>
										<div class="profile_middle">
											<span class="info_text">Money:</span> <?php echo $myMoney3 ?><br />
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$result->free_result();
						$result->close();
						$dbPTD2->close();
					//}
			} ?>
            </div>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>