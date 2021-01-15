<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplication;

use Jet\BaseObject;
use Jet\IO_File;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc_Page_Interface;
use Jet\SysConf_Path;

/**
 *
 */
class PageStaticContent extends BaseObject
{
	/**
	 * @param Mvc_Page_Interface $page
	 * @param Mvc_Page_Content_Interface|null $page_content
	 *
	 * @return string
	 */
	public static function get( Mvc_Page_Interface $page, Mvc_Page_Content_Interface $page_content = null )
	{

		$root_dir = SysConf_Path::getApplication() . 'texts/staticContent/';

		if(
			$page_content &&
			($text_id = $page_content->getParameter( 'text_id' ))
		) {
			$file_path = $root_dir . $page->getLocale() . '/' . $text_id . '.html';
		} else {
			$file_path = $root_dir . $page->getLocale() . '/' . $page->getSite()->getId() . '/' . $page->getId() . '.html';
		}

		if( !IO_File::exists( $file_path ) ) {
			return '';
		}

		return IO_File::read( $file_path );
	}
}