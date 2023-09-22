<td id="sidebar">
	<?php if($loggedIn) { ?>
		 <?php if(isset($whichProfile)) { ?>
			<div class="block">
            <?php //echo $cookieOutcome; ?>
				<div class="title"><p>Current Profile:</p></div>
				<div class="content">
					<div class="profile_top">
						<div class="avatar">
							<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo ${"avatar".$whichProfile} ?>.png" alt="" style="height:auto;width:auto;" />
						</div>
						<div class="name">
							<?php echo ${"nickname".$whichProfile} ?>
						</div>
					</div>
					<div class="profile_middle">
						<span class="info_text">Badges:</span> <?php echo $badges ?><br />
						<span class="info_text">Money:</span> <?php echo $money ?><br />
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="block profile">
				<div class="title"><p>Current Account:</p></div>
				<div class="content">
					<div class="profile_top">
						<div class="avatar">
							<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo ${"avatar".$whichAvatar} ?>.png" alt="" />
						</div>
						<div class="name">
							<?php echo $accNickname ?>
						</div>
					</div>
					<div class="profile_middle">
						- <a href="changeNickname.php">Change Account Nickname</a><br />
						- <a href="changeAvatar.php">Change Account Avatar</a><br />
						- <a href="changePassword.php">Change Account Password</a>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="block">
			<div class="title"><p>Log in</p></div>
			<div class="content">
				<form action="trading_account.php" method="post">
					EMAIL: <br />
					<input class="text" name="Email" type="text"/><br />
					<br />
					PASSWORD:<br />
				  <input name="Pass" type="password" class="text" maxlength="10"/><br />
					<br />
					<div class="login_actions">
						<a href="http://www.sndgames.com/games/ptd/password1.php">Lost Password?</a>
						<input value="Login" type="submit" class="login_btn"/>
					</div>
				</form>
			</div>
		</div>
	<?php } ?>
</td>