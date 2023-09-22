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
<title>Pokemon Center - Add PTD3 Lab Reward</title>
</head>

<body>
	<p>
		These are rewards that players can get for collecting full elementals of a family.
	</p>
<form id="form1" name="form1" method="post" action="ptd3AddLabReward.php?email=sotero86@gmail.com&pass=luigi1">
<p>Pokemon Number:
  <input type="text" name="pokeNum" id="pokeNum" />
  </p>
  <p>Pokemon Name:
  <input type="text" name="pokeName" id="pokeName" />
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
$elm1 = $_REQUEST['elm1'];

//If the form is empty do nothing
 if (empty($pokeNum) || empty($pokeName)) {
	 exit;
 }
//Connect to trading database
$db = connect_To_ptd2_Trading();
$type = 'lab';
$cost = 0;
	
//Get last cost value for lab
$queryTotal = "SELECT cost FROM ptdtrad_ptd2_trading.ptd3_rewards ORDER by cost desc LIMIT 1";
$resultTotal = $db->prepare($queryTotal);
$resultTotal->execute();
$resultTotal->store_result();
$resultTotal->bind_result($cost);
$resultTotal->fetch();
$resultTotal->free_result();
$resultTotal->close();
	
if ($cost < 1) {
	$cost = 1;
}else{
	$cost++;
}
	
$query = "INSERT INTO ptdtrad_ptd2_trading.ptd3_rewards (pokeNum, pokeName, elementNum, cost, type) VALUES ";
$query .= "($pokeNum, '$pokeName', $elm1, $cost, '$type')";

echo $query;

if ($db->query($query) == true) {
	echo " SUCCESS! Added for Lab Reward #$cost";
}else{
	echo $db->error;
}
	?>
</p>
</body>
</html>