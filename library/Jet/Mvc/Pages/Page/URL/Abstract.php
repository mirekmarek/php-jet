<?php
/**
 *
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
 * Class Mvc_Pages_Page_URL_Abstract
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getPageURLInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Pages_Page_URL_Abstract'
 *
 * @JetDataModel:name = 'Jet_Mvc_Pages_Page_URL'
 * @JetDataModel:parent_model_class_name = 'Jet\\Mvc_Pages_Page_Abstract'
 */
abstract class Mvc_Pages_Page_URL_Abstract extends DataModel_Related_1toN {

	/**
	 * @param string $URL
	 * @param bool $is_default (optional, default: false )
	 * @param bool $is_SSL (optional, default: false )
	 */
	abstract function __construct($URL='', $is_default=false, $is_SSL=false);

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	abstract function  toString();

	/**
	 * @return string
	 */
	abstract public function getURL();

	/**
	 * @param string $URL
	 */
	abstract public function setURL($URL);

	/**
	 * @return bool
	 */
	abstract public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	abstract public function setIsDefault($is_default);

	/**
	 * @return bool
	 */
	abstract public function getIsSSL();

	/**
	 * @param bool $is_SSL
	 */
	abstract public function setIsSSL($is_SSL);


}