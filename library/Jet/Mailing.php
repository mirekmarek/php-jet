<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mailing extends BaseObject
{
	/**
	 * @var ?Mailing_Config
	 */
	protected static ?Mailing_Config $config = null;

	/**
	 * @var ?Mailing_Backend_Abstract
	 */
	protected static ?Mailing_Backend_Abstract $backend = null;

	/**
	 * @var ?string
	 */
	protected static ?string $base_view_dir = null;


	/**
	 * @return string
	 */
	public static function getBaseViewDir() : string
	{
		if(!static::$base_view_dir) {
			static::$base_view_dir = SysConf_PATH::APPLICATION().'views/email_templates/';
		}

		return static::$base_view_dir;
	}

	/**
	 * @param string $dir
	 */
	public static function setBaseViewDir( string $dir ) : void
	{
		static::$base_view_dir = $dir;
	}

	/**
	 *
	 * @return Mailing_Config
	 */
	public static function getConfig() : Mailing_Config
	{
		if( !static::$config ) {
			static::$config = new Mailing_Config();
		}

		return static::$config;
	}

	/**
	 * @return Mailing_Backend_Abstract
	 */
	public static function getBackend() : Mailing_Backend_Abstract
	{
		if( !static::$backend ) {
			static::$backend = new Mailing_Backend_Default();
		}

		return static::$backend;
	}

	/**
	 * @param Mailing_Backend_Abstract $backend
	 */
	public static function setBackend( Mailing_Backend_Abstract $backend ) : void
	{
		static::$backend = $backend;
	}
	
	

	/**
	 * @param Mailing_Email $email
	 * @param string $to
	 * @param array $headers
	 *
	 * @return bool
	 */
	public static function sendEmail( Mailing_Email $email, string $to, array $headers=[] ) : bool
	{
		return static::getBackend()->sendEmail( $email, $to, $headers );
	}


}



