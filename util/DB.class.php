<?php
require_once(dirname(__FILE__)."/../db.inc.php");

class DB {
	/**
	 * @var mysqli
	 */
	private $db;

	const TYPE_INVALID = null;
	const TYPE_INT = "%d";
	const TYPE_STRING = "%s";

	private function __construct() {
		$this->db = new mysqli(DB_URL, DB_USER, DB_PASS, DB_DBNAME);
	}

	/**
	 * @param $sql
	 * @return mysqli_result;
	 */
	public function query($sql) {
		$params = func_get_args();
		array_shift($params);

		while (count($params) > 0 && ($placeholder = $this->getNextPlaceholder($sql)) != self::TYPE_INVALID) {
			$nextParam = array_shift($params);
			switch ($placeholder) {
				case self::TYPE_INT:
					$replacement = (int) $nextParam;
					break;
				case self::TYPE_STRING:
					$replacement = "'".$this->db->real_escape_string($nextParam)."'";
					break;
				default:
					return null;
			}

			$pattern = "/{$placeholder}/";
			$sql = preg_replace($pattern, $replacement, $sql, 1);
		}

		return $this->db->query($sql);
	}

	public function lastId() {
		return $this->db->insert_id;
	}

	public function startTransaction() {
		$this->db->autocommit(false);
	}

	private function endTransaction() {
		$this->db->autocommit(true);
	}

	public function commit() {
		$this->db->commit();
		$this->endTransaction();
	}

	public function rollback() {
		$this->db->rollback();
		$this->endTransaction();
	}

	private function getNextPlaceholder($sql) {
		$nextPlaceholder = strpos($sql, "%");
		if ($nextPlaceholder === false) {
			return false;
		}

		$type = self::TYPE_INVALID;
		switch (substr($sql, $nextPlaceholder+1, 1)) {
			case 'd':
				$type = self::TYPE_INT;
				break;
			case 's':
				$type = self::TYPE_STRING;
				break;
		}

		return $type;
	}

	/**
	 * @static
	 * @return DB
	 */
	public static function get() {
		static $db = null;

		if ($db === null) {
			$db = new DB();
		}

		return $db;
	}
}
