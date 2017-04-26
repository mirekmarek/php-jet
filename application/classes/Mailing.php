<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

class Mailing {
	/**
	 * @var Application_Config_Emails
	 */
	protected static $config = null;

	/**
	 *
	 * @return Application_Config_Emails
	 */
	public static function getConfig(){
		if(!static::$config) {
			static::$config = new Application_Config_Emails();
		}
		return static::$config;
	}

	/**
	 * @param string $locale
	 * @return Application_Config_Emails_Sender
	 */
	public function getSenderConfig( $locale ) {
		$locale = (string)$locale;

		return static::getConfig()->getSender($locale);
	}

	/**
	 * @param string $to
	 * @param string $template_id
	 * @param array $data
	 * @param string|Locale $locale
	 */
	public static function sendTemplate( $to, $template_id, array $data=[], $locale=null ) {
		//TODO:
	}


}