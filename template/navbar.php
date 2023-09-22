<div id="nav">
	<div id="suckerfish">
		<ul class="menu">
			<li><a href="http://samdangames.blogspot.com/">Blog</a></li>
            <?php if($pageMenuset == "extended") { ?>
			<li><a href="checkPokemon.php?live=true&<?php echo $urlValidation?>">Home</a></li>
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
			<li><a href="adoption.php?<?php echo $urlValidation?>">Pokemon Adoption</a></li>
			<li><a href="avatarStore.php?<?php echo $urlValidation?>">Avatar Store</a></li>
			<li><a href="dailyCode.php?<?php echo $urlValidation?>">Daily Gift</a></li>
			<li class="expanded"><a href="inventory.php?<?php echo $urlValidation?>">Inventory</a>
				<ul class="menu">
					<li><a href="inventory_items.php?<?php echo $urlValidation?>">Items</a></li>
					<li><a href="inventory_avatar.php?<?php echo $urlValidation?>">Avatars</a></li>
				</ul>
			</li>
			<li><a href="gameCorner_test.php?<?php echo $urlValidation?>">Game Corner</a></li>
			<li class="expanded"><a href="#">Trading Center</a>
				<ul class="menu">
					<li><a href="createTrade.php?<?php echo $urlValidation?>">Create Trade</a></li>
					<li><a href="yourTrades.php?<?php echo $urlValidation?>">Your Trade Request</a></li>
					<li><a href="searchTrades.php?<?php echo $urlValidation?>">Search Trades</a></li>
                    <li><a href="latestTrades.php?<?php echo $urlValidation?>">Latest Trades</a></li>
				</ul>
			</li>
            <li class="expanded"><a href="#">Utilities</a>
				<ul class="menu">
                	<li><a href="transferTo2.php?<?php echo $urlValidation?>">Transfer to PTD 2</a></li>
                	<li><a href="removeHack.php?<?php echo $urlValidation?>">Remove Hacked Tag</a></li>
					<li><a href="elite4fix.php?<?php echo $urlValidation?>">Elite 4 Black Screen Fix</a></li>
				</ul>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>