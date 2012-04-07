<?php
class Time {
	private $date;
	private $time;

	public function __construct($date, $time) {
		$this->setDate($date);
		$this->setTime($time);
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public function getDate() {
		return $this->date;
	}

	public function setTime($time) {
		$this->time = $time;
	}

	public function getTime() {
		return $this->time;
	}
}
