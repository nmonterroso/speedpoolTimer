<?php
class Time {
	private $tid;
	private $date;
	private $time;
	private $penalties;
	private $player;

	public function __construct(Player $player, $time, array $penalties=null) {
		$this->date = time();
		$this->player = $player;
		$this->time = $time;

		if ($penalties != null) {
			$this->penalties = array();
			foreach ($penalties as $penalty) {
				$this->penalties[] = new Penalty($penalty->time, $penalty->penaltyAmount);
			}
		}
	}

	public function save() {
		DB::get()->startTransaction();

		$sql = "INSERT INTO `times`
						SET `pid`=%d, `date`=%d, `time`=%d";
		if (!DB::get()->query($sql, $this->player->pid(), $this->date, $this->time)) {
			return false;
		}

		$this->tid = DB::get()->lastId();
		foreach ($this->penalties as $penalty) {
			/** @var $penalty Penalty */
			if (!$penalty->save($this->tid)) {
				DB::get()->rollback();
				return false;
			}
		}

		DB::get()->commit();
		return true;
	}

	public function player() {
		return $this->player;
	}

	public function time() {
		return $this->time;
	}

	public function penalties() {
		if ($this->penalties === null && $this->tid !== null) {
			$this->penalties = array();
			$sql = "SELECT `time`, `penaltyAmount`
							FROM `penalties`
							WHERE `tid`=%d
							ORDER BY `time` ASC";
			$res = DB::get()->query($sql, $this->tid);
			while ($p = $res->fetch_object()) {
				$penalty = new Penalty($p->time, $p->penaltyAmount);
				$this->penalties[] = $penalty;
			}
		}

		return $this->penalties;
	}

	public function date($date = null) {
		if ($date !== null) {
			$this->date = $date;
		}

		return $this->date;
	}

	public function tid($tid = null) {
		if ($tid !== null) {
			$this->tid = $tid;
		}

		return $this->tid;
	}
}