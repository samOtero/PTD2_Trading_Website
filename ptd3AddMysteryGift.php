<?php
$whichEmail = $_REQUEST['email'];
$whichPass = $_REQUEST['pass'];
if ($whichEmail != "sotero86@gmail.com" || $whichPass != "luigi1") {
	echo "wrong email or pass";
	exit;
}
include 'moveList.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pokemon Center - Add PTD3 Mystery Gift</title>
</head>

<body>
	<p>
		*Make sure you upload an image with the same name to the PTD3 trading center site.
	</p>
<form id="form1" name="form1" method="post" action="ptd3AddMysteryGift.php?email=sotero86@gmail.com&pass=luigi1">
<p>Pokemon Number:
  <input type="text" name="pokeNum" id="pokeNum" />
  </p>
  <p>Pokemon Name*:
  <input type="text" name="pokeName" id="pokeName" />
  </p>
  <?php
	//Loop through all our attack 4 times to create attack fields
	for ($a = 1; $a <= 4; $a++) {
		echo "<p>Attack $a: <select name=\"atk$a\">";
		echo "<option value=0>Empty</option>"; //Add our empty value
		for($i = 1; $i <= 691; $i++) {
			echo "<option value=$i>".get_Move_Name_By_ID($i)."</option>";
		}
		echo "</select></p>";
	}
  	
	?>
  <p>Start Date:
  <input type="text" name="startDate" id="startDate" value="<?php echo date("Y-m-d")?>" />
  </p>
  <p>Element:
  	<select name="elm1">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm2">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm3">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm4">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm5">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm6">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  <select name="elm7">
  	<option value="3">Grass</option>
  	<option value="4">Poison</option>
  	<option value="5">Water</option>
  	<option value="6">Fire</option>
  	<option value="7">Normal</option>
  	<option value="8">Flying</option>
  	<option value="9">Bug</option>
  	<option value="10">Ghost</option>
  	<option value="11">Steel</option>
  	<option value="12">Rock</option>
  	<option value="13">Electric</option>
  	<option value="14">Ice</option>
  	<option value="15">Fighting</option>
  	<option value="16">Ground</option>
  	<option value="17">Dragon</option>
  	<option value="18">Dark</option>
  	<option value="19">Psychic</option>
  	<option value="21">Fairy</option>
  	<option value="99">Trainer ID Based</option>
	  </select>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
include 'database_connections.php';
//Get our request from the form
$pokeNum = $_REQUEST['pokeNum'];
$pokeName = $_REQUEST['pokeName'];
$atk1 = $_REQUEST['atk1'];
$atk2 = $_REQUEST['atk2'];
$atk3 = $_REQUEST['atk3'];
$atk4 = $_REQUEST['atk4'];
$startDate = $_REQUEST['startDate'];
$elm1 = $_REQUEST['elm1'];
$elm2 = $_REQUEST['elm2'];
$elm3 = $_REQUEST['elm3'];
$elm4 = $_REQUEST['elm4'];
$elm5 = $_REQUEST['elm5'];
$elm6 = $_REQUEST['elm6'];
$elm7 = $_REQUEST['elm7'];

//If the form is empty do nothing
 if (empty($pokeNum) || empty($pokeName)) {
	 exit;
 }
//Connect to trading database
$db = connect_To_ptd2_Trading();
//Get dates from the start date supplied
$date1 = strtotime($startDate);
$date2 = strtotime("+1 day", $date1);
$date3 = strtotime("+1 day", $date2);
$date4 = strtotime("+1 day", $date3);
$date5 = strtotime("+1 day", $date4);
$date6 = strtotime("+1 day", $date5);
$date7 = strtotime("+1 day", $date6);

//Get string version of date to add to the database
$date1String = date("Y-m-d", $date1);
$date2String = date("Y-m-d", $date2);
$date3String = date("Y-m-d", $date3);
$date4String = date("Y-m-d", $date4);
$date5String = date("Y-m-d", $date5);
$date6String = date("Y-m-d", $date6);
$date7String = date("Y-m-d", $date7);

$query = "INSERT INTO ptdtrad_ptd2_trading.ptd_3_mysterygift (num, m1, m2, m3, m4, item, date, shiny, name) VALUES ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date1String', $elm1, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date2String', $elm2, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date3String', $elm3, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date4String', $elm4, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date5String', $elm5, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date6String', $elm6, '$pokeName'), ";
$query .= "($pokeNum, $atk1, $atk2, $atk3, $atk4, -1, '$date7String', $elm7, '$pokeName')";

echo $query;

if ($db->query($query) == true) {
	echo "Success";
}else{
	echo $db->error;
}
	?>
</p>
</body>
</html>