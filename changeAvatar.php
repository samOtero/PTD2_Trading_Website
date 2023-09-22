<?php
	session_start();
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$loggedIn = true;
	$pageMenuset = "account";
	$pageTitle = "Change Account Nickname";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();

$query = "select  avatar1, avatar2, avatar3, whichAvatar, Nickname_1, Nickname_2, Nickname_3, accNickname from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($avatar1, $avatar2, $avatar3, $whichAvatar, $nickname1, $nickname2, $nickname3, $accNickname);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
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
				if ($reason != "savedOutside") {
					$action = $_REQUEST['Action'];
					if ($action == "newAvatar") {
						$newAvatar = $_REQUEST['whichAvatar'];
						if (empty($newAvatar)) {
							$reason = "empty";
						}else{
							$query = "UPDATE poke_accounts SET whichAvatar = ? WHERE trainerID = ?";
							$result = $db->prepare($query);
							$result->bind_param("ii", $newAvatar, $id);
							$result->execute();
							if ($result->sqlstate=="00000") {
								$result->close();
								$reason = "success";
							}else{
								$result->close();
								$reason = "error";
							}
						}
					}
				}
			?>
			</td>
			<td id="main">
				<div class="block">
					<div class="title"><p>Change Avatar - <a href="trading_account.php?Action=logged">Go Back</a></p></div>
                    <?php if ($reason == "error") { ?>
                    <div class="content">
						<p>Error. Could not change your avatar due to a Database issue, please try again later.</p>
					</div>
                     <?php } else if ($reason == "success") { ?>
                     <div class="content">
						<p>Success! Your account avatar has been changed.</p>
					</div>
                      <?php }else if ($reason == "empty") { ?>
                       <div class="content">
						<p>Error. You didn't pick an avatar.</p>
					</div>
                       <?php } else if ($reason == "savedOutside") { ?>
                       <div class="content">
                        	<p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                         </div>
						 <?php }else { ?>
					<div class="content">
						<p>Pick which of your profile's avatar you wish to use for your account's avatar:</p>
					</div>
				</div>
				<div class="avatars">
					<a href="changeAvatar.php?whichAvatar=1&Action=newAvatar">
						<div class="block avatar">
							<div class="image_holder">
								<div class="image_center">
									<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar1 ?>.png" alt="" class="image" />
								</div>
							</div>
							<span class="name"><?php echo $nickname1 ?>'s</span>
						</div>
					</a>
					<a href="changeAvatar.php?whichAvatar=2&Action=newAvatar">
						<div class="block avatar">
							<div class="image_holder">
								<div class="image_center">
									<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar2 ?>.png" alt="" class="image" />
								</div>
							</div>
							<span class="name"><?php echo $nickname2 ?>'s</span>
						</div>
					</a>
					<a href="changeAvatar.php?whichAvatar=3&Action=newAvatar">
						<div class="block avatar">
							<div class="image_holder">
								<div class="image_center">
									<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $avatar3 ?>.png" alt="" class="image" />
								</div>
							</div>
							<span class="name"><?php echo $nickname3 ?>'s</span>
						</div>
					</a>
                    <?php } ?>
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