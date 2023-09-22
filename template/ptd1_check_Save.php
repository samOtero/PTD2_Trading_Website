<?php
	$db = connect_To_Database();
	$query = "select  trainerID from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();			
	if ($result->affected_rows) {
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
?>