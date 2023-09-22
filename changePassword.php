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
if ($action == "newPass") {
	$gameEmail = $_REQUEST['gameEmail'];
	$oldPass = $_REQUEST['oldPass'];
	$newPass1 = $_REQUEST['newPass1'];
	$newPass2 = $_REQUEST['newPass2'];
	if (empty($gameEmail) || empty($oldPass) || empty($newPass1) || empty($newPass2)) {
		$reason = "empty";
		//echo 'You cannot leave any field empty. Try again.';
	}else if ($newPass1 != $newPass2) {
		$reason = "notMatching";
		//echo '';
	}else if ($newPass1 == $oldPass) {
		$reason = "same";
		//echo '';
	}else{
		$newPass = strip_tags($newPass);
		$query = "select trainerID FROM poke_accounts WHERE email = ? AND pass = ?";
		$result = $db->prepare($query);
		$result->bind_param("ss", $gameEmail, $oldPass);
		$result->execute();
		$result->store_result();
		$result->bind_result($myTrainerID);			
		if ($result->affected_rows) {
			$result->fetch();
			$result->close();
			$query = "UPDATE poke_accounts SET pass = ? WHERE trainerID = ?";
			$result = $db->prepare($query);
			$result->bind_param("si", $newPass1, $myTrainerID);
			$result->execute();
			if ($result->sqlstate=="00000") {
				$result->close();
				$reason = "success";
			}else{
				$result->close();
				$reason = "error";
			}
		}else{
			$reason = "noMatch";
			$result->close();
		}
	}
}
				}
			?>
			</td>
			<td id="main">
				<div class="block">
					<div class="title"><p>Change Nickname - <a href="trading_account.php?Action=logged">Go Back</a></p></div>
					<div class="content">
                     <?php if ($reason == "error") { ?>
                     <p>Error. Could not change your password due to a Database issue. <a href="changePassword.php">Try again</a>.</p>
                     <?php } else if ($reason == "success") { ?>
                     <p>Success! Your password has been successfully changed.</p>
                    	<?php } else if ($reason == "noMatch") { ?>
                     <p>Error. Your email and password do not match any account on the server.<a href="changePassword.php">Try again</a>.</p>
                     <?php } else if ($reason == "same") { ?>
                     <p>Error. Your new password cannot be the same as your old password. <a href="changePassword.php">Try again</a>.</p>
                    	<?php } else if ($reason == "notMatching") { ?>
                     <p>Error. Your "New Password" and "New Password Again" do not match. <a href="changePassword.php">Try again</a>.</p>
                     <?php } else if ($reason == "empty") { ?>
                     <p>Error. You cannot leave any field empty. <a href="changePassword.php">Try again</a>.</p>
                    	<?php } else if ($reason == "savedOutside") { ?>
                        	<p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                      <?php } else { ?>
						<form action="changePassword.php?Action=newPass" method="post" name="form1" id="form1">
							<p>Enter your game account email and old password then enter your new password twice to change your password.</p>
							<p><span class="formLabel">Game account email:</span><input type="text" maxlength="50" id="gameEmail" name="gameEmail"></p>
							<p><span class="formLabel">Current Password:</span><input type="text" maxlength="50" id="oldPass" name="oldPass"></p>
							<p><span class="formLabel">New Password:</span><input type="text" maxlength="50" id="newPass1" name="newPass1"></p>
							<p><span class="formLabel">New Password Again:</span><input type="text" maxlength="50" id="newPass2" name="newPass2"></p>
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