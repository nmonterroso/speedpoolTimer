<?php
class LoginManager {
	/**
	 * @static
	 * @param $user
	 * @param $pass
	 * @return User
	 */
	public static function verifyLogin($user, $pass) {
		return User::load($user, $pass);
	}

	/**
	 * @static
	 * @return User
	 */
	public static function getUser() {
		return isset($_SESSION['user']) ? $_SESSION['user'] : false;
	}

	public static function setUser(User $user) {
		$_SESSION['user'] = $user;
	}

	/**
	 * @static
	 * @return User
	 */
	public static function enforceLogin() {
		if (!self::getUser()) {
			header("Location: /login.php");
			exit();
		}

		return self::getUser();
	}

	public static function enforceAnonymous() {
		if (self::getUser()) {
			header("Location: /");
			exit();
		}
	}
}
