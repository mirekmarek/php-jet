<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\BaseObject;
use Jet\IO_File;
use Jet\Locale;
use Jet\Mvc_Page_Interface;

/**
 *
 */
class PageStaticContent extends BaseObject
{
	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return string
	 */
	public static function get( Mvc_Page_Interface $page )
	{
		$file_path = JET_PATH_APPLICATION.'texts/staticContent/'.$page->getSite()->getId().'/'.$page->getLocale().'/'.$page->getId().'.html';

		if(!IO_File::exists($file_path)) {
			return '';
		}

		return IO_File::read($file_path);
	}
}