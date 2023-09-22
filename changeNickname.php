<?php
	session_start();
	include 'database_connections.php';
	$loggedIn = true;
	$pageMenuset = "account";
	$showTopAd = "no";
	$showSideAd = "no";
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
					if ($action == "newName") {
						$newName = $_REQUEST['newName'];
						if (empty($newName)) {
							$reason = "emptyField";
						}else{
							$newName = strip_tags($newName);
							//$query = "select trainerID FROM poke_accounts WHERE accNickname = ?";
							//$result = $db->prepare($query);
							//$result->bind_param("s", $newName);
							///$result->execute();
							//$result->store_result();
							//$result->bind_result($myTrainerID);			
							//if ($result->affected_rows) {
								//$result->fetch();
								//$reason = "nameTaken";
								//$result->close();
							//}else{
								//$result->close();
								$accNickname = $newName;
								$query = "UPDATE poke_accounts SET accNickname = ? WHERE trainerID = ?";
								$result = $db->prepare($query);
								$result->bind_param("si", $newName, $id);
								$result->execute();
								if ($result->sqlstate=="00000") {
									$result->close();
									$reason = "success";
								}else{
									$result->close();
									$reason = "error";
								}
							//}
						}
					}
				}
			?>
			</td>
			<td id="main">
				<div class="block">
					<div class="title"><p>Change Nickname - <a href="trading_account.php?Action=logged">Go Back</a></p></div>
					<div class="content">
                    	<?php 
						if ($reason == "error") { ?>
                        <p>Could not change your account name due to a Database issue, please try again later.</p>
                        <?php }else if ($reason == "success") { ?>
                        <p>Success! Your account name has been changed.</p>
                         <?php }else if ($reason == "nameTaken") { ?>
                         <p>That name is taken. Please enter a different one and try again.</p>
                          <?php }else if ($reason == "emptyField") { ?>
                          <p>You cannot leave the field empty.</p>
                           <?php }else if ($reason == "savedOutside") { ?>
                           <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                            <?php }else { ?>
						<form action="changeNickname.php?Action=newName" method="post" name="form1" id="form1">
							<p>Enter your new account name, trainers will see this name when they are trading with you:</p>
							<p><span class="formLabel">New Account Name:</span><input type="text" maxlength="19" id="newName" name="newName"></p>
							<p><input type="submit" value="Submit" id="submit" name="submit"></p>
						</form>
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