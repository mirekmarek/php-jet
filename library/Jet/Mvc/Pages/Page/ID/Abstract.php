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

abstract class Mvc_Pages_Page_ID_Abstract extends DataModel_ID_Default {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = 'Jet\\Mvc_Factory';
	/**
	 * @var string
	 */
	protected static $__factory_class_method = 'getPageIDInstance';
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = 'Jet\\Mvc_Pages_Page_ID_Abstract';

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
	 * @return string
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
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public function createID( $site_ID, $locale, $page_ID ) {
		$this->setSiteID( $site_ID );
		$this->setLocale( $locale );
		$this->setPageID( $page_ID );
		return $this;
	}
}