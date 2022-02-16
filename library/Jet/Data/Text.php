<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Transliterator;

/**
 *
 */
class Data_Text
{

	/**
	 * @var array
	 */
	protected static array $_defined_constants = [];

	/**
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function removeAccents( string $text ): string
	{

		if( !class_exists( '\Transliterator', false ) ) {
			$text = iconv( SysConf_Jet_Main::getCharset(), 'ASCII//TRANSLIT', $text );

			return preg_replace( '/[^a-zA-Z0-9]/', '_', $text );
		} else {
			/** @noinspection SpellCheckingInspection */
			$transliterator = Transliterator::create( 'NFD; [:Nonspacing Mark:] Remove; NFC' );

			return $transliterator->transliterate( $text );

		}
	}

	/**
	 * Replace data in text by given values
	 *
	 * Example:
	 * $text = ['PARAM1' => 'value 1', 'PARAM2' => 'value 2']
	 * replaces %PARAM1% for value 1 and %PARAM2% for value 2
	 *
	 * @param string $text
	 * @param array $data
	 *
	 * @return string
	 */
	public static function replaceData( string $text, array $data ): string
	{

		$replace_keys = array_keys( $data );
		foreach( $replace_keys as $i => $v ) {
			$replace_keys[$i] = '%' . $v . '%';
		}
		$replace_values = array_values( $data );

		return str_replace( $replace_keys, $replace_values, $text );
	}

	/**
	 * @param string $input
	 * @param bool $encode_quotes
	 *
	 * @return string
	 */
	public static function htmlSpecialChars( string $input, bool $encode_quotes = false ): string
	{
		$flag = $encode_quotes ? ENT_QUOTES : ENT_COMPAT;

		return htmlspecialchars(
			htmlspecialchars_decode(
				trim(
					$input
				)
			), $flag, SysConf_Jet_Main::getCharset() );

	}

}

