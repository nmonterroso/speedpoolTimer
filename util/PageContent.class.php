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

	public static function addJs($js) {
		if (!isset($_GLOBALS['jsIncludes'])) {
			$_GLOBALS['jsIncludes'] = array();
		}

		$_GLOBALS['jsIncludes'][] = $js;
	}

	public static function addCss($css) {
		if (!isset($_GLOBALS['cssIncludes'])) {
			$_GLOBALS['cssIncludes'] = array();
		}

		$_GLOBALS['cssIncludes'][] = $css;
	}

	public static function getJs($script) {
		$jsFile = substr(preg_replace('/\.php$/', '.js', $script), 1);
		$jsIncludes = isset($_GLOBALS['jsIncludes']) ? $_GLOBALS['jsIncludes'] : array();
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
		$cssIncludes = isset($_GLOBALS['cssIncludes']) ? $_GLOBALS['cssIncludes'] : array();
		if (file_exists(dirname(__FILE__)."/../public/css/".$cssFile)) {
			$cssIncludes[] = $cssFile;
		}

		$cssIncludeHtml = "";
		foreach ($cssIncludes as $js) {
			$cssIncludeHtml .= "<script type='text/javascript' src='/js/{$js}'></script>";
		}

		return $cssIncludeHtml;
	}
}
