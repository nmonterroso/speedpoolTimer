<?php
class Time {
	private $tid;
	private $date;
	private $time;
	private $penalties;
	private $player;

	public function __construct(Player $player, $time, array $penalties) {
		$this->date = time();
		$this->player = $player;
		$this->time = $time;

		$this->penalties = array();
		foreach ($penalties as $penalty) {
			$this->penalties[] = new Penalty($penalty->time, $penalty->penaltyAmount);
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
}