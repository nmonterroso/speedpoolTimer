<?php
class PageContent {
	public static function getPage($script) {
		$script = dirname(__FILE__)."/../public".$script;
		$output = "";

		if (file_exists($script)) {
			ob_start();
			require_once($script);
			$output = ob_get_contents();
			ob_end_clean();
		}

		return $output;
	}

	public static function ajax(array $data=array(), $error=false) {
		exit(json_encode(array_merge(array(
			"error"	=> $error,
		), $data)));
	}

	public static function ajaxError($message) {
		self::ajax(array(
			"message"	=> $message,
		), true);
	}

	public static function addStatic($name) {
		self::addCss($name);
		self::addJs($name);
	}

	public static function addJs($js=null) {
		static $includes = array();

		if ($js !== null) {
			$includes[] = "{$js}.js";
		}

		return $includes;
	}

	public static function addCss($css=null) {
		static $includes = array();

		if ($css !== null) {
			$includes[] = "{$css}.css";
		}

		return $includes;
	}

	public static function getJs($script) {
		$jsFile = substr(preg_replace('/\.php$/', '.js', $script), 1);
		$jsIncludes = self::addJs();
		if (file_exists(dirname(__FILE__)."/../public/js/".$jsFile)) {
			$jsIncludes[] = $jsFile;
		}

		$jsIncludeHtml = "";
		foreach ($jsIncludes as $js) {
			$jsIncludeHtml .= "<script type='text/javascript' src='/js/{$js}'></script>";
		}

		return $jsIncludeHtml;
	}

	public static function getCss($script) {
		$cssFile = substr(preg_replace('/\.php$/', '.css', $script), 1);
		$cssIncludes = self::addCss();
		if (file_exists(dirname(__FILE__)."/../public/css/".$cssFile)) {
			$cssIncludes[] = $cssFile;
		}

		$cssIncludeHtml = "";
		foreach ($cssIncludes as $js) {
			$cssIncludeHtml .= "<link rel='stylesheet' href='/css/{$js}'></script>";
		}

		return $cssIncludeHtml;
	}

	public static function header($newHeader=null) {
		static $header = "Speed Pool";

		if ($newHeader !== null) {
			$header = $newHeader;
		}

		return $header;
	}
}
