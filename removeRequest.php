<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Remove Request";
	$pageMenuset = "extended";
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
$reason = "go";
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
					<div class="title"><p>Remove Request - <a href="yourTrades.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                <?php }else { ?>
                <?php
$requestID = $_REQUEST['requestID'];
$offerID = $_REQUEST['offerID'];
$db_New = connect_To_Database_New();
$query = "SELECT trade_request.requestPokeID FROM trade_request, trainer_trades WHERE trainer_trades.currentTrainer = ? AND trade_request.requestID = ? AND trainer_trades.uniqueID = trade_request.requestPokeID";
$result = $db_New->prepare($query);
$result->bind_param("ii", $id, $requestID);
$result->execute();
$result->store_result();
$hmp = $result->affected_rows;
	if ($hmp == 0) {
		?>
        <div class="content">
						<p>You cannot remove this offer since you do not own the pokemon that you are removing.</p>
					</div>
        <?php 
	}else{
	$result->close();
	if (empty($offerID)) {
		?>
        <div class="content">
						<p>Error, press the back link to fix this error.</p>
					</div>
        <?php 
	}else{
$query = "DELETE FROM trade_request WHERE offerID = ?";
$result = $db_New->prepare($query);
$result->bind_param("s", $offerID);
$result->execute();
	?>
					<div class="content">
						<p>Your offer has been removed.</p>
					</div>
                     <?php 
	}
					 }
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