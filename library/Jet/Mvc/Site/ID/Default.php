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
 * Class Mvc_Site_ID_Default
 *
 * @JetFactory:class = 'Mvc_Factory'
 * @JetFactory:method = 'getSiteIDInstance'
 * @JetFactory:mandatory_parent_class = 'Mvc_Site_ID_Abstract'
 */
class Mvc_Site_ID_Default extends Mvc_Site_ID_Abstract {

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

    /**
     *
     * @return bool
     */
    public function getExists() {
        return (bool)Mvc_Site::get( $this );

    }
}