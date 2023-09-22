<td id="sidebar">
<?php
	 if($loggedIn) { 
		 	 if(isset($whichProfile)) {
				 if (isset($profileInfo)) {
					$myAvatarName = $profileInfo[4];
					$badges = $profileInfo[2];
					$money = $profileInfo[3];
					$myNickname = $profileInfo[1];
					$myVersionName = $profileInfo[0];
				 }
?>
         	
			<div class="block <?php echo $myVersionName ?>">
            	<?php //echo $cookieOutcome; ?>
				<div class="title"><p>Current Profile:</p></div>
				<div class="content">
					<div class="profile_top">
						<div class="avatar">
							<img src="<?php echo get_Graphic_Url() ?>/trading_center/avatar/<?php echo $myAvatarName ?>.png" alt="" style="height:auto;width:auto;" />
						</div>
						<div class="name">
							<?php echo $myNickname ?>
						</div>
					</div>
					<div class="profile_middle">
						<span class="info_text">Badges:</span> <?php echo $badges ?><br />
						<span class="info_text">Money:</span> <?php echo $money ?><br />
                        <span class="info_text">Trainer ID:</span> <?php echo $id ?><br />
					</div>
				</div>
			</div>
<?php
			 } 
		 } 
</td>