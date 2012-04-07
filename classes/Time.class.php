<?php
class Time {
	private $date;
	private $time;
	private $penalties;

	public function __construct($date, $time, $penalties) {
		$this->date = $date;
		$this->time = $time;
		$this->penalties = $penalties;
	}

	public function getDate() {
		return $this->date;
	}

	public function getTime() {
		return $this->time;
	}

	public function getPenalties() {
		return $this->penalties;
	}
}
