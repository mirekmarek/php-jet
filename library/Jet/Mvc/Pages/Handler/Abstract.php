<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Pages_Handler_Abstract
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getPageHandlerInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Pages_Handler_Abstract'
 */
abstract class Mvc_Pages_Handler_Abstract extends Object {

	/**
	 * Create new page
	 *
	 * @param Mvc_Pages_Page_Abstract $page_data
	 *
	 */
	abstract function createPage( Mvc_Pages_Page_Abstract $page_data );

	/**
	 * Drop page
	 *
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 *
	 */
	abstract public function dropPage( Mvc_Pages_Page_ID_Abstract $page_ID );


	/**
	 * Drop pages
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 */
	abstract function dropPages( $site_ID, Locale $locale );


	/**
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 */
	abstract function checkPagesData( $site_ID, Locale $locale );

	/**
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 */
	abstract function actualizePages( $site_ID, Locale $locale );
}