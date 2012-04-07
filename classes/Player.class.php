<?php
class Player {
	private $name;
	private $uid;
	private $pid;
	private $times = null;

	public function __construct($uid, $name, $pid=false) {
		$this->uid = $uid;
		$this->name = $name;
		if ($pid) {
			$this->pid = $pid;
		}
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
			$player = new Player($p->uid, $p->name, $p->pid);
			$players[] = $player;
		}

		return $players;
	}

	public function save() {
		$sql = "INSERT INTO `players`
						SET `name`=%s,
								`uid`=%d";
		if (!DB::get()->query($sql, $this->name, $this->uid)) {
			return false;
		}

		$this->pid = DB::get()->lastId();
		return true;
	}

	public function getName() {
		return $this->name;
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
