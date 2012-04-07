<?php
class User {
	private $uid;
	private $name;

	const CLASS_NAME = "User";

	public function __construct($uid, $name) {
		$this->uid = $uid;
		$this->name = $name;
	}

	/**
	 * @static
	 * @param $name
	 * @param $pass
	 * @return User
	 */
	public static function load($name, $pass) {
		$sql = "SELECT `uid`, `name`
						FROM `users`
						WHERE `name`=%s
							AND `pass`=%s";
		$res = DB::get()->query($sql, trim($name), md5(trim($pass)));
		if ($res->num_rows == 1) {
			$user = $res->fetch_object();
			return new User($user->uid, $user->name);
		}

		return null;
	}

	public function getName() {
		return $this->name;
	}

	public function getUid() {
		return $this->uid;
	}
}