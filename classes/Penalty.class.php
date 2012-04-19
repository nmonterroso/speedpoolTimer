<?php
class Penalty {
	private $time;
	private $penaltyAmount;

	public function __construct($time, $penaltyAmount) {
		$this->time = $time;
		$this->penaltyAmount = $penaltyAmount;
	}

	public function save($tid) {
		$sql = "INSERT INTO `penalties`
						SET `tid`=%d, `time`=%d, `penaltyAmount`=%d";
		return DB::get()->query($sql, $tid, $this->time, $this->penaltyAmount);
	}

	public function penaltyAmount() {
		return $this->penaltyAmount;
	}

	public function time() {
		return $this->time;
	}
}
