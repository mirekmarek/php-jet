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
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

/**
 * Class Mvc_Sites_Site_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites'
 * @JetDataModel:ID_class_name = 'Jet\Mvc_Sites_Site_ID_Default'
 */
class Mvc_Sites_Site_Default extends Mvc_Sites_Site_Abstract {

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Site name:'
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Locale
	 */
	protected $default_locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Mvc_Sites_Site_LocalizedData_Default'
	 *
	 * @var Mvc_Sites_Site_LocalizedData_Abstract[]
	 */
	protected $localized_data;


	/**
	 * @param string $ID
	 *
	 */
	protected function setID( $ID ) {
		$this->ID = $ID;
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
		return JET_SITES_PATH . $this->ID.'/';
	}

	/**
	 * @return string
	 */
	public function getPublicFilesPath() {
		return $this->getBasePath().'public_files/';
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath() {
		return $this->getBasePath().'layouts/';
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
	 * @return bool
	 */
	public function getHasLocale( Locale $locale ) {
		return isset( $this->localized_data[$locale->toString()] );
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

		foreach( $this->localized_data as $ld ) {
			$locale = $ld->getLocale();

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

		$new_ld = Mvc_Factory::getLocalizedSiteInstance( $locale );

		$this->localized_data[(string)$locale] = $new_ld;

		if(!$this->default_locale || !$this->default_locale->toString()) {
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

		foreach($this->localized_data as $ld) {
			$o_locale = $ld->getLocale();

			if((string)$o_locale==(string)$locale) {
				unset( $this->localized_data[(string)$locale] );
				continue;
			}

			if((string)$locale == (string)$this->default_locale) {
				$this->default_locale = $o_locale;
			}

		}

		if(!count($this->localized_data)) {
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
	 * @return array
	 */
	public function getLayoutsList() {
		$_lj = IO_Dir::getFilesList( $this->getLayoutsPath(), '*.phtml');

		$layouts = array();

		foreach($_lj as $lj) {
			$lj = substr($lj, 0, -6);

			$layouts[$lj] = basename($lj);
		}

		return $layouts;
	}
}