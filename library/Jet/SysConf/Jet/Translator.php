<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_Jet_Translator
{

	protected static bool $auto_append_unknown_phrase = true;


	/**
	 * @return bool
	 */
	public static function getAutoAppendUnknownPhrase(): bool
	{
		return self::$auto_append_unknown_phrase;
	}

	/**
	 * @param bool $val
	 */
	public static function setAutoAppendUnknownPhrase( bool $val ): void
	{
		self::$auto_append_unknown_phrase = $val;
	}


}