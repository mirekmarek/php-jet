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
 * Class Mvc_Page_ID_Abstract
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getPageIDInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Page_ID_Abstract'
 */
abstract class Mvc_Page_ID_Abstract extends DataModel_ID_Name {

	/**
	 * @param string $site_ID
	 */
	abstract public function setSiteID( $site_ID );

	/**
	 * @param Locale|string $locale
	 *
	 */
	abstract public function setLocale( $locale );

	/**
	 * @param $ID
	 *
	 */
	abstract public function setPageID( $ID );

	/**
	 * @return Mvc_Site_ID_Abstract
	 */
	abstract public function getSiteID();

	/**
	 * @return Locale
	 */
	abstract public function getLocale();

	/**
	 * @return string
	 */
	abstract public function getPageID();


	/**
	 * @param string $site_ID
	 * @param string $locale
	 * @param string $page_ID
	 *
	 * @return Mvc_Page_ID_Abstract
	 */
	public function createID( $site_ID, $locale, $page_ID ) {
		$this->setSiteID( $site_ID );
		$this->setLocale( $locale );
		$this->setPageID( $page_ID );
		return $this;
	}

}