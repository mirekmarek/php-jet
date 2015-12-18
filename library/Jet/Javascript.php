<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 */
namespace Jet;

class Javascript extends Object {


	/**
	 * @param string $JS
	 * @return string
	 */
	public static function translateJavaScript( $JS ) {
		preg_match_all('~Jet.translate\((".*"|\'.*\')\)~isU', $JS, $matches, PREG_SET_ORDER);

		$replacements = [];
		foreach($matches as $match){
			list($search, $text) = $match;
			$text = stripslashes(trim($text, $text[0] == '\'' ? '\'' : '"'));

			$text = json_encode(Tr::_($text));
			$JS = str_replace($search, $text, $JS);
		}
		$JS = Data_Text::replaceData( $JS, $replacements );

		return $JS;
	}
}