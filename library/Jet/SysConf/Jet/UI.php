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
class SysConf_Jet_UI
{
	protected static string $views_dir;
	protected static string $message_session_namespace = '_jsa_ui_messages';
	protected static string $search_default_placeholder = 'Search for...';

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

	/**
	 * @return string
	 */
	public static function getSearchDefaultPlaceholder(): string
	{
		return self::$search_default_placeholder;
	}

	/**
	 * @param string $search_default_placeholder
	 */
	public static function setSearchDefaultPlaceholder( string $search_default_placeholder ): void
	{
		self::$search_default_placeholder = $search_default_placeholder;
	}



}