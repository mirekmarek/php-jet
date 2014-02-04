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

/**
 * Class Mvc_Sites_Site_ID_Abstract
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getSiteIDInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Sites_Site_ID_Abstract'
 */
abstract class Mvc_Sites_Site_ID_Abstract extends DataModel_ID_Name {

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