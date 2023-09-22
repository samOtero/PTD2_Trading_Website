<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Deny Request";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'ptd2_basic.php';
	include 'template/ptd2_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	//$reason = get_Current_Save_Status($id, $currentSave);
	//$profileInfo = get_Basic_Profile_Info($id, $whichProfile);
	if (is_null($profileInfo)) {
		$reason = "savedOutside";			
	}
	$urlValidation = "whichProfile=".$whichProfile;
	$requestID = $_REQUEST['requestID'];
	$offerID = $_REQUEST['offerID'];
	include 'template/header.php';
?>
<div id="content">
	<?php
		include 'template/navbar2.php';
	?>
	<table id="content_table">
		<tr>
			<?php
				include 'template/sidebar2.php';
			?>
			<td id="main">
				<div class="block">
					<div class="title"><p>Deny Request - <a href="yourTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
		$db_New = connect_To_ptd2_Trading();
		$query = "SELECT trade_request.requestPokeID FROM trade_request, trainer_trades WHERE trainer_trades.currentTrainer = ? AND trade_request.requestID = ? AND trainer_trades.uniqueID = trade_request.tradePokeID";
		$result = $db_New->prepare($query);
		$result->bind_param("ii", $id, $requestID);
		$result->execute();
		$result->store_result();
		$hmp = $result->affected_rows;
		$result->free_result();
		$result->close();
		if ($hmp == 0) {
			$db_New->close();
			echo '<div class="content"><p>You cannot deny this offer since you do not own the pokemon that is being traded or this request no longer exist.</p></div></div>';
			return;
		}
		if (empty($offerID)) {
			$db_New->close();
			echo '<div class="content"><p>Error, go back to fix this error.</p></div></div>';
			return;
		}
		$db_New->autocommit(false);
		$transactionFlag = true;
		$query = "DELETE FROM trade_request WHERE offerID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $offerID);
		if (!$result->execute()) {
			$transactionFlag = false;
		}
		$result->close();
		if ($transactionFlag == true) {
			$db_New->commit();
			echo '<div class="content"><p>You have denied the offer.</p></div></div>';
		}else{
			$db_New->rollback();
			echo '<div class="content"><p>An Error has Occurred. Please Try Again.</p></div></div>';
		}
		$db_New->autocommit(true);
		$db_New->close();
	}
	include 'template/footer.php';
?>
</body>
</html>