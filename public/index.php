<?php
require_once(dirname(__FILE__)."/../autoloader.php");
session_start();
$script = $_SERVER['REQUEST_URI'];

if (!LoginManager::getUser() && $script != "/processLogin.php") {
	$script = "/login.php";
}

if ($script == '/') {
	$script = '/home.php';
}

$pageContent = PageContent::getPage($script);
$jsInclude = PageContent::getJs($script);
$cssInclude = PageContent::getCss($script);
$title = PageContent::header();

echo "
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
	'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
	<title>SPEED POOL</title>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css' />
	<link rel='stylesheet' href='/css/simpledialog.css' />
	<link rel='stylesheet' href='/css/style.css' />
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.7.1.min.js'></script>
	<script type='text/javascript' src='/js/mobileinit.js'></script>
	<script type='text/javascript' src='http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js'></script>
	<script type='text/javascript' src='/js/simpledialog2.js'></script>
	<script type='text/javascript' src='/js/utility.js'></script>
</head>
<body>

<div data-role='page' id='page'>
	{$cssInclude}
	{$jsInclude}
	<div data-role='header'>
		<h1>{$title}</h1>
	</div>
	<div data-role='content'>
		$pageContent
	</div>
</div>

</body>
</html>
";