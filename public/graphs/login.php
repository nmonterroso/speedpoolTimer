<?php
require_once(dirname(__FILE__)."/_graphSetup.php");
LoginManager::enforceAnonymous();
PageContent::header("Login");

if ($_POST['login'] && ($user = LoginManager::verifyLogin($_POST['username'], $_POST['userpass']))) {
	LoginManager::setUser($user);
	header("Location: /graphs");
}

echo "
<form method='post'>
	<table>
		<tr>
			<td>Username:</td>
			<td><input type='text' name='username' /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type='password' name='userpass' /></td>
		</tr>
		<tr>
			<td colspan='2' align='right'>
				<input type='submit' name='login' value='Login' />
			</td>
		</tr>
	</table>
</form>
";