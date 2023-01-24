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
class SysConf_Jet_Form
{
	protected static string $default_sent_key = '_jet_form_sent_';
	protected static string $default_views_dir;
	
	protected static string $CSRF_protection_session_name_prefix = 'CSRFProtection/';
	protected static string $CSRF_protection_token_field_name = '_CSRF_token_';
	
	/**
	 * @var callable|null
	 */
	protected static $CSRF_protection_token_generator = null;

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
	
	/**
	 * @return string
	 */
	public static function getCSRFProtection_SessionNamePrefix(): string
	{
		return static::$CSRF_protection_session_name_prefix;
	}
	
	/**
	 * @param string $value
	 */
	public static function setCSRFProtection_SessionNamePrefix( string $value ): void
	{
		static::$CSRF_protection_session_name_prefix = $value;
	}
	
	/**
	 * @return string
	 */
	public static function getCSRFProtection_TokenFieldName(): string
	{
		return static::$CSRF_protection_token_field_name;
	}
	
	/**
	 * @param string $value
	 */
	public static function setCSRFProtection_TokenFieldName( string $value ): void
	{
		static::$CSRF_protection_token_field_name = $value;
	}
	
	/**
	 * @return callable
	 */
	public static function getCSRFProtection_TokenGenerator(): callable
	{
		if(!static::$CSRF_protection_token_generator) {
			static::$CSRF_protection_token_generator = function() : string
			{
				return md5(uniqid().uniqid().uniqid());
			};
		}
		
		return static::$CSRF_protection_token_generator;
	}
	
	/**
	 * @param callable $generator
	 */
	public static function setCSRFProtection_TokenGenerator( callable $generator ): void
	{
		static::$CSRF_protection_token_generator = $generator;
	}


}