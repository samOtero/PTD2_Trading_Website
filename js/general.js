function getTradeRequest(id) {
	if($('requestLoad_' + id).innerHTML == "") {
		$('requestLoad_' + id).innerHTML = "<div class=\"block\"><div class=\"content middle\">Loading...</div></div>";
		new Ajax.Request(PATH_ROOT + 'tradeRequestAjax.php', {
			method: 'get',
			parameters: {tradeID: id},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('requestLoad_' + id).innerHTML = transport.responseText;
					$('getTradeRequestBtn_' + id).style.display = 'none';
				}
			},
			onFailure: function(){
				$('requestLoad_' + id).innerHTML = "<div class=\"block\"><div class=\"content middle\">Request was unable to complete.</div></div>";
			}
		});
	}
	return false;
}
////////////////////////////////////////////////////////////////////////////////////////////// PTD 2
function getTradeRequest2(id) {
	if($('requestLoad_' + id).innerHTML == "") {
		$('requestLoad_' + id).innerHTML = "<div class=\"block\"><div class=\"content middle\">Loading...</div></div>";
		new Ajax.Request(PATH_ROOT + 'tradeRequestAjax2.php', {
			method: 'get',
			parameters: {tradeID: id},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('requestLoad_' + id).innerHTML = transport.responseText;
					$('getTradeRequestBtn_' + id).style.display = 'none';
				}
			},
			onFailure: function(){
				$('requestLoad_' + id).innerHTML = "<div class=\"block\"><div class=\"content middle\">Request was unable to complete.</div></div>";
			}
		});
	}
	return false;
}
function createNewRequest2(which) {
	$('requestArea').innerHTML = "";
	$('requestArea'+which).innerHTML = get_New_Request_HTML(which);
	if (which == 3) {
		$('newRequest').innerHTML = "<p>New request created! Fill in the info below.</p><p>You have (0) request left.</p>";
	}else{
		var howManyLeft = 3-which;
		which++;
		$('newRequest').innerHTML = '<p>New request created! Fill in the info below.</p><p>You have ('+howManyLeft+') request left. <a href="#newRequest" onClick="createNewRequest2(\''+which+'\');return false;">Create New Request</a></p>';
	}
	return false;
}
function get_New_Request_HTML(which) {
	var responce = "";
	responce = responce +  '<div class="title"><p>Request #'+which+': <div id="leftRequest'+which+'">You have (9) more pokemon you can add to this request. <a href="#newRequestItem" onClick="createNewRequestItem2(2, '+which+');return false;">Add Pokemon</a></div></p></div>'
	responce = responce + get_Request_Poke_Box(which, 1);
	responce = responce + '<div id="requestSpot'+which+'_2"></div>';
	responce = responce + '<div id="requestSpot'+which+'_3"></div>';
	responce = responce + '<div id="requestSpot'+which+'_4"></div>';
	responce = responce + '<div id="requestSpot'+which+'_5"></div>';
	responce = responce + '<div id="requestSpot'+which+'_6"></div>';
	responce = responce + '<div id="requestSpot'+which+'_7"></div>';
	responce = responce + '<div id="requestSpot'+which+'_8"></div>';
	responce = responce + '<div id="requestSpot'+which+'_9"></div>';
	responce = responce + '<div id="requestSpot'+which+'_10"></div>';
	return responce;
}
function createNewRequestItem2(pokeNum, which) {
	var howManyLeft = 10-pokeNum;
	var newAmount = 1+pokeNum;
	if (howManyLeft > 0) {
	$('leftRequest'+which).innerHTML = 'You have ('+howManyLeft+') more pokemon you can add to this request. <a href="#newRequestItem" onClick="createNewRequestItem2('+newAmount+', '+which+');return false;">Add Pokemon</a>';
	}else{
		$('leftRequest'+which).innerHTML = 'You have ('+howManyLeft+') more pokemon you can add to this request.';
	}
	$('requestSpot'+which+'_'+pokeNum).innerHTML = get_Request_Poke_Box(which, pokeNum);
}
function get_Request_Poke_Box(which, pokeNum) {
	var responce = '<div class="block"><div class="content"><p><span class="formLabel small">Pokémon:</span><label>';
										responce = responce + '<select id="poke'+which+'_'+pokeNum+'" size="1" name="poke'+which+'_'+pokeNum+'">';
											responce = responce + '<option selected="selected" value="-1">None</option>';
											responce = responce + '<option value="-2">Any</option>';
											responce = responce + '<option value="1">Bulbasaur</option><option value="2">Ivysaur</option><option value="3">Venusaur</option><option value="4">Charmander</option><option value="5">Charmeleon</option><option value="6">Charizard</option><option value="7">Squirtle</option><option value="8">Wartortle</option><option value="9">Blastoise</option><option value="10">Caterpie</option><option value="11">Metapod</option><option value="12">Butterfree</option><option value="13">Weedle</option><option value="14">Kakuna</option><option value="15">Beedrill</option><option value="16">Pidgey</option><option value="17">Pidgeotto</option><option value="18">Pidgeot</option><option value="19">Rattata</option><option value="20">Raticate</option><option value="21">Spearow</option><option value="22">Fearow</option><option value="23">Ekans</option><option value="24">Arbok</option><option value="25">Pikachu</option><option value="26">Raichu</option><option value="27">Sandshrew</option><option value="28">Sandslash</option><option value="29">Nidoran F</option><option value="30">Nidorina</option><option value="31">Nidoqueen</option><option value="32">Nidoran M</option><option value="33">Nidorino</option><option value="34">Nidoking</option><option value="35">Clefairy</option><option value="36">Clefable</option><option value="37">Vulpix</option><option value="38">Ninetales</option><option value="39">Jigglypuff</option><option value="40">Wigglytuff</option><option value="41">Zubat</option><option value="42">Golbat</option><option value="43">Oddish</option><option value="44">Gloom</option><option value="45">Vileplume</option><option value="46">Paras</option><option value="47">Parasect</option><option value="48">Venonat</option><option value="49">Venomoth</option><option value="50">Diglett</option><option value="51">Dugtrio</option><option value="52">Meowth</option><option value="53">Persian</option><option value="54">Psyduck</option><option value="55">Golduck</option><option value="56">Mankey</option><option value="57">Primeape</option><option value="58">Growlithe</option><option value="59">Arcanine</option><option value="60">Poliwag</option><option value="61">Poliwhirl</option><option value="62">Poliwrath</option><option value="63">Abra</option><option value="64">Kadabra</option><option value="65">Alakazam</option><option value="66">Machop</option><option value="67">Machoke</option><option value="68">Machamp</option><option value="69">Bellsprout</option><option value="70">Weepinbell</option><option value="71">Victreebell</option><option value="72">Tentacool</option><option value="73">Tentacruel</option><option value="74">Geodude</option><option value="75">Graveler</option><option value="76">Golem</option><option value="77">Ponyta</option><option value="78">Rapidash</option><option value="79">Slowpoke</option><option value="80">Slowbro</option><option value="81">Magnemite</option><option value="82">Magneton</option><option value="83">Farfetchd</option><option value="84">Doduo</option><option value="85">Dodrio</option><option value="86">Seel</option><option value="87">Dewgong</option><option value="88">Grimer</option><option value="89">Muk</option><option value="90">Shellder</option><option value="91">Cloyster</option><option value="92">Gastly</option><option value="93">Haunter</option><option value="94">Gengar</option><option value="95">Onix</option><option value="96">Drowzee</option><option value="97">Hypno</option><option value="98">Krabby</option><option value="99">Kingler</option><option value="100">Voltorb</option><option value="101">Electrode</option><option value="102">Exeggcute</option><option value="103">Exeggutor</option><option value="104">Cubone</option><option value="105">Marowak</option><option value="106">Hitmonlee</option><option value="107">Hitmonchan</option><option value="108">Lickitung</option><option value="109">Koffing</option><option value="110">Weezing</option><option value="111">Rhyhorn</option><option value="112">Rhydon</option><option value="113">Chansey</option><option value="114">Tangela</option><option value="115">Kangaskhan</option><option value="116">Horsea</option><option value="117">Seadra</option><option value="118">Goldeen</option><option value="119">Seaking</option><option value="120">Staryu</option><option value="121">Starmie</option><option value="123">Scyther</option><option value="122">Mr. Mime</option><option value="124">Jynx</option><option value="125">Electabuzz</option><option value="126">Magmar</option><option value="127">Pinsir</option><option value="128">Tauros</option><option value="129">Magikarp</option><option value="130">Gyarados</option><option value="131">Lapras</option><option value="132">Ditto</option><option value="133">Eevee</option><option value="134">Vaporeon</option><option value="135">Jolteon</option><option value="136">Flareon</option><option value="137">Porygon</option><option value="138">Omanyte</option><option value="139">Omastar</option><option value="140">Kabuto</option><option value="141">Kabutops</option><option value="142">Aerodactyl</option><option value="143">Snorlax</option><option value="144">Articuno</option><option value="145">Zapdos</option><option value="146">Moltres</option><option value="147">Dratini</option><option value="148">Dragonair</option><option value="149">Dragonite</option><option value="150">Mewtwo</option><option value="151">Mew</option><option value="152">Chikorita</option><option value="153">Bayleef</option><option value="154">Meganium</option><option value="155">Cyndaquil</option><option value="156">Quilava</option><option value="157">Typhlosion</option><option value="158">Totodile</option><option value="159">Croconaw</option><option value="160">Feraligatr</option><option value="161">Sentret</option><option value="162">Furret</option><option value="163">Hoothoot</option><option value="164">Noctowl</option><option value="165">Ledyba</option><option value="166">Ledian</option><option value="167">Spinarak</option><option value="168">Ariados</option><option value="169">Crobat</option><option value="170">Chinchou</option><option value="171">Lanturn</option><option value="172">Pichu</option><option value="173">Cleffa</option><option value="174">Igglybuff</option><option value="175">Togepi</option><option value="176">Togetic</option><option value="177">Natu</option><option value="178">Xatu</option><option value="179">Mareep</option><option value="180">Flaaffy</option><option value="181">Ampharos</option><option value="182">Bellossom</option><option value="183">Marill</option><option value="184">Azumarill</option><option value="185">Sudowoodo</option><option value="186">Politoed</option><option value="187">Hoppip</option><option value="188">Skiploom</option><option value="189">Jumpluff</option><option value="190">Aipom</option><option value="191">Sunkern</option><option value="192">Sunflora</option><option value="193">Yanma</option><option value="194">Wooper</option><option value="195">Quagsire</option><option value="196">Espeon</option><option value="197">Umbreon</option><option value="198">Murkrow</option><option value="199">Slowking</option><option value="200">Misdreavus</option><option value="201">Unown</option><option value="202">Wobbuffet</option><option value="203">Girafarig</option><option value="204">Pineco</option><option value="205">Forretress</option><option value="206">Dunsparce</option><option value="207">Gligar</option><option value="208">Steelix</option><option value="209">Snubbull</option><option value="210">Granbull</option><option value="211">Qwilfish</option><option value="212">Scizor</option><option value="213">Shuckle</option><option value="214">Heracross</option><option value="215">Sneasel</option><option value="216">Teddiursa</option><option value="217">Ursaring</option><option value="218">Slugma</option><option value="219">Magcargo</option><option value="220">Swinub</option><option value="221">Pilowsine</option><option value="222">Corsola</option><option value="223">Remoraid</option><option value="224">Octillery</option><option value="225">Delibird</option><option value="226">Mantine</option><option value="227">Skarmory</option><option value="228">Houndour</option><option value="229">Houndoom</option><option value="230">Kingdra</option><option value="231">Phanpy</option><option value="232">Donphan</option><option value="233">Porygon 2</option><option value="234">Stantler</option><option value="235">Smeargle</option><option value="236">Tyrogue</option><option value="237">Hitmontop</option><option value="238">Smoochum</option><option value="239">Elekid</option><option value="240">Magby</option><option value="241">Miltank</option><option value="242">Blissey</option><option value="243">Raikou</option><option value="244">Entei</option><option value="245">Suicune</option><option value="246">Larvitar</option><option value="247">Pupitar</option><option value="248">Tyranitar</option><option value="249">Lugia</option><option value="250">Ho-oh</option><option value="251">Celebi</option><option value="252">Treecko</option><option value="253">Grovyle</option><option value="254">Sceptile</option><option value="255">Torchic</option><option value="256">Combusken</option><option value="257">Blaziken</option><option value="258">Mudkip</option><option value="259">Marshtomp</option><option value="260">Swampert</option><option value="261">Poochyena</option><option value="262">Mightyena</option><option value="263">Zigzagoon</option><option value="264">Linoone</option><option value="265">Wurmple</option><option value="266">Silcoon</option><option value="267">Beautifly</option><option value="268">Cascoon</option><option value="269">Dustox</option><option value="270">Lotad</option><option value="271">Lombre</option><option value="272">Ludicolo</option><option value="273">Seedot</option><option value="274">Nuzleaf</option><option value="275">Shiftry</option><option value="276">Taillow</option><option value="277">Swellow</option><option value="278">Wingull</option><option value="279">Pelipper</option><option value="280">Ralts</option><option value="281">Kirlia</option><option value="282">Gardevoir</option><option value="283">Surskit</option><option value="284">Masquerain</option><option value="285">Shroomish</option><option value="286">Breloom</option><option value="287">Slakoth</option><option value="288">Vigoroth</option><option value="289">Slaking</option><option value="290">Nincada</option><option value="291">Ninjask</option><option value="292">Shedinja</option><option value="293">Whismur</option><option value="294">Loudred</option><option value="295">Exploud</option><option value="296">Makuhita</option><option value="297">Hariyama</option><option value="298">Azurill</option><option value="299">Nosepass</option><option value="300">Skitty</option><option value="301">Delcatty</option><option value="302">Sableye</option><option value="303">Mawile</option><option value="304">Aron</option><option value="305">Lairon</option><option value="306">Aggron</option><option value="307">Meditite</option><option value="308">Medicham</option><option value="309">Electrike</option><option value="310">Manectric</option><option value="311">Plusle</option><option value="312">Minun</option><option value="313">Volbeat</option><option value="314">Illumise</option><option value="315">Roselia</option><option value="316">Gulpin</option><option value="317">Swalot</option><option value="318">Carvanha</option><option value="319">Sharpedo</option><option value="320">Wailmer</option><option value="321">Wailord</option><option value="322">Numel</option><option value="323">Camerupt</option><option value="324">Torkoal</option><option value="325">Spoink</option><option value="326">Grumpig</option><option value="327">Spinda</option><option value="328">Trapinch</option><option value="329">Vibrava</option><option value="330">Flygon</option><option value="331">Cacnea</option><option value="332">Cacturne</option><option value="333">Swablu</option><option value="334">Altaria</option><option value="335">Zangoose</option><option value="336">Seviper</option><option value="337">Lunatone</option><option value="338">Solrock</option><option value="339">Barboach</option><option value="340">Whiscash</option><option value="341">Corphish</option><option value="342">Crawdaunt</option><option value="343">Baltoy</option><option value="344">Claydol</option><option value="345">Lileep</option><option value="346">Cradily</option><option value="347">Anorith</option><option value="348">Armaldo</option><option value="349">Feebas</option><option value="350">Milotic</option><option value="351">Castform</option><option value="352">Kecleon</option><option value="353">Shuppet</option><option value="354">Banette</option><option value="355">Duskull</option><option value="356">Dusclops</option><option value="357">Tropius</option><option value="358">Chimecho</option><option value="359">Absol</option><option value="360">Wynaut</option><option value="361">Snorunt</option><option value="362">Glalie</option><option value="363">Spheal</option><option value="364">Sealeo</option><option value="365">Walrein</option><option value="366">Clamperl</option><option value="367">Huntail</option><option value="368">Gorebyss</option><option value="369">Relicanth</option><option value="370">Luvdisc</option><option value="371">Bagon</option><option value="372">Shelgon</option><option value="373">Salamence</option><option value="374">Beldum</option><option value="375">Metang</option><option value="376">Metagross</option><option value="377">Regirock</option><option value="378">Regice</option><option value="379">Registeel</option><option value="380">Latias</option><option value="381">Latios</option><option value="382">Kyogre</option><option value="383">Groudon</option><option value="384">Rayquaza</option><option value="385">Jirachi</option><option value="386">Deoxys</option><option value="387">Turtwig</option><option value="388">Grotle</option><option value="389">Torterra</option><option value="390">Chimchar</option><option value="391">Monferno</option><option value="392">Infernape</option><option value="393">Piplup</option><option value="394">Prinplup</option><option value="395">Empoleon</option><option value="396">Starly</option><option value="397">Staravia</option><option value="398">Staraptor</option><option value="399">Bidoof</option><option value="400">Bibarel</option><option value="401">Kricketot</option><option value="402">Kricketune</option><option value="403">Shinx</option><option value="404">Luxio</option><option value="405">Luxray</option><option value="406">Budew</option><option value="407">Roserade</option><option value="408">Cranidos</option><option value="409">Rampardos</option><option value="410">Shieldon</option><option value="411">Bastiodon</option><option value="412">Burmy</option><option value="413">Wormadam</option><option value="414">Mothim</option><option value="415">Combee</option><option value="416">Vespiquen</option><option value="417">Pachirisu</option><option value="418">Buizel</option><option value="419">Floatzel</option><option value="420">Cherubi</option><option value="421">Cherrim</option><option value="422">Shellos</option><option value="423">Gastrodon</option><option value="424">Ambipom</option><option value="425">Drifloon</option><option value="426">Drifblim</option><option value="427">Buneary</option><option value="428">Lopunny</option><option value="429">Mismagus</option><option value="430">Honchkrow</option><option value="431">Glameow</option><option value="432">Purugly</option><option value="433">Chingling</option><option value="434">Stunky</option><option value="435">Skuntank</option><option value="436">Bronzor</option><option value="437">Bronzong</option><option value="438">Bonsly</option><option value="439">Mime Jr</option><option value="440">Happiny</option><option value="441">Chatot</option><option value="442">Spiritomb</option><option value="443">Gible</option><option value="444">Gabite</option><option value="445">Garchomp</option><option value="446">Munchlax</option><option value="447">Riolu</option><option value="448">Lucario</option><option value="449">Hippopotas</option><option value="450">Hippowdon</option><option value="451">Skorupi</option><option value="452">Drapion</option><option value="453">Croagunk</option><option value="454">Toxicroak</option><option value="455">Carnivine</option><option value="456">Finneon</option><option value="457">Lumineon</option><option value="458">Mantyke</option><option value="459">Snover</option><option value="460">Abomasnow</option><option value="461">Weavile</option><option value="462">Magnezone</option><option value="463">Lickilicky</option><option value="464">Rhyperior</option><option value="465">Tangrowth</option><option value="466">Electivire</option><option value="467">Magmortar</option><option value="468">Togekiss</option><option value="469">Yanmega</option><option value="470">Leafeon</option><option value="471">Glaceon</option><option value="472">Gliscor</option><option value="473">Mamoswine</option><option value="474">Porygon Z</option><option value="475">Gallade</option><option value="476">Probopass</option><option value="477">Dusknoir</option><option value="478">Froslass</option><option value="479">Rotom</option><option value="480">Uxie</option><option value="481">Mesprit</option><option value="482">Azelf</option><option value="483">Dialga</option><option value="484">Palkia</option><option value="485">Heatran</option><option value="486">Regigigas</option><option value="487">Giratina</option><option value="488">Cresselia</option><option value="489">Phione</option><option value="490">Manaphy</option><option value="491">Darkrai</option><option value="492">Shaymin</option><option value="493">Arceus</option><option value="494">Victini</option><option value="495">Snivy</option><option value="496">Servine</option><option value="497">Serperior</option><option value="498">Tepig</option><option value="499">Pignite</option><option value="500">Emboar</option><option value="501">Oshawott</option><option value="502">Dewott</option><option value="503">Samurott</option><option value="504">Patrat</option><option value="505">Watchog</option><option value="506">Lillipup</option><option value="507">Herdier</option><option value="508">Stoutland</option><option value="509">Purrloin</option><option value="510">Liepard</option><option value="511">Pansage</option><option value="512">Simisage</option><option value="513">Pansear</option><option value="514">Simisear</option><option value="515">Panpour</option><option value="516">Simipour</option><option value="517">Munna</option><option value="518">Musharna</option><option value="519">Pidove</option><option value="520">Tranquill</option><option value="521">Unfezant</option><option value="522">Blitzle</option><option value="523">Zebstrika</option><option value="524">Roggenrolla</option><option value="525">Boldore</option><option value="526">Gigalith</option><option value="527">Woobat</option><option value="528">Swoobat</option><option value="529">Drilbur</option><option value="530">Excadrill</option><option value="531">Audino</option><option value="532">Timburr</option><option value="533">Gurdurr</option><option value="534">Conkeldurr</option><option value="535">Tympole</option><option value="536">Palpitoad</option><option value="537">Seismitoad</option><option value="538">Throh</option><option value="539">Sawk</option><option value="540">Sewaddle</option><option value="541">Swadloon</option><option value="542">Leavanny</option><option value="543">Venipede</option><option value="544">Whirlipede</option><option value="545">Scolipede</option><option value="546">Cottonee</option><option value="547">Whimsicott</option><option value="548">Petilil</option><option value="549">Lilligant</option><option value="550">Basculin</option><option value="551">Sandile</option><option value="552">Krokorok</option><option value="553">Krookodile</option><option value="554">Darumaka</option><option value="555">Darmanitan</option><option value="556">Maractus</option><option value="557">Dwebble</option><option value="558">Crustle</option><option value="559">Scraggy</option><option value="560">Scrafty</option><option value="561">Sigilyph</option><option value="562">Yamask</option><option value="563">Cofagrigus</option><option value="564">Tirtouga</option><option value="565">Carracosta</option><option value="566">Archen</option><option value="567">Archeops</option><option value="568">Trubbish</option><option value="569">Garbodor</option><option value="570">Zorua</option><option value="571">Zoroark</option><option value="572">Minccino</option><option value="573">Cinccino</option><option value="574">Gothita</option><option value="575">Gothorita</option><option value="576">Gothitelle</option><option value="577">Solosis</option><option value="578">Duosion</option><option value="579">Reuniclus</option><option value="580">Ducklett</option><option value="581">Swanna</option><option value="582">Vanillite</option><option value="583">Vanillish</option><option value="584">Vanilluxe</option><option value="585">Deerling</option><option value="586">Sawsbuck</option><option value="587">Emolga</option><option value="588">Karrablast</option><option value="589">Escavalier</option><option value="590">Foongus</option><option value="591">Amoonguss</option><option value="592">Frillish</option><option value="593">Jellicent</option><option value="594">Alomomola</option><option value="595">Joltik</option><option value="596">Galvantula</option><option value="597">Ferroseed</option><option value="598">Ferrothorn</option><option value="599">Klink</option><option value="600">Klang</option><option value="601">Klinklang</option><option value="602">Tynamo</option><option value="603">Eelektrik</option><option value="604">Eelektross</option><option value="605">Elgyem</option><option value="606">Beheeyem</option><option value="607">Litwick</option><option value="608">Lampent</option><option value="609">Chandelure</option><option value="610">Axew</option><option value="611">Fraxure</option><option value="612">Haxorus</option><option value="613">Cubchoo</option><option value="614">Beartic</option><option value="615">Cryogonal</option><option value="616">Shelmet</option><option value="617">Accelgor</option><option value="618">Stunfisk</option><option value="619">Mienfoo</option><option value="620">Mienshao</option><option value="621">Druddigon</option><option value="622">Golett</option><option value="623">Golurk</option><option value="624">Pawniard</option><option value="625">Bisharp</option><option value="626">Bouffalant</option><option value="627">Rufflet</option><option value="628">Braviary</option><option value="629">Vullaby</option><option value="630">Mandibuzz</option><option value="631">Heatmor</option><option value="632">Durant</option><option value="633">Deino</option><option value="634">Zweilous</option><option value="635">Hydreigon</option><option value="636">Larvesta</option><option value="637">Volcarona</option><option value="638">Cobalion</option><option value="639">Terrakion</option><option value="640">Virizion</option><option value="641">Tornadus</option><option value="642">Thundurus</option><option value="643">Reshiram</option><option value="644">Zekrom</option><option value="645">Landorus</option><option value="646">Kyurem</option><option value="647">Keldeo</option><option value="648">Meloetta</option><option value="649">Genesect</option><option value="650">Chespin</option><option value="651">Quilladin</option><option value="652">Chesnaught</option><option value="653">Fennekin</option><option value="654">Braixen</option><option value="655">Delphox</option><option value="656">Froakie</option><option value="657">Frogadier</option><option value="658">Greninja</option><option value="659">Bunnelby</option><option value="660">Diggersby</option><option value="661">Fletchling</option><option value="662">Fletchinder</option><option value="663">Talonflame</option><option value="664">Scatterbug</option><option value="665">Spewpa</option><option value="666">Vivillon</option><option value="667">Litleo</option><option value="668">Pyroar</option><option value="669">Flabebe</option><option value="670">Floette</option><option value="671">Florges</option><option value="672">Skiddo</option><option value="673">Gogoat</option><option value="674">Pancham</option><option value="675">Pangoro</option><option value="676">Furfrou</option><option value="677">Espurr</option><option value="678">Meowstic</option><option value="679">Honedge</option><option value="680">Doublade</option><option value="681">Aegislash</option><option value="682">Spritzee</option><option value="683">Aromatisse</option><option value="684">Swirlix</option><option value="685">Slurpuff</option><option value="686">Inkay</option><option value="687">Malamar</option><option value="688">Binacle</option><option value="689">Barbaracle</option><option value="690">Skrelp</option><option value="691">Dragalge</option><option value="692">Clauncher</option><option value="693">Clawitzer</option><option value="694">Helioptile</option><option value="695">Heliolisk</option><option value="696">Tyrunt</option><option value="697">Tyrantrum</option><option value="698">Amaura</option><option value="699">Aurorus</option><option value="700">Sylveon</option><option value="701">Hawlucha</option><option value="702">Dedenne</option><option value="703">Carbink</option><option value="704">Goomy</option><option value="705">Sliggoo</option><option value="706">Goodra</option><option value="707">Klefki</option><option value="708">Phantump</option><option value="709">Trevenant</option><option value="710">Pumpkaboo</option><option value="711">Gourgeist</option><option value="712">Bergmite</option><option value="713">Avalugg</option><option value="714">Noibat</option><option value="715">Noivern</option><option value="716">Xerneas</option><option value="717">Yveltal</option><option value="718">Zygarde</option><option value="719">Diancie</option><option value="720">Hoopa</option><option value="1010">Missing No.</option><option value="2500">Chameleaf</option><option value="2501">Thorneleon</option><option value="2502">Truffeleon</option><option value="2503">Coalla</option><option value="2504">Cindereus</option><option value="2505">Blitzupial</option><option value="2506">Bubbull</option><option value="2507">Buffaflow</option><option value="2508">Watador</option>';
										responce = responce + '</select>';
									responce = responce + '</label>';
								responce = responce + '</p>';
                               responce = responce + ' <p><span class="formLabel small">Type:</span>';
    responce = responce + '<label>';
      responce = responce + '<input type="radio" name="type'+which+'_'+pokeNum+'" id="regular" value="0" />';
      responce = responce + 'Regular</label>';
    responce = responce + '<label>';
      responce = responce + '<input name="type'+which+'_'+pokeNum+'" type="radio" id="shiny" value="1" />';
      responce = responce + 'Shiny</label>';
     responce = responce + '<label>';
     responce = responce + ' <input name="type'+which+'_'+pokeNum+'" type="radio" id="shadow" value="2" /> Shadow</label>';
      responce = responce + '<label><input name="type'+which+'_'+pokeNum+'" type="radio" id="any" value="-1" checked="checked"/> Any</label>';
  responce = responce + '</p>';
   responce = responce + ' <p><span class="formLabel small">Gender:</span>';
      responce = responce + '<label><input name="gender'+which+'_'+pokeNum+'" type="radio" id="male" value="1" />Male</label>';
     responce = responce + '<label><input name="gender'+which+'_'+pokeNum+'" type="radio" id="female" value="2" /> Female</label>';
      responce = responce + '<label><input type="radio" name="gender'+which+'_'+pokeNum+'" id="any" value="-1" checked="checked"/>Any</label>';
  responce = responce + '</p>';
								responce = responce + '<p>';
									responce = responce + '<span class="formLabel small">Level:</span>';
									responce = responce + '<label>';
										responce = responce + '<select id="levelComparison'+which+'_'+pokeNum+'" size="1" name="levelComparison'+which+'_'+pokeNum+'">';
											responce = responce + '<option selected="selected" value="1">=</option>';
											responce = responce + '<option value="2">&lt;=</option>';
											responce = responce + '<option value="3">&gt;=</option>';
											responce = responce + '<option value="4">&lt;</option>';
											responce = responce + '<option value="5">&gt;</option>';
										responce = responce + '</select>';
									responce = responce + '</label>';
									responce = responce + '<label>';
										responce = responce + '<select id="level'+which+'_'+pokeNum+'" size="1" name="level'+which+'_'+pokeNum+'">';
											responce = responce + '<option value="0">Any</option>';
											for (var i=1;i<=100;i++)
											{
												responce = responce + '<option value="'+i+'">'+i+'</option>';
											}
										responce = responce + '</select>';
									responce = responce + '</label>';
								responce = responce + '</p>';
							responce = responce + '</div></div>';
	return responce;
}
function pickupPoke2(id, whichProf) {
	$('pickup_' + id).innerHTML = "Sending to profile...";
	new Ajax.Request(PATH_ROOT + 'acceptPickupAjax2.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('pickup_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('pickup_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickup2(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function resetPickup2(id, whichProf) {
	$('pickup_' + id).innerHTML = '<a href="#pickup'+id+'" id="quickPickup_'+id+'" onClick="pickupPoke2(\''+id+'\', \''+whichProf+'\');return false;">Send To Profile</a> | <a href="#abandon'+id+'" onClick="abandonPoke2(\''+id+'\', \''+whichProf+'\');return false;">Abandon</a>';
}
function abandonPoke2(id, whichProf) {
	$('pickup_' + id).innerHTML = 'Abandon this pokemon forever? <a href="#yes'+id+'" id="confirmAbandonYes_'+id+'" onClick="abandonConfirm2(\''+id+'\', \''+whichProf+'\');return false;">Yes</a> | <a href="#no'+id+'" id="confirmAbandonYes_'+id+'" onClick="resetPickup2(\''+id+'\', \''+whichProf+'\');return false;">No</a>';
	return false;
}
function abandonConfirm2(id, whichProf) {
	$('pickup_' + id).innerHTML = "Pokemon is leaving...";
	new Ajax.Request(PATH_ROOT + 'abandonPickupAjax2.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('pickup_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('pickup_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickup2(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function abandonPokeCreate2(id, whichProf) {
	$('create_' + id).innerHTML = 'Abandon this pokemon forever? <a href="#yes'+id+'" id="confirmAbandonYes_'+id+'" onClick="abandonConfirmCreate2(\''+id+'\', \''+whichProf+'\');return false;">Yes</a> | <a href="#no'+id+'" id="confirmAbandonYes_'+id+'" onClick="resetPickupCreate2(\''+id+'\', \''+whichProf+'\');return false;">No</a>';
	return false;
}
function abandonConfirmCreate2(id, whichProf) {
	$('create_' + id).innerHTML = "Pokemon is leaving...";
	new Ajax.Request(PATH_ROOT + 'abandonCreateAjax2.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('create_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('create_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickupCreate2(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function resetPickupCreate2(id, whichProf) {
	$('create_' + id).innerHTML = '<a href="tradeMeSetup2.php?whichProfile='+whichProf+'&pokeID='+id+'">Trade</a> | <a href="changePokeNickname2.php?whichProfile='+whichProf+'&pokeID='+id+'">Change Nickname</a> | <a href="#abandon'+id+'" onClick="abandonPokeCreate2(\''+id+'\', \''+whichProf+'\');return false;">Abandon</a>';
}
////////////////////////////////////////////////////////////////////////////////////////////// PTD 2 END
function pickupPoke(id, whichProf) {
	$('pickup_' + id).innerHTML = "Sending to profile...";
	new Ajax.Request(PATH_ROOT + 'acceptPickupAjax.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('pickup_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('pickup_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickup(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function abandonPoke(id, whichProf) {
	$('pickup_' + id).innerHTML = 'Abandon this pokemon forever? <a href="#yes'+id+'" id="confirmAbandonYes_'+id+'" onClick="abandonConfirm(\''+id+'\', \''+whichProf+'\');return false;">Yes</a> | <a href="#no'+id+'" id="confirmAbandonYes_'+id+'" onClick="resetPickup(\''+id+'\', \''+whichProf+'\');return false;">No</a>';
	return false;
}
function abandonPokeCreate(id, whichProf) {
	$('create_' + id).innerHTML = 'Abandon this pokemon forever? <a href="#yes'+id+'" id="confirmAbandonYes_'+id+'" onClick="abandonConfirmCreate(\''+id+'\', \''+whichProf+'\');return false;">Yes</a> | <a href="#no'+id+'" id="confirmAbandonYes_'+id+'" onClick="resetPickupCreate(\''+id+'\', \''+whichProf+'\');return false;">No</a>';
	return false;
}
function abandonConfirmCreate(id, whichProf) {
	$('create_' + id).innerHTML = "Pokemon is leaving...";
	new Ajax.Request(PATH_ROOT + 'abandonCreateAjax.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('create_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('create_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickupCreate(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function abandonConfirm(id, whichProf) {
	$('pickup_' + id).innerHTML = "Pokemon is leaving...";
	new Ajax.Request(PATH_ROOT + 'abandonPickupAjax.php', {
			method: 'get',
			parameters: {pokeID: id, whichProfile:whichProf},
			onSuccess: function(transport) {
				if (transport.status == 200) {
					$('pickup_' + id).innerHTML = transport.responseText;
				}
			},
			onFailure: function(){
				$('pickup_' + id).innerHTML = 'Error, <a href="#" id="resetPickup_'+id+'" onClick="resetPickup(\''+id+'\', \''+whichProf+'\');return false;">try again</a>..';
			}
		});
	return false;
}
function resetPickupCreate(id, whichProf) {
	$('create_' + id).innerHTML = '<a href="tradeMeSetup.php?whichProfile='+whichProf+'&pokeID='+id+'">Trade</a> | <a href="changePokeNickname.php?whichProfile='+whichProf+'&pokeID='+id+'">Change Nickname</a> | <a href="#abandon'+id+'" onClick="abandonPokeCreate(\''+id+'\', \''+whichProf+'\');return false;">Abandon</a>';
}
function resetPickup(id, whichProf) {
	$('pickup_' + id).innerHTML = '<a href="#pickup'+id+'" id="quickPickup_'+id+'" onClick="pickupPoke(\''+id+'\', \''+whichProf+'\');return false;">Send To Profile</a> | <a href="#abandon'+id+'" onClick="abandonPoke(\''+id+'\', \''+whichProf+'\');return false;">Abandon</a>';
}
function closePickup(id) {
	$('pickupBox_' + id).innerHTML = "";
	return false;
}