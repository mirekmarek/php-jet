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
class Mailing extends BaseObject
{
	const DEFAULT_SENDER_ID = 'default';

	/**
	 * @var ?Mailing_Config
	 */
	protected static ?Mailing_Config $config = null;

	/**
	 * @var ?Mailing_Backend_Abstract
	 */
	protected static ?Mailing_Backend_Abstract $backend = null;

	/**
	 *
	 * @return Mailing_Config
	 */
	public static function getConfig(): Mailing_Config
	{
		if( !static::$config ) {
			static::$config = new Mailing_Config();
		}

		return static::$config;
	}

	/**
	 * @return Mailing_Backend_Abstract
	 */
	public static function getBackend(): Mailing_Backend_Abstract
	{
		if( !static::$backend ) {
			static::$backend = new Mailing_Backend_Default();
		}

		return static::$backend;
	}

	/**
	 * @param Mailing_Backend_Abstract $backend
	 */
	public static function setBackend( Mailing_Backend_Abstract $backend ): void
	{
		static::$backend = $backend;
	}


	/**
	 * @param Mailing_Email $email
	 *
	 * @return bool
	 */
	public static function sendEmail( Mailing_Email $email ): bool
	{
		return static::getBackend()->sendEmail( $email );
	}

}
