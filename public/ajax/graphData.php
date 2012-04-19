<?php
require_once(dirname(__FILE__)."/_ajaxSetup.php");
$user = LoginManager::enforceLogin("graphs/login.php");

$data = json_decode($_POST['data']);
if (empty($data->players) || !property_exists($data, 'since') || !property_exists($data, 'until')) {
	PageContent::ajaxError("missing parameters");
}

$graphs = array();
foreach ($data->players as $pid) {
	$player = Player::get($pid);
	$times = $player->times($data->since, $data->until);
	$totalTime = 0;
	$totalPenalties = 0;
	$games = array();
	foreach ($times as $time) {
		/** @var $time Time */

		$totalTime += $time->time();
		$game = array(
			'time'			=> $time->time(),
			'date'			=> $time->date(),
			'penalties'	=> array(),
		);

		foreach ($time->penalties() as $penalty) {
			/** @var $penalty Penalty */

			++$totalPenalties;
			$game['penalties'] = array(
				'time'					=> $penalty->time(),
				'penaltyAmount'	=> $penalty->penaltyAmount(),
			);
		}

		$games[] = $game;
	}

	$average = count($games) == 0 ? 0 : floor($totalTime / count($games));
	$graphs[$player->pid()] = array(
		'games'					=> $games,
		'numGames'			=> count($games),
		'average'				=> $average,
		"numPenalties"	=> $totalPenalties,
	);
}

PageContent::ajax(array('graphs' => $graphs));