<?php
LoginManager::enforceAnonymous();
PageContent::header("Login");

echo "
<form method='post' action='/processLogin.php'>
	<div data-role='fieldcontain'>
		<label for='username'>Username:</label>
		<input type='text' name='username' id='username' />
	</div>

	<div data-role='fieldcontain'>
		<label for='userpass'>Password:</label>
		<input type='password' name='userpass' id='userpass' />
	</div>

	<input type='submit' name='login' value='Login' />
</form>
";