<?php
require_once(dirname(__FILE__)."/_ajaxSetup.php");
$user = LoginManager::enforceLogin();

$data = json_decode($_POST['data']);
if (!isset($data->player) || !isset($data->time) || !isset($data->penalties)) {
	PageContent::ajaxError("missing parameters");
}

$player = Player::get($data->player->pid);

if (!$player) {
	PageContent::ajaxError("No pid found for player {$data->player->name}");
}

$time = new Time($player, $data->time, $data->penalties);
if (!$time->save()) {
	PageContent::ajaxError("error while saving");
}

PageContent::ajax();