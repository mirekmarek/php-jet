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
	public const SHORT = 0;
	public const MEDIUM = 0;
	public const LONG = '';
	public const FULL = '';
	public const GREGORIAN = '';
	public const TRADITIONAL = '';
}
