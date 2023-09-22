<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Deny Request";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
$reason = "go";
	$urlValidation = "whichProfile=".$whichProfile;
	$requestID = $_REQUEST['requestID'];
	$offerID = $_REQUEST['offerID'];
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
					<div class="title"><p>Deny Request - <a href="yourTrades.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
					do_Stuff();
				}
	 			?>
			</td>
		</tr>
	</table>
</div>
<?php
	function do_Stuff() {
		global $requestID, $id, $urlValidation, $offerID, $whichProfile;
		$db_New = connect_To_Database_New();
		$query = "SELECT trade_request.requestPokeID FROM trade_request, trainer_trades WHERE trainer_trades.currentTrainer = ? AND trade_request.requestID = ? AND trainer_trades.uniqueID = trade_request.tradePokeID";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $id, $requestID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		if ($hmp == 0) {
			echo '<div class="content"><p>You cannot deny this offer since you do not own the pokemon that is being traded or this request no longer exist.</p></div></div>';
			return;
		}
		$result->close();
		if (empty($offerID)) {
			echo '<div class="content"><p>Error, go back to fix this error.</p></div></div>';
			return;
		}
		$query = "DELETE FROM trade_request WHERE offerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $offerID);
		$result->execute();
		echo '<div class="content"><p>You have denied the offer.</p></div></div>';
	}
	include 'template/footer.php';
?>
</body>
</html>