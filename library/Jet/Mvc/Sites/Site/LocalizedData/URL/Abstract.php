<?php
/**
 *
 *
 *
 * Class describes one URL
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
 * Class Mvc_Sites_Site_LocalizedData_URL_Abstract
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Sites_Site_LocalizedData_URL_Abstract'
 * @JetFactory:method = 'getLocalizedSiteURLInstance'
 *
 * @JetDataModel:name = 'site_localized_data_URL'
 * @JetDataModel:parent_model_class_name = 'Jet\\Mvc_Sites_LocalizedData_Abstract'
 */
abstract class Mvc_Sites_Site_LocalizedData_URL_Abstract extends DataModel_Related_1toN {

	/**
	 * @param string $URL (optional)
	 * @param bool $is_default (optional)
	 */
	public function __construct($URL='', $is_default=false) {
		if($URL) {
			$this->setURL($URL);
			$this->setIsDefault($is_default);
		}

		parent::__construct();
	}

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	abstract public function  toString();


	/**
	 * @return string
	 */
	abstract public function getURL();

	/**
	 * @param string $URL
	 * @throws Mvc_Sites_Site_Exception
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
	 * @return bool|string
	 */
	abstract public function getSchemePart();

	/**
	 * @return bool|string
	 */
	abstract public function getHostPart();

	/**
	 * @return bool|string
	 */
	abstract public function getPostPart();

	/**
	 * @return bool|string
	 */
	abstract public function getPathPart();


	/**
	 * @return bool
	 */
	abstract public function getIsSSL();

	/**
	 * @param bool $is_SSL
	 */
	abstract public function setIsSSL( $is_SSL );


	/**
	 * Example:
	 *
	 * URL: http://my-domain.tld/path/
	 *
	 * Base URL: http://my-domain.tld
	 *
	 * URL: https://my-domain.tld:8443/path/
	 *
	 * Base URL: https://my-domain.tld:8443
	 *
	 *
	 * @return string
	 */
	abstract public function getBaseURL();

}