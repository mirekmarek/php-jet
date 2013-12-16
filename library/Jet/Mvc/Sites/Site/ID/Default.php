<?php
/**
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
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Sites_Site_ID_Default extends Mvc_Sites_Site_ID_Abstract {
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
	public function setSiteID( $ID ) {
		$this->values["ID"] = $ID;
	}

	/**
	 * @return string
	 */
	public function getSiteID() {
		return $this->values["ID"];
	}
}