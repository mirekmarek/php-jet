<?php
/**
 *
 *
 *
 * Default class describing Site Localized Data (@see Mvc_Sites, @see Mvc_Site_LocalizedData_Abstract)
 *
 * A class can be replaced by another class (@see Factory, @see Mvc_Factory), but they must expand Mvc_Site_LocalizedData_Abstract
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
 * Class Mvc_Site_LocalizedData_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites_LocalizedData'
 * @JetDataModel:parent_model_class_name = 'Jet\Mvc_Site_Default'
 * @JetDataModel:ID_class_name = 'Jet\DataModel_ID_Passive'
 */
class Mvc_Site_LocalizedData_Default extends Mvc_Site_LocalizedData_Abstract {

	/**
	 *
	 * @JetDataModel:related_to = 'main.ID'
	 */
	protected $site_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:is_required = true
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_active = true;


	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title:'
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Header Suffix:'
	 *
	 * @var string
	 */
	protected $default_headers_suffix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Body Prefix:'
	 *
	 * @var string
	 */
	protected $default_body_prefix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Body Suffix:'
	 *
	 * @var string
	 */
	protected $default_body_suffix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Mvc_Site_LocalizedData_URL_Default'
	 *
	 * @var Mvc_Site_LocalizedData_URL_Abstract[]|DataModel_Related_1toN_Iterator
	 */
	protected $URLs;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Mvc_Site_LocalizedData_MetaTag_Default'
	 *
	 * @var Mvc_Site_LocalizedData_MetaTag_Abstract[]|DataModel_Related_1toN_Iterator
	 */
	protected $default_meta_tags;

	/**
	 * @param Locale $locale
	 *
	 * @return void
	 */
	protected function setLocale( Locale $locale ) {
		$this->locale = $locale;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getArrayKeyValue() {
		return (string)$this->locale;
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
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getDefaultHeadersSuffix() {
		return $this->default_headers_suffix;
	}

	/**
	 * @param string $default_headers_suffix
	 */
	public function setDefaultHeadersSuffix($default_headers_suffix) {
		$this->default_headers_suffix = $default_headers_suffix;
	}

	/**
	 * @return string
	 */
	public function getDefaultBodyPrefix() {
		return $this->default_body_prefix;
	}

	/**
	 * @param string $default_body_prefix
	 */
	public function setDefaultBodyPrefix($default_body_prefix) {
		$this->default_body_prefix = $default_body_prefix;
	}

	/**
	 * @return string
	 */
	public function getDefaultBodySuffix() {
		return $this->default_body_suffix;
	}

	/**
	 * @param string $default_body_suffix
	 */
	public function setDefaultBodySuffix($default_body_suffix) {
		$this->default_body_suffix = $default_body_suffix;
	}

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract[]
	 */
	public function getURLs() {
		return $this->URLs;
	}

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract[]|string[] $URLs
	 */
	public function setURLs($URLs) {
		$this->URLs->clearData();

		foreach($URLs as $URL) {
            $this->URLs[] = $URL;
		}
	}

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 */
	public function addURL( $URL ) {
		$this->_addURL($URL );
	}

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 */
	public function removeURL( $URL ) {
		$index = null;

		/**
		 * @var Mvc_Site_LocalizedData_URL_Abstract $e_URL
		 */
		foreach($this->URLs as $i=>$e_URL) {
			if( (string)$URL==(string)$e_URL  ) {
				$index = $i;
				break;
			}
		}

		if($index===null) {
			return;
		}


		$was_default = $e_URL->getIsDefault();
		$was_SSL = $e_URL->getIsSSL();

		unset($this->URLs[$index]);

		if($was_default) {
			foreach($this->URLs as $e_URL) {
				if($URL->getIsSSL()==$was_SSL) {
					$e_URL->setIsDefault(true);
					break;
				}
			}
		}
	}

	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 * @return bool
	 */
	public function setDefaultURL( $URL ) {
		return $this->_setDefaultURL( $URL, false );
	}

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	public function getDefaultURL( ) {
		return $this->_getDefaultURL( false );
	}


	/**
	 * @param Mvc_Site_LocalizedData_URL_Abstract|string $URL
	 * @return bool
	 */
	public function setDefaultSslURL( $URL ) {
		return $this->_setDefaultURL( $URL, true );
	}

	/**
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	public function getDefaultSslURL( ) {
		return $this->_getDefaultURL( true );
	}


	/**
	 * @param string $URL
	 * @throws Mvc_Site_Exception
	 */
	protected function _addURL(  $URL ) {
		foreach($this->URLs as $e_URL) {
			if( (string)$URL==(string)$e_URL  ) {
				throw new Mvc_Site_Exception(
					'URL \''.$URL.'\' is already added',
					Mvc_Site_Exception::CODE_URL_ALREADY_ADDED
				);
			}
		}

		$new_URL_instance = Mvc_Factory::getLocalizedSiteURLInstance();
		$new_URL_instance->setURL( (string)$URL );
		$is_SSL = $new_URL_instance->getIsSSL();

		$is_default = true;

		foreach( $this->URLs as $URL ) {
			if($URL->getIsSSL()==$is_SSL) {
				$is_default = false;
				break;
			}
		}

		$new_URL_instance->setIsDefault( $is_default );

		$this->URLs[] = $new_URL_instance;
	}

	/**
	 * @param $URL
	 * @param bool $is_SSL
	 * @return bool
	 */
	protected function _setDefaultURL( $URL, $is_SSL ) {
		$set = false;
		foreach($this->URLs as $e_URL) {
			if($e_URL->getIsSSL()==$is_SSL) {
				$e_URL->setIsDefault( ( (string)$URL==(string)$e_URL  )  );
				if( (string)$URL==(string)$e_URL  ) {
					$set = true;
				}
			}
		}

		return $set;
	}

	/**
	 * @param bool $is_SSL
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	protected function _getDefaultURL( $is_SSL ) {
		foreach($this->URLs as $e_URL) {
			if( $e_URL->getIsDefault() && $e_URL->getIsSSL()==$is_SSL  ) {
				return $e_URL;
			}
		}
		return null;
	}



	/**
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Abstract[]
	 */
	public function getDefaultMetaTags() {
		return $this->default_meta_tags;
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract $default_meta_tag
	 */
	public function addDefaultMetaTag( Mvc_Site_LocalizedData_MetaTag_Abstract $default_meta_tag ) {
		$this->default_meta_tags[] = $default_meta_tag;
	}

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( $index ) {
		unset($this->default_meta_tags[$index]);
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract[] $default_meta_tags
	 */
	public function setDefaultMetaTags($default_meta_tags) {
		$this->default_meta_tags->clearData();

		foreach( $default_meta_tags as $default_meta_tag ) {
			$this->addDefaultMetaTag($default_meta_tag);
		}
	}
}