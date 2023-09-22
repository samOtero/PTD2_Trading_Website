<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "no";
	$showSideAd = "no";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave2'];
	$loggedIn = true;
	$pageTitle = "Remove Request";
	$pageMenuset = "extended";
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
					<div class="title"><p>Remove Request - <a href="yourTrades2.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
                    <?php if ($reason == "savedOutside") { ?>
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                <?php }else { ?>
                <?php
	$requestID = $_REQUEST['requestID'];
	$offerID = $_REQUEST['offerID'];
	$db_New = connect_To_ptd2_Trading();
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
		$result->free_result();
		$result->close();
		$db_New->close();
	}else{
		$result->free_result();
		$result->close();
		if (empty($offerID)) {
			?>
			<div class="content">
							<p>Error, press the back link to fix this error.</p>
						</div>
			<?php 
			$db_New->close();
		}else{
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
				 echo '<div class="content"><p>Your offer has been removed. The other trainer can no longer accept this offer.</p></div>';
			 }else{
				 $db_New->rollback();
				 echo '<div class="content"><p>An Error has Occurred. Please Try Again.</p></div>';
			 }
			 $db_New->autocommit(true);
			 $db_New->close();
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