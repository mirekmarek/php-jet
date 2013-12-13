<?php
/**
 *
 *
 *
 * Default class describing Site (@see Mvc_Sites, @see Mvc_Sites_Site_Abstract)
 *
 * A class can be replaced by another class (@see Factory, @see Mvc_Factory), but they must expand Mvc_Sites_Site_Abstract
 *
 * @see Factory
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Sites_Site_Default extends Mvc_Sites_Site_Abstract {

	/**
	 * @var string
	 */
	protected static $__data_model_model_name = "Jet_Mvc_Sites_Site";
	/**
	 * @var string
	 */
	protected static $__data_model_ID_class_name = "Jet\\Mvc_Sites_Site_ID_Default";
	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		"name" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255
		),
		"is_default" => array(
			"type" => self::TYPE_BOOL,
		),
		"is_active" => array(
			"type" => self::TYPE_BOOL,
		),
		"locales" => array(
			"type" => self::TYPE_ARRAY,
			"item_type" => self::TYPE_LOCALE,
		),
		"default_locale" => array(
			"type" => self::TYPE_LOCALE,
			"is_required" => true
		),
		"localized_data" => array(
			"type" => self::TYPE_DATA_MODEL,
			"data_model_class" => "Jet\\Mvc_Sites_Site_LocalizedData_Default"
		)
	);

	/**
	 *
	 * @var string
	 */
	protected $ID = "";


	/**
	 * Internal site name
	 *
	 * @var string
	 */
	protected $name = "";

	/**
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 * @see Mvc_Sites
	 *
	 * @var Locale[]
	 */
	protected $locales = array();

	/**
	 * @see Mvc_Sites
	 *
	 * @var Locale
	 */
	protected $default_locale = null;

	/**
	 *
	 * @var Mvc_Sites_Site_LocalizedData_Abstract[]
	 */
	protected $localized_data;



	/**
	 * Prepares new site data
	 *
	 * @param string $name
	 * @param string $ID (optional)
	 */
	public function initNew( $name, $ID=null ) {
		parent::initNewObject();
		if($ID) {
			$this->ID = $ID;
		} else {
			$this->ID = $this->getEmptyIDInstance()->generateID($this, $name );
		}

		$this->name = $name;
		$this->is_default = count($this->getList())==0;
	}


	/**
	 * @param bool $called_after_save
	 * @param mixed|null $backend_save_result
	 */
	protected  function generateID(  $called_after_save = false, $backend_save_result = null  ) {
	}

	/**
	 * Returns site name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath() {
		return JET_APPLICATION_SITES_PATH . $this->ID."/";
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath() {
		return $this->getBasePath()."layouts/";
	}

	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	public function getDefaultLocale() {
		return $this->default_locale;
	}


	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Sites_Site_LocalizedData_Abstract
	 */
	public function getLocalizedData( Locale $locale ) {
		return $this->localized_data[$locale->toString()];
	}

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Sites_Site_LocalizedData_URL_Abstract[]
	 */
	public function getURLs( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getURLs();
	}

	/**
	 * Add URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function addURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Remove URL. If the URL was default, then set as the default first possible URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function removeURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->setDefaultURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Sites_Site_LocalizedData_URL_Abstract
	 */
	public function getDefaultURL( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getDefaultURL();
	}



	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultSslURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->setDefaultSslURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public function getDefaultSslURL( Locale $locale ) {
		return (string)$this->localized_data[(string)$locale]->getDefaultSslURL();
	}


	/**
	 *
	 * @param Locale $locale
	 * @return Mvc_Sites_Site_LocalizedData_MetaTag_Abstract[]
	 */
	public function getDefaultMetaTags( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getDefaultMetaTags();
	}

	/**
	 * @param Locale $locale
	 * @param Mvc_Sites_Site_LocalizedData_MetaTag_Abstract $meta_tag
	 */
	public function addDefaultMetaTag( Locale $locale, Mvc_Sites_Site_LocalizedData_MetaTag_Abstract $meta_tag) {
		$this->localized_data[(string)$locale]->addDefaultMetaTag($meta_tag);
	}

	/**
	 * @param Locale $locale
	 * @param int $index
	 */
	public function removeDefaultMetaTag( Locale $locale, $index ) {
		$this->localized_data[(string)$locale]->removeDefaultMetaTag($index);
	}

	/**
	 * @param Locale $locale
	 * @param Mvc_Sites_Site_LocalizedData_MetaTag_Abstract[] $meta_tags
	 */
	public function  setDefaultMetaTags( Locale $locale, $meta_tags ) {
		$this->localized_data[(string)$locale]->setDefaultMetaTags( $meta_tags );
	}


	/**
	 * Returns site locales
	 *
	 * @see Site
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getLocales( $get_as_string=false ) {

		$result = array();

		foreach( $this->locales as $locale ) {
			$result[] = $get_as_string ? (string)$locale : $locale;
		}

		return $result;
	}

	/**
	 * Add locale
	 *
	 * @param Locale $locale
	 */
	public function addLocale( Locale $locale ) {
		if( isset($this->localized_data[(string)$locale]) ) {
			return;
		}

		$new_ld = Mvc_Factory::getLocalizedSiteInstance();
		$new_ld->initNew($locale);

		$this->locales[] = $locale;
		$this->localized_data[(string)$locale] = $new_ld;

		if(!$this->default_locale->toString()) {
			$this->setDefaultLocale( $locale );
		}
	}

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale ) {
		if( !isset($this->localized_data[(string)$locale]) ) {
			return;
		}

		$new_locales = array();

		foreach($this->locales as $o_locale) {
			if((string)$o_locale==(string)$locale) {
				unset( $this->localized_data[(string)$locale] );
				continue;
			}

			if((string)$locale == (string)$this->default_locale) {
				$this->default_locale = $o_locale;
			}

			$new_locales[] = $o_locale;
		}

		$this->locales = $new_locales;

		if(!$this->locales) {
			$this->default_locale = null;
		}
	}

	/**
	 * Set default locale. Add locale first if is not defined.
	 *
	 * @param Locale $locale
	 */
	public function setDefaultLocale( Locale $locale ) {
		$this->addLocale( $locale );

		$this->default_locale = $locale;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault() {
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault($is_default) {
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @return bool
	 */
	public function getIsActive() {
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive($is_active) {
		$this->is_active = (bool)$is_active;
	}



	/**
	 * Returns a list of all sites
	 *
	 * @return Mvc_Sites_Site_Abstract[]
	 */
	public function getList() {
		$list = $this->fetchObjects();
		$list->getQuery()->setOrderBy("name");
		return $list;
	}

	/**
	 * Returns site by URL
	 *
	 * @param string $URL
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public function getByURL( $URL ) {
		return $this->fetchOneObject(
			array(
				"this.localized_data.URL"=>$URL
			)
		);
	}


	/**
	 * Returns default site data
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public function getDefault() {
		return $this->fetchOneObject( array(
			"this.is_default" => true
		) );
	}

	/**
	 * @return array
	 */
	public function getLayoutsList() {
		$_lj = IO_Dir::getFilesList( $this->getLayoutsPath(), "*.phtml");

		$layouts = array();

		foreach($_lj as $lj) {
			$lj = substr($lj, 0, -6);

			$layouts[$lj] = basename($lj);
		}

		return $layouts;
	}
}