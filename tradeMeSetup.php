<?php
	session_start();
	$whichProfile = $_REQUEST['whichProfile'];
	include 'database_connections.php';
	$showTopAd = "yes";
	$showSideAd = "yes";
	$id = $_SESSION['myID'];
	$currentSave = $_SESSION['currentSave'];
	$loggedIn = true;
	$pageTitle = "Trade Setup";
	$pageMenuset = "extended";
	require 'moveList.php';
	$pokeID = $_REQUEST['pokeID'];
	include 'template/ptd1_cookies.php';
	include 'template/head.php';
?>
<body>
<?php
	$db = connect_To_Database();
$reason = "go";
$query = "select whichDB from poke_accounts WHERE trainerID = ? AND currentSave = ?";
	$result = $db->prepare($query);
	$result->bind_param("is", $id, $currentSave);
	$result->execute();
	$result->store_result();
	$result->bind_result($whichDB);			
	if ($result->affected_rows) {
		$result->fetch();
		$result->close();
	}else{
		$result->close();
		$reason = "savedOutside";
	}
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
					<div class="title thin"><p>Trade Setup - <a href="createTrade.php?<?php echo $urlValidation ?>">Go Back</a></p></div>
				</div>
                <?php if ($reason == "savedOutside") { ?>
                	<div class="block">
					<div class="content">
						 <p>It seems you have saved outside of the Trading Center. <a href="trading.php">Please go back and log in again</a>.</p>
					</div>
                   </div>
                <?php }else { 
$dbActual = get_Pokemon_Database($whichDB, $db);
	$query = "SELECT num, lvl, exp, shiny, nickname, m1, m2, m3, m4, mSel, ability, item, originalOwner, myTag FROM trainer_pokemons WHERE trainerID = ? AND uniqueID = ?";
	$result = $dbActual->prepare($query);
	$result->bind_param("ii", $id, $pokeID);
	$result->execute();
	$result->store_result();
	$result->bind_result($pokeNum, $pokeLevel, $pokeExp, $pokeShiny, $pokeNickname, $m1, $m2, $m3, $m4, $mSel, $ability, $item, $originalOwner, $myTag);
	if ($result->affected_rows == 0) {
		$result->close();
		?>
        <div class="block">
			<div class="content">
				 <p>You cannot trade this pokemon. <a href="trading.php">Click here to go back.</a></p>
			</div>
        </div>
        <?php
	}else{
		$result->fetch();
	$pokeNickname = strip_tags($pokeNickname);
	$result->close();
		$isHacked = "";
		if ($myTag == "h") {
			$isHacked = " - <b>(Hacked Version)</b>";
		}
		$pokeNickname = stripslashes($pokeNickname).$isHacked;
		pokeBox($pokeNickname, $pokeLevel, $pokeShiny, $m1, $m2, $m3, $m4, $pokeNum, "&nbsp;");				
				?>
				<div class="block">
					<div class="content">
						<p>You are about to list this pokemon up for trade. In the forms below choose what you want in return for your trade. If another trainer fufills ALL the request then the trade will be accepted automatically.</p>
						<p>If you don't want to specify who you want and want to let the trainers offer you different pokemon then leave all the fields empty and press submit.</p>
					</div>
				</div>
				<form action="tradeMe.php?<?php echo $urlValidation ?>&Action=setup&pokeID=<?php echo $pokeID ?>" method="post" name="form1" id="form1">
				<div class="block">
					<div class="content">
						<?php for($i = 1; $i <=6; $i++) { ?>
						<div class="block">
							<div class="title"><p>Pokémon Request #<?=$i?>:</p></div>
							<div class="content">
								<p>
									<span class="formLabel small">Pokémon:</span>
									<label>
										<select id="poke<?=$i?>" size="1" name="poke<?=$i?>">
											<option selected="selected" value="-1">None</option>
											<option value="0">Any</option>
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
                                <p><span class="formLabel small">Type:</span>
    <label>
      <input type="radio" name="type<?php echo $i ?>" id="regular" value="0" />
      Regular</label>
    <label>
      <input name="type<?php echo $i ?>" type="radio" id="shiny" value="1" />
      Shiny</label>
      <label>
     <label>
      <input name="type<?php echo $i ?>" type="radio" id="shadow" value="2" />
      Shadow</label>
      <label>
      <input name="type<?php echo $i ?>" type="radio" id="any" value="-1" checked="checked"/>
      Any</label>
  </p>
								<p>
									<span class="formLabel small">Level:</span>
									<label>
										<select id="levelComparison<?=$i?>" size="1" name="levelComparison<?=$i?>">
											<option selected="selected" value="1">=</option>
											<option value="2">&lt;=</option>
											<option value="3">&gt;=</option>
											<option value="4">&lt;</option>
											<option value="5">&gt;</option>
										</select>
									</label>
									<label>
										<select id="level<?=$i?>" size="1" name="level<?=$i?>">
											<option value="0">Any</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
											<option value="24">24</option>
											<option value="25">25</option>
											<option value="26">26</option>
											<option value="27">27</option>
											<option value="28">28</option>
											<option value="29">29</option>
											<option value="30">30</option>
											<option value="31">31</option>
											<option value="32">32</option>
											<option value="33">33</option>
											<option value="34">34</option>
											<option value="35">35</option>
											<option value="36">36</option>
											<option value="37">37</option>
											<option value="38">38</option>
											<option value="39">39</option>
											<option value="40">40</option>
											<option value="41">41</option>
											<option value="42">42</option>
											<option value="43">43</option>
											<option value="44">44</option>
											<option value="45">45</option>
											<option value="46">46</option>
											<option value="47">47</option>
											<option value="48">48</option>
											<option value="49">49</option>
											<option value="50">50</option>
											<option value="51">51</option>
											<option value="52">52</option>
											<option value="53">53</option>
											<option value="54">54</option>
											<option value="55">55</option>
											<option value="56">56</option>
											<option value="57">57</option>
											<option value="58">58</option>
											<option value="59">59</option>
											<option value="60">60</option>
                                              <option value="61">61</option>
                                              <option value="62">62</option>
                                              <option value="63">63</option>
                                              <option value="64">64</option>
                                              <option value="65">65</option>
                                              <option value="66">66</option>
                                              <option value="67">67</option>
                                              <option value="68">68</option>
                                              <option value="69">69</option>
                                              <option value="70">70</option>
                                              <option value="71">71</option>
                                              <option value="72">72</option>
                                              <option value="73">73</option>
                                              <option value="74">74</option>
                                              <option value="75">75</option>
                                              <option value="76">76</option>
                                              <option value="77">77</option>
                                              <option value="78">78</option>
                                              <option value="79">79</option>
                                              <option value="80">80</option>
                                              <option value="81">81</option>
                                              <option value="82">82</option>
                                              <option value="83">83</option>
                                              <option value="84">84</option>
                                              <option value="85">85</option>
                                              <option value="86">86</option>
                                              <option value="87">87</option>
                                              <option value="88">88</option>
                                              <option value="89">89</option>
                                              <option value="90">90</option>
                                              <option value="91">91</option>
                                              <option value="92">92</option>
                                              <option value="93">93</option>
                                              <option value="94">94</option>
                                              <option value="95">95</option>
                                              <option value="96">96</option>
                                              <option value="97">97</option>
                                              <option value="98">98</option>
                                              <option value="99">99</option>
                                              <option value="100">100</option>
										</select>
									</label>
								</p>
							</div>
						</div>
						<?php } ?>
						<p><input type="submit" value="Submit" id="submit" name="submit"></p>
					</div>
				</form>
				</div>
                <?php }
				}?>
			</td>
		</tr>
	</table>
</div>
<?php
	include 'template/footer.php';
?>
</body>
</html>