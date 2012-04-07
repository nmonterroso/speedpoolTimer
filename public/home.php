<?php
LoginManager::enforceLogin();

$players = Player::getPlayers(LoginManager::getUser()->getUid());
$emptyList = empty($players);
$options = "
	<option>Select Player</option>
	<option value='0'>Create New</option>
";

foreach ($players as $player) {
	/** @var $player Player */
	$options .= "<option value='".$player->getPid()."'>".$player->getName()."</option>";
}

echo "
	<select id='playerSelect'>
		$options
	</select>
";