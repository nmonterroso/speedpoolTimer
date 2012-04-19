<?php
require_once(dirname(__FILE__)."/_ajaxSetup.php");
$user = LoginManager::enforceLogin();

$p = $_POST;
if (!$p['name']) {
	PageContent::ajaxError("Missing Name");
}

$player = new Player($user->uid(), $p['name']);
if (!$player->save()) {
	PageContent::ajaxError("Couldn't save new player. Does that player already exist?");
}

PageContent::ajax(array(
	"pid"	=> $player->pid()
));