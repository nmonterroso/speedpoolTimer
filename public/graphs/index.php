<?php
require_once(dirname(__FILE__)."/_graphSetup.php");
$user = LoginManager::enforceLogin("graphs/login.php");
$players = Player::getPlayers($user->uid());

$playerSelect = "<select id='players' multiple='multiple'>";
foreach ($players as $player) {
	/** @var $player Player */
	$playerSelect .= "<option value='".$player->pid()."'>".$player->name()."</option>";
}
$playerSelect .= "</select>";

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
	<script type='text/javascript' src='/js/graphs.js'></script>
</head>
<body>

<div class='options'>
	$playerSelect
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

</body>
</html>
";