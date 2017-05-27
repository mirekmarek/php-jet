<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
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