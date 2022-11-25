<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */


if(extension_loaded( 'intl' )) {
	return;
}


class Locale {
	public static function parseLocale(string $locale): ?array
	{
		return null;
	}
	
	public static function getDisplayRegion() : string
	{
		return '';
	}
	
	public static function getDisplayLanguage() : string
	{
		return '';
	}
}

class IntlDateFormatter {
	const SHORT = 0;
	const MEDIUM = 0;
	const LONG = '';
	const FULL = '';
	const GREGORIAN = '';
	const TRADITIONAL = '';
}
