<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Search Trades";
	$pageMenuset = "extended";
	require 'moveList.php';
	include 'template/ptd1_cookies.php';
	//require 'trade_To_Pickup_By_ID.php';
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
                <?php
					$backURL = "checkPokemon.php";
					$action = $_REQUEST['Action'];
					if ($action == "search") {
						$backURL = "searchTrades.php";
					}
				?>
					<div class="title"><p>Trade Search - <a href="<?php echo $backURL ?>?<?php echo $urlValidation ?>">Go Back</a></p></div>
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
		global $id, $db, $urlValidation, $whichProfile, $currentSave;
		$action = $_REQUEST['Action'];
		$expireDate = date( 'Y-m-d', strtotime('-10 days'));
		if ($action == "search") {
			$whichPoke = $_REQUEST['pokeList'];
			$whichPokeWant = $_REQUEST['pokeList2'];
			$typeWant = $_REQUEST['typeWant'];
			$type = $_REQUEST['type'];
			$haveRequest = $_REQUEST['haveRequest'];
			$specificID = $_REQUEST['specificTradeID'];
			//$specificTrainer = $_REQUEST['trainerName'];
			
			$db_New = connect_To_Database_New();
			
			/////////////////RETURN TO PICKUP A POKEMON THAT HAS BEEN UP FOR MORE THAN 10 DAYS WITH INACTIVITY IN THE USER/////////////////////////////
			/*$query = "SELECT uniqueID, currentTrainer FROM trainer_trades WHERE pickup = 0 AND lastTimeUsed <= ? LIMIT 1";
			$result = $db_New->prepare($query);
			$result->bind_param("s", $expireDate);
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($tradeIDPickup, $currentTrainerPickup);
			$query2 = "DELETE FROM trade_request WHERE tradePokeID = ? OR requestPokeID = ?";
			$result2 = $db_New->prepare($query2);
			$query3 = "DELETE FROM trade_wants WHERE tradePokeID = ?";
			$result3 = $db_New->prepare($query3);
			for ($i=0; $i<$hmp; $i++) {
				$result->fetch();
				//echo $tradeID;
				$result2->bind_param("ss", $tradeIDPickup, $tradeIDPickup);
				$result2->execute();
				$result3->bind_param("s", $tradeIDPickup);
				$result3->execute();
				trade_To_Pickup($db_New, $tradeIDPickup, $currentTrainerPickup);
			}
			$result2->close();
			$result3->close();
			$result->close();*/
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
			if ($whichPokeWant != 0) {
				//DECOMP
				$queryExtra = "SELECT distinct tradePokeID FROM trade_wants WHERE num = ? AND (shiny = ? OR shiny = -1)";
				$queryExtraIn ="";
				$resultExtra = $db_New->prepare($queryExtra);
				$resultExtra->bind_param("ii", $whichPokeWant, $typeWant);
				$resultExtra->execute();
				$resultExtra->store_result();
				$hmpExtra = $resultExtra->affected_rows;
				$resultExtra->bind_result($tradePokeIDExtra);
				if ($hmpExtra < 1) {
					$queryExtraIn = "'none'";
				}
				for ($i=1; $i<=$hmpExtra; $i++) {
					$resultExtra->fetch();
					if ($i > 1) {
						$queryExtraIn = $queryExtraIn.', ';
					}
					$queryExtraIn = $queryExtraIn."'".$tradePokeIDExtra."'";
				}
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE uniqueID IN (".$queryExtraIn.") ORDER BY num, lvl";
				$result = $db_New->prepare($query);
				
				
				
				//$query = "SELECT trainer_trades.num, trainer_trades.lvl, trainer_trades.shiny, trainer_trades.currentTrainer, trainer_trades.uniqueID, trainer_trades.nickname, trainer_trades.myTag, trainer_trades.m1, trainer_trades.m2, trainer_trades.m3, trainer_trades.m4 FROM trainer_trades, trade_wants WHERE trainer_trades.uniqueID = trade_wants.tradePokeID AND trade_wants.num = ? AND (trade_wants.shiny = ? OR trade_wants.shiny = -1) AND lastTimeUsed > ? ORDER BY trainer_trades.num, trainer_trades.lvl";
				//$result = $db_New->prepare($query);
				//$result->bind_param("iis", $whichPokeWant, $typeWant, $expireDate);
			}else if (!empty($specificID)) {
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE uniqueID = ? AND pickup = 0 AND lastTimeUsed > ?";
				$result = $db_New->prepare($query);
				$result->bind_param("ss", $specificID, $expireDate);
			//}else if (!empty($specificTrainer)) {
//				$query = "select  trainerID from poke_accounts WHERE accNickname = ?";
//				$result2 = $db->prepare($query);
//				$result2->bind_param("s", $specificTrainer);
//				$result2->execute();
//				$result2->store_result();
//				$result2->bind_result($tradeTrainerID);			
//				$result2->fetch();
//				$result2->close();
//				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE currentTrainer = ? AND pickup = 0 AND lastTimeUsed > ? ORDER BY num, lvl";
//				$result = $db_New->prepare($query);
//				$result->bind_param("is", $tradeTrainerID, $expireDate);
			}else if ($haveRequest == "1") {		
				//DECOMP
				//selecting first the desidered trades that are in the right date
				$queryExtra = "SELECT uniqueID FROM trainer_trades WHERE num = ? AND shiny = ? AND pickup = 0 AND lastTimeUsed > ?";
				$resultExtra = $db_New->prepare($queryExtra);
				$resultExtra->bind_param("iis", $whichPoke, $type, $expireDate);
				//
				//making a list of the the uniqueIDs from this query
				$queryExtraIn ="";
				$resultExtra->execute();
				$resultExtra->store_result();
				$hmpExtra = $resultExtra->affected_rows;
				$resultExtra->bind_result($tradePokeIDExtra);
				if ($hmpExtra < 1) {
					$queryExtraIn = "'none'";
				}
				for ($i=1; $i<=$hmpExtra; $i++) {
					$resultExtra->fetch();
					if ($i > 1) {
						$queryExtraIn = $queryExtraIn.', ';
					}
					$queryExtraIn = $queryExtraIn."'".$tradePokeIDExtra."'";
				}
				$resultExtra->free_result();
				$resultExtra->close();
				//
				//select the ones that are in the list
				$queryExtra = "SELECT tradePokeID FROM trade_wants WHERE tradePokeID IN (".$queryExtraIn.")";
				$resultExtra = $db_New->prepare($queryExtra);
				//
				//make a list of the uniqueID from this query
				$queryExtraIn2 ="";
				$resultExtra->execute();
				$resultExtra->store_result();
				$hmpExtra = $resultExtra->affected_rows;
				$resultExtra->bind_result($tradePokeIDExtra);
				if ($hmpExtra < 1) {
					$queryExtraIn2 = "'none'";
				}
				for ($i=1; $i<=$hmpExtra; $i++) {
					$resultExtra->fetch();
					if ($i > 1) {
						$queryExtraIn2 = $queryExtraIn2.', ';
					}
					$queryExtraIn2 = $queryExtraIn2."'".$tradePokeIDExtra."'";
				}
				$resultExtra->free_result();
				$resultExtra->close();
				//
				//finally search for which are left
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE uniqueID IN (".$queryExtraIn2.") ORDER BY lvl";
				$result = $db_New->prepare($query);
				//
			//}else if ($haveRequest == "2") {
				//$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE num = ? AND shiny = ? AND pickup = 0 AND lastTimeUsed > ? AND NOT EXISTS (SELECT shiny FROM trade_wants WHERE trainer_trades.uniqueID = trade_wants.tradePokeID) ORDER BY lvl";
				//$result = $db_New->prepare($query);
				//$result->bind_param("iis", $whichPoke, $type, $expireDate);
			}else {
				$query = "SELECT num, lvl, shiny, currentTrainer, uniqueID, nickname, myTag, m1, m2, m3, m4 FROM trainer_trades WHERE num = ? AND shiny = ? AND pickup = 0 AND lastTimeUsed > ? ORDER BY lvl";
				$result = $db_New->prepare($query);
				$result->bind_param("iis", $whichPoke, $type, $expireDate);		
			}
			$result->execute();
			$result->store_result();
			$hmp = $result->affected_rows;
			$result->bind_result($pokeNum, $pokeLevel,$pokeShiny, $currentTrainer, $tradeID, $pokeNickname, $myTag, $m1, $m2, $m3, $m4);
			if ($hmp == 0) {
				echo '<div class="content"><p>No Pokemon have been found that match your criteria.</p></div></div>';
				return;
			}
			echo '</div>';
			//$db = connect_To_Database();
			for ($i=1; $i<=$hmp; $i++) {
				$result->fetch();
				$pokeNickname = stripslashes($pokeNickname);
				$isHacked = "";
				if ($myTag == "h") {
					$isHacked = " - <b>(Hacked Version)</b>";
				}
				$pokeNickname = $pokeNickname.$isHacked;
				//$query = "select  accNickname, avatar1, avatar2, avatar3, whichAvatar from poke_accounts WHERE trainerID = ?";
//				$result2 = $db->prepare($query);
//				$result2->bind_param("i", $currentTrainer);
//				$result2->execute();
//				$result2->store_result();
//				$result2->bind_result($accNickname, $avatar1, $avatar2, $avatar3, $whichAvatar);			
//				$result2->fetch();
//				$result2->close();
				$accNickname = "";
				$avatar1 = "";
				$avatar2 = "";
				$avatar3 = "";
				$whichAvatar = "1";
				pokeBox_Search($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, '<a href="requestTrade.php?'.$urlValidation.'&tradeID='.$tradeID.'">Request Trade</a>', ${avatar.$whichAvatar}, $accNickname, $tradeID);
			}
			$result->close();
		}else{
		echo '<div class="content">';	
		?>
<p>Search for which pokemon you want:</p>
<form id="form1" name="form1" method="post" action="searchTrades.php?<?php echo $urlValidation ?>&Action=search">
  <p>
    <label>Pokemon
      <select name="pokeList" size="1" id="pokeList">
        <option value="1" selected="selected">Bulbasaur</option>
        <option value="2">Ivysaur</option>
        <option value="3">Venusaur</option>
        <option value="4">Charmander</option>
        <option value="5">Charmeleon</option>
        <option value="6">Charizard</option>
        <option value="7">Squirtle</option>
        <option value="8">Wartortle</option>
        <option value="9">Blastoise</option>
        <option value="10">Caterpie</option>
        <option value="11">Metapod</option>
        <option value="12">Butterfree</option>
        <option value="13">Weedle</option>
        <option value="14">Kakuna</option>
        <option value="15">Beedrill</option>
        <option value="16">Pidgey</option>
        <option value="17">Pidgeotto</option>
        <option value="18">Pidgeot</option>
        <option value="19">Rattata</option>
        <option value="20">Raticate</option>
        <option value="21">Spearow</option>
        <option value="22">Fearow</option>
        <option value="23">Ekans</option>
        <option value="24">Arbok</option>
        <option value="25">Pikachu</option>
        <option value="26">Raichu</option>
        <option value="27">Sandshrew</option>
        <option value="28">Sandslash</option>
        <option value="29">Nidoran F</option>
        <option value="30">Nidorina</option>
        <option value="31">Nidoqueen</option>
        <option value="32">Nidoran M</option>
        <option value="33">Nidorino</option>
        <option value="34">Nidoking</option>
        <option value="35">Clefairy</option>
        <option value="36">Clefable</option>
        <option value="37">Vulpix</option>
        <option value="38">Ninetales</option>
        <option value="39">Jigglypuff</option>
        <option value="40">Wigglytuff</option>
        <option value="41">Zubat</option>
        <option value="42">Golbat</option>
        <option value="43">Oddish</option>
        <option value="44">Gloom</option>
        <option value="45">Vileplume</option>
        <option value="46">Paras</option>
        <option value="47">Parasect</option>
        <option value="48">Venonat</option>
          <option value="49">Venomoth</option>
        <option value="50">Diglett</option>
        <option value="51">Dugtrio</option>
        <option value="52">Meowth</option>
        <option value="53">Persian</option>
        <option value="54">Psyduck</option>
        <option value="55">Golduck</option>
        <option value="56">Mankey</option>
        <option value="57">Primeape</option>
        <option value="58">Growlithe</option>
        <option value="59">Arcanine</option>
        <option value="60">Poliwag</option>
        <option value="61">Poliwhirl</option>
        <option value="62">Poliwrath</option>
        <option value="63">Abra</option>
        <option value="64">Kadabra</option>
        <option value="65">Alakazam</option>
        <option value="66">Machop</option>
        <option value="67">Machoke</option>
        <option value="68">Machamp</option>
        <option value="69">Bellsprout</option>
        <option value="70">Weepinbell</option>
        <option value="71">Victreebel</option>
        <option value="72">Tentacool</option>
        <option value="73">Tentacruel</option>
        <option value="74">Geodude</option>
        <option value="75">Graveler</option>
        <option value="76">Golem</option>
        <option value="77">Ponyta</option>
        <option value="78">Rapidash</option>
        <option value="79">Slowpoke</option>
        <option value="80">Slowbro</option>
        <option value="81">Magnemite</option>
        <option value="82">Magneton</option>
        <option value="83">Farfetch'd</option>
        <option value="84">Doduo</option>
        <option value="85">Dodrio</option>
        <option value="86">Seel</option>
        <option value="87">Dewgong</option>
        <option value="88">Grimer</option>
        <option value="89">Muk</option>
        <option value="90">Shellder</option>
        <option value="91">Cloyster</option>
        <option value="92">Gastly</option>
         <option value="93">Haunter</option>
          <option value="94">Gengar</option>
        <option value="95">Onix</option>
        <option value="96">Drowzee</option>
        <option value="97">Hypno</option>
        <option value="98">Krabby</option>
        <option value="99">Kingler</option>
        <option value="100">Voltorb</option>
        <option value="101">Electrode</option>
        <option value="102">Exeggcute</option>
        <option value="103">Exeggutor</option>
        <option value="104">Cubone</option>
        <option value="105">Marowak</option>
        <option value="106">Hitmonlee</option>
        <option value="107">Hitmonchan</option>
        <option value="108">Lickitung</option>
        <option value="109">Koffing</option>
        <option value="110">Weezing</option>
        <option value="111">Rhyhorn</option>
        <option value="112">Rhydon</option>
        <option value="113">Chansey</option>
        <option value="114">Tangela</option>
        <option value="115">Kangaskhan</option>
        <option value="116">Horsea</option>
        <option value="117">Seadra</option>
        <option value="118">Goldeen</option>
        <option value="119">Seaking</option>
        <option value="120">Staryu</option>
        <option value="121">Starmie</option>
        <option value="122">Mr Mime</option>
         <option value="123">Scyther</option>
        <option value="124">Jynx</option>
        <option value="125">Electabuzz</option>
        <option value="126">Magmar</option>
        <option value="127">Pinsir</option>
        <option value="128">Tauros</option>
        <option value="129">Magikarp</option>
        <option value="130">Gyarados</option>
        <option value="131">Lapras</option>
        <option value="132">Ditto</option>
        <option value="133">Eevee</option>
        <option value="134">Vaporeon</option>
        <option value="135">Jolteon</option>
        <option value="136">Flareon</option>
         <option value="137">Porygon</option>
         <option value="138">Omanyte</option>
         <option value="139">Omastar</option>
         <option value="140">Kabuto</option>
         <option value="141">Kabutops</option>
         <option value="142">Aerodactyl</option>
         <option value="143">Snorlax</option>
         <option value="144">Articuno</option>
       <option value="145">Zapdos</option>
        <option value="146">Moltres</option>
          <option value="147">Dratini</option>
           <option value="148">Dragonair</option>
           <option value="149">Dragonite</option>
           <option value="150">Mewtwo</option>
        <option value="151">Mew</option>
        <option value="243">Raikou</option>
        <option value="244">Entei</option>
        <option value="245">Suicune</option>
        <option value="494">Victini</option>
        <option value="1010">Missingno.</option>
      </select>
    </label>
  </p>
  <p>Type?:
    <label>
      <input type="radio" name="type" id="Regular" value="0"  checked="checked"/>
      Regular
    </label>
    <label>
      <input name="type" type="radio" id="Shiny" value="1" />
      Shiny
    </label>
    <label>
      <input name="type" type="radio" id="Shadow" value="2" />
      Shadow
    </label>
  </p>
   <p>Have Request?:
    <label>
      <input type="radio" name="haveRequest" id="all" value="0"  checked="checked"/>
      Show All
    </label>
    <label>
      <input name="haveRequest" type="radio" id="yes" value="1" />
      Yes
    </label>
    <?php /*?><label>
      <input name="haveRequest" type="radio" id="no" value="2" />
      No
    </label><?php */?>
  </p>
  <p>Or search by a specific trade id:</p>
  <p>TradeID
    <input name="specificTradeID" type="text" id="specificTradeID" size="14" />
  </p>
  <!--<p>Or Search by a specific trainer's account name:</p>
  <p>Trainer Account Name:
    <input type="text" name="trainerName" id="trainerName" />
  </p>-->
  <p>Or Search for which pokemon other trainers want:</p>
  <p>
    <label>Pokemon
      <select name="pokeList2" size="1" id="pokeList2">
      	<option value="0" selected="selected">Don't Search</option>
        <option value="1">Bulbasaur</option>
        <option value="2">Ivysaur</option>
        <option value="3">Venusaur</option>
        <option value="4">Charmander</option>
        <option value="5">Charmeleon</option>
        <option value="6">Charizard</option>
        <option value="7">Squirtle</option>
        <option value="8">Wartortle</option>
        <option value="9">Blastoise</option>
        <option value="10">Caterpie</option>
        <option value="11">Metapod</option>
        <option value="12">Butterfree</option>
        <option value="13">Weedle</option>
        <option value="14">Kakuna</option>
        <option value="15">Beedrill</option>
        <option value="16">Pidgey</option>
        <option value="17">Pidgeotto</option>
        <option value="18">Pidgeot</option>
        <option value="19">Rattata</option>
        <option value="20">Raticate</option>
        <option value="21">Spearow</option>
        <option value="22">Fearow</option>
        <option value="23">Ekans</option>
        <option value="24">Arbok</option>
        <option value="25">Pikachu</option>
        <option value="26">Raichu</option>
        <option value="27">Sandshrew</option>
        <option value="28">Sandslash</option>
        <option value="29">Nidoran F</option>
        <option value="30">Nidorina</option>
        <option value="31">Nidoqueen</option>
        <option value="32">Nidoran M</option>
        <option value="33">Nidorino</option>
        <option value="34">Nidoking</option>
        <option value="35">Clefairy</option>
        <option value="36">Clefable</option>
        <option value="37">Vulpix</option>
        <option value="38">Ninetales</option>
        <option value="39">Jigglypuff</option>
        <option value="40">Wigglytuff</option>
        <option value="41">Zubat</option>
        <option value="42">Golbat</option>
        <option value="43">Oddish</option>
        <option value="44">Gloom</option>
        <option value="45">Vileplume</option>
        <option value="46">Paras</option>
        <option value="47">Parasect</option>
        <option value="48">Venonat</option>
          <option value="49">Venomoth</option>
        <option value="50">Diglett</option>
        <option value="51">Dugtrio</option>
        <option value="52">Meowth</option>
        <option value="53">Persian</option>
        <option value="54">Psyduck</option>
        <option value="55">Golduck</option>
        <option value="56">Mankey</option>
        <option value="57">Primeape</option>
        <option value="58">Growlithe</option>
        <option value="59">Arcanine</option>
        <option value="60">Poliwag</option>
        <option value="61">Poliwhirl</option>
        <option value="62">Poliwrath</option>
        <option value="63">Abra</option>
        <option value="64">Kadabra</option>
        <option value="65">Alakazam</option>
        <option value="66">Machop</option>
        <option value="67">Machoke</option>
        <option value="68">Machamp</option>
        <option value="69">Bellsprout</option>
        <option value="70">Weepinbell</option>
        <option value="71">Victreebel</option>
        <option value="72">Tentacool</option>
        <option value="73">Tentacruel</option>
        <option value="74">Geodude</option>
        <option value="75">Graveler</option>
        <option value="76">Golem</option>
        <option value="77">Ponyta</option>
        <option value="78">Rapidash</option>
        <option value="79">Slowpoke</option>
        <option value="80">Slowbro</option>
        <option value="81">Magnemite</option>
        <option value="82">Magneton</option>
        <option value="83">Farfetch'd</option>
        <option value="84">Doduo</option>
        <option value="85">Dodrio</option>
        <option value="86">Seel</option>
        <option value="87">Dewgong</option>
        <option value="88">Grimer</option>
        <option value="89">Muk</option>
        <option value="90">Shellder</option>
        <option value="91">Cloyster</option>
        <option value="92">Gastly</option>
         <option value="93">Haunter</option>
          <option value="94">Gengar</option>
        <option value="95">Onix</option>
        <option value="96">Drowzee</option>
        <option value="97">Hypno</option>
        <option value="98">Krabby</option>
        <option value="99">Kingler</option>
        <option value="100">Voltorb</option>
        <option value="101">Electrode</option>
        <option value="102">Exeggcute</option>
        <option value="103">Exeggutor</option>
        <option value="104">Cubone</option>
        <option value="105">Marowak</option>
        <option value="106">Hitmonlee</option>
        <option value="107">Hitmonchan</option>
        <option value="108">Lickitung</option>
        <option value="109">Koffing</option>
        <option value="110">Weezing</option>
        <option value="111">Rhyhorn</option>
        <option value="112">Rhydon</option>
        <option value="113">Chansey</option>
        <option value="114">Tangela</option>
        <option value="115">Kangaskhan</option>
        <option value="116">Horsea</option>
        <option value="117">Seadra</option>
        <option value="118">Goldeen</option>
        <option value="119">Seaking</option>
        <option value="120">Staryu</option>
        <option value="121">Starmie</option>
        <option value="122">Mr Mime</option>
           <option value="123">Scyther</option>
        <option value="124">Jynx</option>
        <option value="125">Electabuzz</option>
        <option value="126">Magmar</option>
        <option value="127">Pinsir</option>
        <option value="128">Tauros</option>
        <option value="129">Magikarp</option>
        <option value="130">Gyarados</option>
        <option value="131">Lapras</option>
        <option value="132">Ditto</option>
        <option value="133">Eevee</option>
        <option value="134">Vaporeon</option>
        <option value="135">Jolteon</option>
        <option value="136">Flareon</option>
        <option value="137">Porygon</option>
        <option value="138">Omanyte</option>
         <option value="139">Omastar</option>
         <option value="140">Kabuto</option>
         <option value="141">Kabutops</option>
         <option value="142">Aerodactyl</option>
        <option value="143">Snorlax</option>
        <option value="144">Articuno</option>
       <option value="145">Zapdos</option>
        <option value="146">Moltres</option>
          <option value="147">Dratini</option>
           <option value="148">Dragonair</option>
           <option value="149">Dragonite</option>
           <option value="150">Mewtwo</option>
        <option value="151">Mew</option>
        <option value="243">Raikou</option>
        <option value="244">Entei</option>
        <option value="245">Suicune</option>
        <option value="494">Victini</option>
        <option value="1010">Missingno.</option>
      </select>
    </label>
  </p>
  <p>Type?:
    <label>
      <input type="radio" name="typeWant" id="Regular2" value="0" checked="checked" />
      Regular</label>
    <label>
      <input name="typeWant" type="radio" id="Shiny2" value="1" />
      Shiny</label>
     <label>
      <input name="typeWant" type="radio" id="Shadow2" value="2" />
      Shadow</label>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form>
		<?php
		echo '</div></div>';
		}
	}
	include 'template/footer.php';
?>
</body>
</html>