<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

/**
 * Class Mvc_Router_Map_Abstract
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getRouterMapInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Router_Map_Abstract'
 */

abstract class Mvc_Router_Map_Abstract extends Object {

	/**
	 *
	 */
	abstract public function generate();

	/**
	 * @return Mvc_Router_Map_URL_Abstract
	 */
	abstract public function getDefaultURL();

	/**
	 * @param array $URLs
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	abstract public function findPage( array $URLs );

	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 * @param bool $only_default (optional, default: false)
	 *
	 * @return Mvc_Router_Map_URL_Abstract[]|null
	 */
	abstract public function findURLs( Mvc_Pages_Page_ID_Abstract $page_ID, $only_default=false );


	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	abstract public function findMainURL( Mvc_Pages_Page_ID_Abstract $page_ID );

}