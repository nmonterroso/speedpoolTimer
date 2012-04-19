<?php
class Player {
	private $name;
	private $uid;
	private $pid;

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

	/**
	 * @static
	 * @param $pid
	 * @return Player
	 */
	public static function get($pid) {
		$player = null;

		$sql = "SELECT `pid`, `name`, `uid`
						FROM `players`
						WHERE `pid`=%d";
		$res = DB::get()->query($sql, $pid);
		if ($p = $res->fetch_object()) {
			$player = new Player($p->uid, $p->name, $p->pid);
		}

		return $player;
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

	public function name() {
		return $this->name;
	}

	public function pid() {
		return $this->pid;
	}

	public function times($since=0, $until=0) { // maybe i should add some caching layer here
		if ($until < $since) {
			return array();
		}

		$times = array();
		$params = array($this->pid());
		$sql = "SELECT `tid`, `date`, `time`
						FROM `times`
						WHERE `pid`=%d";
		if ($since > 0) {
			$sql .= " AND `date` > %d";
			$params[] = $since;
		}

		if ($until > 0) {
			$sql .= " AND `date` < %d";
			$params[] = $until;
		}

		$sql .= " ORDER BY `date` ASC";

		$res = DB::get()->query($sql, $params);
		while ($t = $res->fetch_object()) {
			$time = new Time($this, $t->time);
			$time->tid($t->tid);
			$time->date($t->date);
			$times[] = $time;
		}

		return $times;
	}

	public function uid() {
		return $this->uid;
	}
}
