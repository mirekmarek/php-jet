<?php
/**
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
 * @subpackage Mvc_Sites
 */
namespace Jet;

abstract class Mvc_Sites_Site_ID_Abstract extends DataModel_ID_Default {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getSiteIDInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Sites_Site_ID_Abstract";

	/**
	 * @param string $ID
	 */
	abstract public function setSiteID( $ID );

	/**
	 * @return string
	 */
	abstract public function getSiteID();

	/**
	 * @param string $ID
	 *
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	public function createID( $ID ) {
		$this->setSiteID($ID);
		return $this;
	}
}