<div id="nav">
	<div id="suckerfish">
		<ul class="menu">
			<li><a href="http://samdangames.blogspot.com/">Blog</a></li>
            <?php if($pageMenuset == "extended") { ?>
			<li><a href="checkPokemon2.php?live=true&<?php echo $urlValidation?>">Home</a></li>
			<?php } ?>
			<?php if($pageMenuset == "account" || $pageMenuset == "extended") { ?>
			<li class="expanded"><a href="#">Account</a>
				<ul class="menu">
					<li><a href="changeNickname.php">Change Nickname</a></li>
					<li><a href="changeAvatar.php">Change Avatar</a></li>
					<li><a href="changePassword.php">Change Password</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if($pageMenuset == "extended") { ?>
			<li><a href="adoption2.php?<?php echo $urlValidation?>">Pokemon Adoption</a></li>
            <li><a href="dailyActivity2.php?<?php echo $urlValidation?>">Daily Activity</a></li>
            <li><a href="breeding2.php?<?php echo $urlValidation?>">Breeding Center</a></li>
            <li class="expanded"><a href="#">Convert</a>
				<ul class="menu">
					<li><a href="deevolve2.php?<?php echo $urlValidation?>">Deevolution Chamber</a></li>
					<li><a href="createShiny2.php?<?php echo $urlValidation?>">Convert To Shiny</a></li>
					<li><a href="createShadow2.php?<?php echo $urlValidation?>">Convert To Shadow</a></li>
                    <li><a href="removeHack2.php?<?php echo $urlValidation?>">Remove Hacked Tag</a></li>
				</ul>
			</li>
            <li><a href="itemStore2.php?<?php echo $urlValidation?>">Item Store</a></li>
            <?php //<!--<li><a href="Rewards2.php? <?php echo $urlValidation">Rewards</a></li>-->?>
			<li class="expanded"><a href="#">Trading Center</a>
				<ul class="menu">
					<li><a href="createTrade2.php?<?php echo $urlValidation?>">Create Trade</a></li>
					<li><a href="yourTrades2.php?<?php echo $urlValidation?>">Your Trade Request</a></li>
					<li><a href="searchTrades2.php?<?php echo $urlValidation?>">Search Trades</a></li>
                    <li><a href="latestTrades2.php?<?php echo $urlValidation?>">Latest Trades</a></li>
				</ul>
			</li>
            <li><a href="hof2.php?<?php echo $urlValidation?>">Hall Of Fame</a></li>
			<?php } ?>
		</ul>
	</div>
</div>