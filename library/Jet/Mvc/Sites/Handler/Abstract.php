<?php
/**
 *
 *
 *
 * Basic site handler class (@see Mvc_Sites)
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
 * @subpackage Mvc_Sites
 */
namespace Jet;

abstract class Mvc_Sites_Handler_Abstract extends Object {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getSiteHandlerInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Sites_Handler_Abstract";

	/**
	 * Create new site
	 *
	 * @param Mvc_Sites_Site_Abstract $site_data
	 * @param string $template
	 * @param bool $activate (optional, default:true)
	 *
	 */
	abstract public function createSite( Mvc_Sites_Site_Abstract $site_data, $template, $activate=true );

	/**
	 * Drop site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 *
	 */
	abstract public function dropSite( Mvc_Sites_Site_ID_Abstract $ID );

 	/**
	 * Activate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 *
	 */
	abstract public function activateSite( Mvc_Sites_Site_ID_Abstract $ID );

	/**
	 * Deactivate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 *
	 */
	abstract public function deactivateSite( Mvc_Sites_Site_ID_Abstract $ID );

	/**
	 * Returns site data ...
	 *
	 * @throws Mvc_Sites_Handler_Exception
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 * @return Mvc_Sites_Site_Abstract
	 */
	protected function _getSite( Mvc_Sites_Site_ID_Abstract $ID  ) {
		$site = Mvc_Sites::getSite( $ID );
		
		if(!$site) {
			throw new Mvc_Sites_Handler_Exception(
				"Unknown site '{$ID}' ",
				Mvc_Sites_Handler_Exception::CODE_UNKNOWN_SITE
			);
		}

		return $site;

	}

}