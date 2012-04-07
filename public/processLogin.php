<?php
LoginManager::enforceAnonymous();

if ($user = LoginManager::verifyLogin($_POST['username'], $_POST['userpass'])) {
	LoginManager::setUser($user);
	header("Location: /");
} else {
	header("Location: /login.php?failed=1");
}