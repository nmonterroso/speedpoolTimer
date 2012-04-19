<?php
$user = LoginManager::enforceLogin();
PageContent::addStatic("timer");

$options = "
	<option value='-1' data-placeholder='true'>Select Player</option>
	<option value='0'>Create New</option>
";

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
	<select id='playerSelect'>
		$options
	</select>
	$players
	<div id='play'>
		<div id='timerContainer'>
			<div id='timer'>00:00.000</div>
		</div>
		<fieldset class='ui-grid-a'>
			<div class='ui-block-a'><button data-mini='true' id='timerReset'>Reset</button></div>
			<div class='ui-block-b'><button data-mini='true' id='timerPenalty'>Penalty</button></div>
		</fieldset>
		<button data-mini='true' id='timerToggle'>Start</button>
	</div>
";