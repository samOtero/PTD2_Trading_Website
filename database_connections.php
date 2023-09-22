<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
 function connect_To_Database() {
 	$db = mysqli_connect('', '', '', 'sndgame_ptdprofile5');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra'.mysqli_connect_errno().mysqli_connect_error();
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_New() {
 	$db = new mysqli('', '', '', 'sndgame_ptdOldProfiles');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_2() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile2');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_3() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile3');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_4() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile4');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_5() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile6');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_7() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile7');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_8() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile8');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_Database_9() {
 	$db = new mysqli('', '', '', 'sndgame_ptdprofile9');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_HTD_Database() {
 	$db = new mysqli('', '', '', 'sndgame_htdunits');
 
 	if (mysqli_connect_errno()) {
		echo encrypt('Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno());
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_1on1_Database() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2OneVOne');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Story_Database() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_accounts');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Story_Extra_Database() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2Story');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_1() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_1');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_2() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_2');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_3() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_3');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_4() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_4');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_1_New() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_1_New');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_2_New() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_2_New');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_3_New() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_3_New');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Poke_Storage_4_New() {
 	$db = new mysqli('', '', '', 'sndgame_ptd2_pokeStorage_4_New');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_ptd2_Trading() {
 	$db = new mysqli('localhost', '', '', 'ptdtrad_ptd2_trading');
 	//$db = new mysqli('', '', '', 'sndgame_ptd2_trading');
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function connect_To_Cosmoids() {
 	$db = new mysqli('', '', '', 'sndgame_cosmoids');
 
 	if (mysqli_connect_errno()) {
		echo 'Result=Failure&Reason=DatabaseConnection&Extra='.mysqli_connect_errno();
	 	exit;
 	}
	return $db;
 }
 function get_PTD2_Pokemon_Database($whichDB) {
	 if ($whichDB == 1) {
		return connect_To_ptd2_Poke_Storage_1();
	}else if ($whichDB == 2) {
		return connect_To_ptd2_Poke_Storage_2();
	}else if ($whichDB == 3) {
		return connect_To_ptd2_Poke_Storage_3();
	}else if ($whichDB == 4) {
		return connect_To_ptd2_Poke_Storage_4();
	}else if ($whichDB == 5) {
		return connect_To_ptd2_Poke_Storage_1_New();
	}else if ($whichDB == 6) {
		return connect_To_ptd2_Poke_Storage_2_New();
	}else if ($whichDB == 7) {
		return connect_To_ptd2_Poke_Storage_3_New();
	}
	return connect_To_ptd2_Poke_Storage_4_New();
 }
 function get_HTD_Unit_Database($whichDB, $defaultDB) {
 	return $defaultDB;
 }
 function get_Pokemon_Database($whichDB, $defaultDB) {
	 if ($whichDB == 2) {
		return connect_To_Database_2();
	}else if ($whichDB == 3) {
		return connect_To_Database_3();
	}else if ($whichDB == 4) {
		return connect_To_Database_4();
	}else if ($whichDB == 5) {
		return connect_To_Database_5();
	}else if ($whichDB == 7) {
		return connect_To_Database_7();
	}else if ($whichDB == 8) {
		return connect_To_Database_8();
	}else if ($whichDB == 9) {
		return connect_To_Database_9();
	}
 	return $defaultDB;
 }
 /////////////////////////////////////////////////////////////////////////////////////////////////
?>