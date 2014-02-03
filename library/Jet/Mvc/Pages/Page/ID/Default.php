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
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Pages_Page_ID_Default
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getSiteIDInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Sites_Site_ID_Abstract'
 */
class Mvc_Pages_Page_ID_Default extends Mvc_Pages_Page_ID_Abstract {

	/**
	 * @param string $site_ID
	 */
	public function setSiteID( $site_ID ) {
		$this->values['site_ID'] = $site_ID;
	}

	/**
	 * @param Locale|string $locale
	 *
	 */
	public function setLocale($locale) {
		$this->values['locale'] = $locale;

		if(!($this->values['locale'] instanceof $locale)) {
			$this->values['locale'] = new Locale($this->values['locale']);
		}

	}

	/**
	 * @param $ID
	 *
	 */
	public function setPageID($ID) {
		$this->values['ID'] = $ID;
	}

	/**
	 * @return string
	 */
	public function getSiteID() {
		return $this->values['site_ID'];
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale() {
		return $this->values['locale'];
	}

	/**
	 * @return string
	 */
	public function getPageID() {
		return $this->values['ID'];
	}

	/**
	 * Generate unique ID
	 *
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 */
	public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null ) {

		if(!$this->values['ID']) {
			/**
			 * @var Mvc_Pages_Page_Abstract $data_model_instance
			 */
			$this->generateNameID( $data_model_instance, 'ID', $data_model_instance->getName() );
		}

	}

}