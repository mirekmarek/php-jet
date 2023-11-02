<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Page_Interface;

class Application_Web_Pages
{
	public static function changePassword() : ?MVC_Page_Interface
	{
		return static::getPage('change-password');
	}
	
	public static function resetPassword() : ?MVC_Page_Interface
	{
		return static::getPage('password-reset');
	}
	
	
	public static function signUp() : ?MVC_Page_Interface
	{
		return static::getPage('sign-up');
	}
	
	public static function secretArea() : ?MVC_Page_Interface
	{
		return static::getPage('secret_area');
	}
	
	public static function RESTAPITest() : ?MVC_Page_Interface
	{
		return static::getPage('rest');
	}
	
	public static function getPage( string $id, ?Locale $locale=null ) : ?MVC_Page_Interface
	{
		return MVC::getPage(
			$id,
			$locale,
			Application_Web::getBaseId()
		);
	}
}