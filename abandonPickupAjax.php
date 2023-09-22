<?php
	include 'database_connections.php';
	$pokeID = $_REQUEST['pokeID'];
	if (!isset($pokeID)) {
		echo 'Error, no pokeID present.';
		exit;
	}
	do_Stuff();
	function do_Stuff() {
		global $id, $pokeID;
		$db_New = connect_To_Database_New();
		$query = "DELETE FROM trainer_pickup WHERE uniqueID = ?";
		$result = $db_New->prepare($query);
		$result->bind_param("s", $pokeID);
		$result->execute();
		$result->close();
		echo 'Goodbye! ';
		?>
        <a href="#close<?php echo $pokeID?>" onClick="closePickup('<?php echo $pokeID?>');return false;">Close</a>
        <?php
	}
?>
