<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_Jet_Form
{
	protected static string $default_sent_key = '_jet_form_sent_';
	protected static string $default_views_dir;

	/**
	 * @return string
	 */
	public static function getDefaultSentKey(): string
	{
		return static::$default_sent_key;
	}

	/**
	 * @param string $default_sent_key
	 */
	public static function setDefaultSentKey( string $default_sent_key ): void
	{
		static::$default_sent_key = $default_sent_key;
	}

	/**
	 * @return string
	 */
	public static function getDefaultViewsDir(): string
	{
		return static::$default_views_dir;
	}

	/**
	 * @param string $default_views_dir
	 */
	public static function setDefaultViewsDir( string $default_views_dir ): void
	{
		static::$default_views_dir = $default_views_dir;
	}


}