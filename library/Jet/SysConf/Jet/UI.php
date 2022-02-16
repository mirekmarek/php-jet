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
class SysConf_Jet_UI
{
	protected static string $views_dir;
	protected static string $message_session_namespace = '_jsa_ui_messages';

	/**
	 * @return string
	 */
	public static function getViewsDir(): string
	{
		return static::$views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public static function setViewsDir( string $views_dir ): void
	{
		static::$views_dir = $views_dir;
	}

	/**
	 * @return string
	 */
	public static function getMessageSessionNamespace(): string
	{
		return self::$message_session_namespace;
	}

	/**
	 * @param string $message_session_namespace
	 */
	public static function setMessageSessionNamespace( string $message_session_namespace ): void
	{
		self::$message_session_namespace = $message_session_namespace;
	}

}