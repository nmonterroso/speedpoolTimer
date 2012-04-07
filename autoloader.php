<?php
spl_autoload_register(function($className) {
	$directories = array(
		"util",
		"classes",
	);

	foreach ($directories as $dir) {
		$classPath = dirname(__FILE__)."/{$dir}/{$className}.class.php";
		if (file_exists($classPath)) {
			require_once($classPath);
			break;
		}
	}
});