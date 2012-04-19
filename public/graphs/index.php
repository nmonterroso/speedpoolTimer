<?php
require_once(dirname(__FILE__)."/_graphSetup.php");
$user = LoginManager::enforceLogin("graphs/login.php");

$players = "<div class='hidden'>";
foreach (Player::getPlayers($user->uid()) as $player) {
	/** @var $player Player */
	$players .= "
		<div class='player'>
			<span class='id'>".$player->pid()."</span>
			<span class='name'>".$player->name()."</span>
		</div>
	";
}
$players .= "</div>";

echo "
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
	'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
	<title>GRAPHS, YO</title>
	<link rel='stylesheet' href='/css/style.css' />
	<link rel='stylesheet' href='/css/graphs.css' />
	<link rel='stylesheet' href='/css/ui-lightness/jquery-ui-1.8.19.custom.css' />
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.7.1.min.js'></script>
	<script type='text/javascript' src='/js/jquery-ui-1.8.19.custom.min.js'></script>
	<script type='text/javascript' src='/js/utility.js'></script>
	<script type='text/javascript' src='/js/graphs.js'></script>
</head>
<body>

<div class='options'>
	<select id='players' multiple='multiple'></select>
</div>
<div class='options'>
	<table>
		<tr>
			<td>Since:</td>
			<td><input type='text' id='timeSince' /></td>
		</tr>
		<tr>
			<td>Until:</td>
			<td><input type='text' id='timeUntil' /></td>
		</tr>
	</table>
</div>
<div class='clear'></div>
<button id='viewGraphs'>View</button>
<div id='graphContainer' class='hidden'>
	<h2>Graphs</h2>
	<div id='graphData'></div>
</div>

$players
</body>
</html>
";