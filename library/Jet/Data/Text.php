<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Data_Text
{

	/**
	 * @var array
	 */
	protected static $_defined_constants = [];

	/**
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function removeAccents( $text )
	{

		if( !class_exists( '\Transliterator', false ) ) {
			$text = iconv( JET_CHARSET, 'ASCII//TRANSLIT', $text );

			return preg_replace( '/[^a-zA-Z0-9]/', '_', $text );
		} else {
			/** @noinspection PhpUndefinedClassInspection */
			/** @noinspection PhpVoidFunctionResultUsedInspection */
			/** @noinspection PhpUndefinedMethodInspection */
			/** @noinspection SpellCheckingInspection */
			$transliterator = \Transliterator::create( 'NFD; [:Nonspacing Mark:] Remove; NFC' );

			/** @noinspection PhpUndefinedMethodInspection */
			return $transliterator->transliterate( $text );

		}
	}

	/**
	 *
	 * @param string $text
	 * @param int    $max_length
	 * @param bool   $add_dots (optional, default: true)
	 * @param string $dots (optional, default: ...)
	 *
	 * @return string
	 */
	public static function shorten( $text, $max_length, $add_dots = true, $dots = '...' )
	{

		$text = trim( $text );

		if( $add_dots ) {
			$max_length = $max_length-strlen( $dots );
		}

		if( !preg_match( '~^(.{0,'.$max_length.'})~us', $text, $match ) ) {
			return $text;
		}

		$shortened = $match[1];

		if( strlen( $text )===strlen( $shortened ) ) {
			return $text;
		}

		$shortened = preg_replace( '~( [^ ]*)$~us', '', $shortened );
		if( $add_dots ) {
			$shortened .= $dots;
		}

		return $shortened;
	}

	/**
	 * Search all %JET_*% constants in text and replace it by values
	 *
	 * Example:
	 * <code>
	 * $text = 'My temp path is %JET_PATH_TMP%';
	 * $output = Data_Text::replaceConstants($text); // %JET_PATH_TMP% will be replaced by real path to [ROAD_root]/tmp/
	 * </code>
	 *
	 * @param string $input
	 *
	 * @param array  $default_replacement (optional; default: array())
	 *
	 * @return string
	 */
	public static function replaceSystemConstants( $input, array $default_replacement = [] )
	{
		if( !static::$_defined_constants ) {
			static::$_defined_constants = get_defined_constants( true );
			static::$_defined_constants = static::$_defined_constants['user'];
		}

		$data = static::$_defined_constants;


		return static::replaceData( $input, array_merge( $data, $default_replacement ) );
	}

	/**
	 * Replace data in text by given values
	 *
	 * Example:
	 * $text = array('PARAM1' => 'value 1', 'PARAM2' => 'value 2')
	 * replaces %PARAM1% for value 1 and %PARAM2% for value 2
	 *
	 * @param string $text
	 * @param array  $data
	 *
	 * @return string
	 */
	public static function replaceData( $text, array $data )
	{

		$replace_keys = array_keys( $data );
		foreach( $replace_keys as $i => $v ) {
			$replace_keys[$i] = '%'.$v.'%';
		}
		$replace_values = array_values( $data );

		return str_replace( $replace_keys, $replace_values, $text );
	}

	/**
	 * @param string $input
	 *
	 * @return string
	 */
	public static function htmlSpecialChars( $input )
	{
		return htmlspecialchars( $input, ENT_QUOTES, JET_CHARSET );
	}

}