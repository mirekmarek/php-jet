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
	 * @param array<string,mixed> $data
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
	
	public static function emojiToHTMLEntities( string $html ): string
	{
		$patterns = [
			'/[\x{1F600}-\x{1F64F}\x{2700}-\x{27BF}\x{1F680}-\x{1F6FF}\x{24C2}-\x{1F251}\x{1F30D}-\x{1F567}\x{1F900}-\x{1F9FF}\x{1F300}-\x{1F5FF}\x{1FA70}-\x{1FAF6}]/u'
		];
		
		foreach( $patterns as $pattern ) {
			$matches = [];
			
			preg_match_all($pattern, $html, $matches, PREG_SET_ORDER );
			
			foreach($matches as $match) {
				$emoji = $match[0];
				
				$utf32 = mb_convert_encoding($emoji, 'UTF-32', 'UTF-8');
				$hex4 = bin2hex($utf32);
				$dec = hexdec($hex4);
				
				$emoji = '&#'.$dec.';';
				
				$html = str_replace( $match, $emoji, $html );
			}
			
		}

		return $html;
	}

}

