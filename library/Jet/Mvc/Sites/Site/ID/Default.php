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
 * Class Mvc_Sites_Site_ID_Default
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getSiteIDInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Sites_Site_ID_Abstract'
 */
class Mvc_Sites_Site_ID_Default extends Mvc_Sites_Site_ID_Abstract {

	/**
	 * @param string $ID
	 */
	public function setSiteID( $ID ) {
		$this->values['ID'] = $ID;
	}

	/**
	 * @return string
	 */
	public function getSiteID() {
		return $this->values['ID'];
	}
}