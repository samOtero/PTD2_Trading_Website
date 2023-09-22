<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PTD3 Trading Center - Create Trade</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<link rel="stylesheet" href="../css/ptd3/style.css">
</head>

<body style="padding-bottom:50px">
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="http://www.ptdtrading.com/trading.php">PTD 3: Trading Center</a> </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a href="http://samdangames.blogspot.com/">Blog</a></li>
        <li><a href="main.php">Home</a></li>
        <li class=""dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Trading <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="createTrade.php">Create Trade</a></li>
            <li><a href="#">Your Trade Request</a></li>
            <li><a href="#">Search Trades</a></li>
            <li><a href="#">Latest Trades</a></li>
          </ul>
        </li>
      </ul>
      <p class="navbar-text navbar-right"><img src="http://www.ptdtrading.com/trading_center/avatar/b_-1_2.png"> Gold - Trainer ID: 1009033</p>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class = "col-sm-12"  style="padding-top: 70px">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">Create Trade</h4>
            </div>
            <div class = "panel-body">
                <p>Here is a list of your pokemon from this profile, click on "Trade" to create a new Trade.
    <p>NOTE: This will remove the pokemon from your profile. To get him back to your profile go back to the "Your Trade Request" page and call your pokemon back.</p>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
        <div class = "col-sm-12">
            <div class="alert alert-success" role="alert">Your Pokémon on this profile <span class="label label-success">100</span></div>
      </div>
     <div>
     <?php echo block_poke_create_trade(20, "", "Reg") ?>
  <?php echo block_poke_create_trade(1, "panel-shiny", "Shny") ?>
  <?php echo block_poke_create_trade(2, "panel-shadow", "Shdw") ?>
  <?php echo block_poke_create_trade(3, "panel-grass", "Grss") ?>
  <?php echo block_poke_create_trade(4, "panel-poison", "Psn") ?>
  <?php echo block_poke_create_trade(5, "panel-water", "Wtr") ?>
  <?php echo block_poke_create_trade(6, "panel-fire", "Fire") ?>
  <?php echo block_poke_create_trade(7, "panel-normal", "Nrml") ?>
  <?php echo block_poke_create_trade(8, "panel-bug", "Bug") ?>
  <?php echo block_poke_create_trade(9, "panel-ghost" ,"Ghst") ?>
  <?php echo block_poke_create_trade(10, "panel-steel", "Stl") ?>
  <?php echo block_poke_create_trade(11, "panel-rock", "Rock") ?>
  <?php echo block_poke_create_trade(12, "panel-electric", "Elec") ?>
  <?php echo block_poke_create_trade(13, "panel-ice", "Ice") ?>
  <?php echo block_poke_create_trade(14, "panel-fight", "Fght") ?>
  <?php echo block_poke_create_trade(15, "panel-ground", "Grnd") ?>
  <?php echo block_poke_create_trade(16, "panel-dragon", "Drgn") ?>
  <?php echo block_poke_create_trade(17, "panel-dark", "Dark") ?>
  <?php echo block_poke_create_trade(18, "panel-physic", "Psyc") ?>
  <?php echo block_poke_create_trade(19, "panel-fairy", "Fairy") ?>
  <?php
  	function block_poke_create_trade($id, $panelType="", $extraName="Elem") {
  ?>
  	<div class="pokeblock panel-group col-lg-3 col-md-4 col-sm-6">
    <div class = "panel panel-primary <?php echo $panelType ?>">
    	<div class = "panel-heading" data-toggle="collapse" data-target="#collapse<?php echo $id ?>" style="cursor: pointer">
        <img src="http://www.ptdtrading.com/games/ptd/small/1_0.png">Bulbasaur<img src="http://www.ptdtrading.com/trading_center/images/male.png"><img src="http://www.ptdtrading.com/images/ribbon_smaller.png">  <span class="label label-success"><?php echo $extraName ?></span> <span class="label label-danger">H</span> <span class="pull-right">Lvl 100</span>
        </div>
        <div id="collapse<?php echo $id ?>" class = "panel-body panel-collapse collapse">
            <div class = "row">
                <div class = "col-xs-7 text-center">
                    <h4>
                        	<p><span class="label label-primary">Tackle</span> <span class="label label-primary">Leech Seed</span></p>
                        	<p><span class="label label-primary">Growl</span> <span class="label label-primary">Vine Whip</span></p>
                        <p><span class="label label-warning">None</span></p>
                    </h4>
                </div>
                <div class = "col-xs-5 text-center" >
                	<div class="btn-group-vertical btn-group-sm" role="group" aria-label="...">
                    	<button type="button" class="btn btn-primary">Trade</button>
                        <button data-toggle="collapse" data-target="#collapse3_<?php echo $id ?>" type="button" class="btn btn-primary">Nickname</button>
                        <button data-toggle="collapse" data-target="#collapse2_<?php echo $id ?>" type="button" class="btn btn-danger">Abandon</button>
                    </div>
                </div>
            </div>
        </div>
        
         <!-- NICKNAME PANEL -->
        <div id="collapse3_<?php echo $id ?>" class = "panel-body panel-collapse collapse">
            <div class = "row">
                <div id="nicknameContent_<?php echo $id ?>" class = "col-xs-12 text-center">
                    <div class="input-group">
                    	<input type="text" class="form-control" placeholder="New Nickname...">
                        <span class="input-group-btn">
                        	<button class="btn btn-success" type="button">Change</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END NICKNAME PANEL -->
        
        <!-- ABANDON PANEL -->
        <div id="collapse2_<?php echo $id ?>" class = "panel-body panel-collapse collapse">
            <div class = "row">
                <div id="abandonContent_<?php echo $id ?>" class = "col-xs-12 text-center">
                    Abandon this Pokémon forever?<br/>
                    <button type="button" class="btn btn-success btn-md" data-toggle="collapse" data-target="#collapse2_<?php echo $id ?>">No</button>
                    <button type="button" class="btn btn-danger btn-md">Yes</button>
                       
                </div>
            </div>
        </div>
        <!-- END ABANDON PANEL -->
        
    </div>
  </div>
  <?
    }
  ?>
</div>
<!-- /.container --> 
<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> 
<!-- Latest compiled and minified JavaScript --> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>