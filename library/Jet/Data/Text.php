<?php
/**
 *
 *
 *
 * Texts manipulation class
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Data
 * @subpackage Data_Text
 */
namespace Jet;

class Data_Text {
	
	/**
	 * Removes accents from text
	 * 
	 * @param string $text
	 * @return string
	 */
	public static function removeAccents( $text ){
		/** @noinspection PhpUndefinedClassInspection */
		/** @noinspection PhpVoidFunctionResultUsedInspection */
		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection SpellCheckingInspection */
		$myTrans = \Transliterator::create("NFD; [:Nonspacing Mark:] Remove; NFC");
		/** @noinspection PhpUndefinedMethodInspection */
		return $myTrans->transliterate( $text );
	}
	
	/**
	 *
	 * @param string $text
	 * @param int $max_length
	 * @param bool $add_dots (optional, default: true)
	 * @param $dots (optional, default: ...)
	 * @return string 
	 */
	public static function shorten(
					$text,
					$max_length,
					$add_dots = true,
					$dots = "..."
				) {

		$text = trim($text);

		if( $add_dots ) {
			$max_length = $max_length - strlen($dots);
		}

		if(!preg_match("~^(.{0,$max_length})~us", $text, $match)){
			return $text;
		}

		$shortened = $match[1];

		if( strlen($text)===strlen($shortened) ){
			return $text;
		}

		$shortened = preg_replace("~( [^ ]*)$~us", "", $shortened);
		if ($add_dots) {
			$shortened .= $dots;
		}
		return $shortened;
	}
	
	
	/**
	 * Replace data in text by given values
	 *
	 * Example:
	 * $text = array("PARAM1" => "value 1", "PARAM2" => "value 2")
	 * replaces %PARAM1% for value 1 and %PARAM2% for value 2
	 * 
	 * @param string $text
	 * @param array $data
	 * @return string
	 */
	public static function replaceData( $text, array $data ){

		$replace_keys = array_keys($data);
		foreach($replace_keys as $i=>$v) {
			$replace_keys[$i] = "%{$v}%";
		}
		$replace_values = array_values( $data );

		return str_replace($replace_keys, $replace_values, $text);
	}

	/**
	 * Search all %JET_*% constants in text and replace it by values
	 *
	 * Example:
	 * <code>
	 * $text = "My temp path is %JET_TMP_PATH%";
	 * $output = Data_Text::replaceConstants($text); // %JET_TMP_PATH% will be replaced by real path to [ROAD_root]/tmp/
	 * </code>
	 *
	 * @param string $input
	 *
	 * @param array $default_replacement (optional; default: array())
	 *
	 * @return string
	 */
	public static function replaceSystemConstants( $input, array $default_replacement=array() ) {
		$data = array(
			"JET_BASE_PATH" => JET_BASE_PATH,
			"JET_DATA_PATH" => JET_DATA_PATH,
			"JET_LIBRARY_PATH" => JET_LIBRARY_PATH,
			"JET_LOGS_PATH" => JET_LOGS_PATH,
			"JET_TMP_PATH" => JET_TMP_PATH,
			"JET_APPLICATION_PATH" => JET_APPLICATION_PATH,
			"JET_APPLICATION_ERROR_PAGES_PATH" => JET_APPLICATION_ERROR_PAGES_PATH,
			"JET_APPLICATION_CONFIG_PATH" => JET_APPLICATION_CONFIG_PATH,
			"JET_APPLICATION_MODULES_PATH" => JET_APPLICATION_MODULES_PATH,
			"JET_APPLICATION_SITES_PATH" => JET_APPLICATION_SITES_PATH,
			"JET_TEMPLATES_PATH" => JET_TEMPLATES_PATH,
			"JET_TEMPLATES_SITES_PATH" => JET_TEMPLATES_SITES_PATH,
			"JET_TEMPLATES_MODULES_PATH" => JET_TEMPLATES_MODULES_PATH,
			"JET_PUBLIC_PATH" => JET_PUBLIC_PATH,
			"JET_PUBLIC_IMAGES_PATH" => JET_PUBLIC_IMAGES_PATH,
			"JET_PUBLIC_SCRIPTS_PATH" => JET_PUBLIC_SCRIPTS_PATH,
			"JET_PUBLIC_STYLES_PATH" => JET_PUBLIC_STYLES_PATH,
			"JET_PUBLIC_LIBS_PATH" => JET_PUBLIC_LIBS_PATH,
			"JET_BASE_URI" => JET_BASE_URI,
			"JET_MODULES_URI" => JET_MODULES_URI,
			"JET_PUBLIC_URI" => JET_PUBLIC_URI,
			"JET_SITES_URI" => JET_SITES_URI,
			"JET_PUBLIC_FILES_URI" => JET_PUBLIC_FILES_URI,
			"JET_PUBLIC_DATA_URI" => JET_PUBLIC_DATA_URI,
			"JET_PUBLIC_IMAGES_URI" => JET_PUBLIC_IMAGES_URI,
			"JET_PUBLIC_SCRIPTS_URI" => JET_PUBLIC_SCRIPTS_URI,
			"JET_PUBLIC_STYLES_URI" => JET_PUBLIC_STYLES_URI,
		);


		return static::replaceData($input, array_merge($data, $default_replacement));
	}

}