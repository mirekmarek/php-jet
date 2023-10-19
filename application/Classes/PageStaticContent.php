<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\BaseObject;
use Jet\MVC_Page_Content_Interface;
use Jet\MVC_Page_Interface;

class PageStaticContent extends BaseObject
{
	/**
	 * @param MVC_Page_Interface $page
	 * @param MVC_Page_Content_Interface|null $page_content
	 *
	 * @return string
	 */
	public static function get( MVC_Page_Interface $page, MVC_Page_Content_Interface $page_content = null ) : string
	{
		return 'Static content test '.$page->getKey();
	}
}