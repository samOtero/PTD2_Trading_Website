<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Daily Gift - Prize List";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	 $urlValidation = "whichProfile=".$whichProfile;
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
				<div class="block">
					<div class="title thin"><p>Daily Code - Prize List - <a href="dailyCode.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                <?php if ($reason == "savedOutside") { ?>
                <div class="block">
					<div class="content">
                    <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
                    </div>
				</div>
                <?php }else { ?>
				<div class="block">
					<div class="title"><p>Common Daily Code Prizes:</p></div>
					<div class="content">
						<ul>
							<li>Requirements - None</li>
  <li>Price  - 1,000</li>
   <li>Casino Coin (1,000) - 50%</li>
   <li>Casino Coin (10,000) - 49.934%</li>
  <li>1 Snd Coin - 0.05%</li>
  <li>5 Snd Coins - 0.01%</li>
  <li>10 Snd Coins - 0.005%</li>
  <li>20 Snd Coins - 0.001%</li>
						</ul>
					</div>
				</div>
				<div class="block">
					<div class="title"><p>Uncommon Daily Code Prizes:</p></div>
					<div class="content">
						<ul>
							<li>Requirements - 3 Badges</li>
  <li>Price  - 10,000</li>
  <li>Casino Coin (10,000) - 50%</li>
   <li>Casino Coin (25,000) - 49.844%</li>
   <li>1 Snd Coin - 0.1%</li>
   <li>5 Snd Coins - 0.05%</li>
   <li>10 Snd Coins - 0.01%</li>
   <li>20 Snd Coins - 0.005%</li>
						</ul>
					</div>
				</div>
				<div class="block">
					<div class="title"><p>Rare Daily Code Prizes:</p></div>
					<div class="content">
						<ul>
							<li>Requirements - Beat Latest Level</li>
  <li>Price  - 100,000</li>
  <li>Casino Coin (50,000) - 50%</li>
   <li>Casino Coin (100,000) - 49.34%</li>
   <li>1 Snd Coin - 0.5%</li>
   <li>5 Snd Coins - 0.1%</li>
   <li>10 Snd Coins - 0.05%</li>
   <li>20 Snd Coins - 0.01%</li>
						</ul>
					</div>
				</div>
                 <?php } ?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>