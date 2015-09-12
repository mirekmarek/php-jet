<?php
/**
 *
 *
 *
 * @see Mvc_Site_Abstract
 * @see DataModel
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
 * Class Mvc_Site_LocalizedData_Abstract
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getLocalizedSiteInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Site_LocalizedData_Abstract'
 *
 * @JetDataModel:name = 'site_localized_data'
 * @JetDataModel:parent_model_class_name = 'Jet\Mvc_Site_Abstract'
 */
abstract class Mvc_Site_LocalizedData_Abstract extends DataModel_Related_1toN {

	/**
	 * @param Locale $locale (optional)
	 */
	public function __construct( Locale $locale=null) {

		if($locale) {
			$this->setLocale($locale);
		}

		parent::__construct();
	}

	/**
	 * @param Locale $locale
	 */
	abstract protected function setLocale( Locale $locale );

	/**
	 * @return Locale
	 */
	abstract public function getLocale();

	/**
	 * @return string
	 */
	public function getArrayKeyValue() {
		trigger_error('Please implement '.get_class($this).'::getArrayKeyValue()', E_ERROR);
	}

	/**
	 * @return bool
	 */
	abstract public function getIsActive();

	/**
	 * @param bool $is_active
	 */
	abstract public function setIsActive($is_active);


	/**
	 * @return string
	 */
	abstract public function getTitle();

	/**
	 * @param $title
	 */
	abstract public function setTitle($title);

	/**
	 * @return string
	 */
	abstract public function getDefaultHeadersSuffix();

	/**
	 * @param string $default_headers_suffix
	 */
	abstract public function setDefaultHeadersSuffix($default_headers_suffix);

	/**
	 * @return string
	 */
	abstract public function getDefaultBodyPrefix();

	/**
	 * @param string $default_body_prefix
	 */
	abstract public function setDefaultBodyPrefix($default_body_prefix);

	/**
	 * @return string
	 */
	abstract public function getDefaultBodySuffix();

	/**
	 * @param string $default_body_suffix
	 */
	abstract public function setDefaultBodySuffix($default_body_suffix);

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract[]|DataModel_Related_1toN
	 */
	abstract public function getURLs();

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract[]|string[] $URLs
	 */
	abstract public function setURLs($URLs);

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 */
	abstract public function addURL( $URL );

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 */
	abstract public function removeURL( $URL );

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 * @return bool
	 */
	abstract public function setDefaultURL( $URL );

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	abstract public function getDefaultURL();


	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 * @return bool
	 */
	abstract public function setDefaultSslURL( $URL );

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	abstract public function getDefaultSslURL();

	/**
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Abstract[]
	 */
	abstract public function getDefaultMetaTags();

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract $default_meta_tag
	 */
	abstract public function addDefaultMetaTag( Mvc_Site_LocalizedData_MetaTag_Abstract $default_meta_tag );

	/**
	 *
	 * @param int $index
	 */
	abstract public function removeDefaultMetaTag( $index );

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract[] $default_meta_tags
	 */
	abstract public function setDefaultMetaTags($default_meta_tags);

}