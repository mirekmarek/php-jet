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

use Jet\BaseObject;
use Jet\Locale;

class Mailing extends BaseObject {
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
	 * @param string|Locale $locale
	 * @return Application_Config_Emails_Sender
	 */
	public static function getSenderConfig( $locale ) {
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
		if(!$locale) {
			$locale = Locale::getCurrentLocale();
		}

		$template = new Mailing_Template( $template_id, $locale, $data );
		$template->send($to);
	}


}