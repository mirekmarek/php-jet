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

class Mvc_Page_ID extends Mvc_Page_ID_Abstract {

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
		if(!($locale instanceof Locale)) {
			$locale = new Locale($locale);
		}

		$this->values['locale'] = $locale;
	}

	/**
	 * @param $ID
	 *
	 */
	public function setPageID($ID) {
		$this->values['ID'] = $ID;
	}

	/**
	 * @return Mvc_Site_ID_Abstract
	 */
	public function getSiteID() {
		return Mvc_Factory::getSiteIDInstance()->createID( $this->values['site_ID'] );
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

}