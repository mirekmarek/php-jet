<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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

abstract class Mvc_Pages_Handler_Abstract extends Object {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getPageHandlerInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Pages_Handler_Abstract";

	/**
	 * Create new page
	 *
	 * @param Mvc_Pages_Page_Abstract $page_data
	 *
	 * @return void
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
	 * @return void
	 */
	abstract function dropPages( $site_ID, Locale $locale );

	/**
	 * Actualize pages (example: actualize pages by project definition)
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @return void
	 */
	abstract function actualizePages( $site_ID, Locale $locale );
}