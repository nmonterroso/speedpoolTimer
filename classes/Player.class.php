<?php
class Player {
	private $name;
	private $uid;
	private $pid;
	private $times = null;

	const CLASS_NAME = "Player";

	public function __construct($uid, $name) {
		$this->uid = $uid;
		$this->name = $name;
	}

	/**
	 * @static
	 * @param $uid
	 * @return array
	 */
	public static function getPlayers($uid) {
		$players = array();

		$sql = "SELECT `pid`, `name`, `uid`
						FROM `players`
						WHERE `uid`=%d
						ORDER BY `name`";
		$res = DB::get()->query($sql, $uid);
		while ($p = $res->fetch_object()) {
			$player = new Player($p->uid, $p->name);
			$player->setPid($p->pid);
			$players[] = $player;
		}

		return $players;
	}

	public function save() {

	}

	public function getName() {
		return $this->name;
	}

	public function setPid($pid) {
		$this->pid = $pid;
	}

	public function getPid() {
		return $this->pid;
	}

	public function getTimes() {
		if ($this->times == null) {

		}

		return $this->times;
	}

	public function getUid() {
		return $this->uid;
	}
}
