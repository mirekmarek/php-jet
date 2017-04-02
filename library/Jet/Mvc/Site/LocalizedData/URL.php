<?php
/**
 *
 *
 *
 * Class describes one URL
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 *
 * @JetDataModel:name = 'site_localized_data_URL'
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites_LocalizedData_URLs'
 * @JetDataModel:parent_model_class_name = JET_MVC_SITE_LOCALIZED_CLASS
 * @JetDataModel:id_class_name = 'DataModel_Id_Passive'
 */
class Mvc_Site_LocalizedData_URL extends BaseObject implements Mvc_Site_LocalizedData_URL_Interface {

	/**
	 *
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:is_id = true
	 *
	 */
	protected $site_id = '';

	/**
	 * @JetDataModel:related_to = 'parent.locale'
	 * @JetDataModel:is_id = true
     *
     * @var Locale
	 */
	protected $locale = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_label = 'URL:'
	 * @JetDataModel:form_field_type = Form::TYPE_INPUT
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $URL = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_SSL = false;

	/**
	 * @var array|null|bool
	 */
	protected $parsed_URL_data = null;

	/**
	 * @param string $URL (optional)
	 * @param bool $is_default (optional)
	 */
	public function __construct($URL='', $is_default=false) {
		if($URL) {
			$this->setURL($URL);
			$this->setIsDefault($is_default);
		}

	}

    /**
     * @return string
     */
    public function  __toString() {
        return $this->toString();
    }

	/**
	 * @param string $site_id
	 */
	public function setSiteId($site_id) {
		$this->site_id = $site_id;
	}

	/**
	 * @return string
	 */
	public function getSiteId() {
		return $this->site_id;
	}

    /**
     * @param Locale $locale
     */
    public function setLocale( Locale $locale )
    {
        $this->locale = $locale;
    }

    /**
     * @return Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

	/**
	 * @return string
	 */
	public function  toString() {
		return $this->URL;
	}

	/**
	 * @return string
	 */
	public function getAsNonSchemaURL() {

		$host = $this->getHostPart();
		$port = $this->getPostPart();
		$path = $this->getPathPart();

		$URL = '//'.$host;
		if($port) {
			$URL .= ':'.$port;
		}

		$URL .= $path;

		return $URL;
	}


	/**
	 * @return string
	 */
	public function getURL() {
		return $this->URL;
	}

	/**
	 * @param string $URL
	 * @throws Mvc_Site_Exception
	 */
	public function setURL($URL) {

		if(!$URL) {
			throw new Mvc_Site_Exception(
				'URL is not defined',
				Mvc_Site_Exception::CODE_URL_NOT_DEFINED
			);
		}

        $force_ssl = false;
        if(substr($URL, 0, 4)=='SSL:') {
            $force_ssl = true;

            $URL = substr($URL, 4);
        }

		$parse_data = parse_url($URL);
		if(
			$parse_data===false ||
			!empty($parse_data['user']) ||
			!empty($parse_data['pass']) ||
			!empty($parse_data['query']) ||
			!empty($parse_data['fragment'])
		) {
			throw new Mvc_Site_Exception(
				'URL format is not valid! Valid format examples: http://host/, https://host/, http://host:80/, http://host/path/, .... ',
				Mvc_Site_Exception::CODE_URL_INVALID_FORMAT
			);
		}

        if(!isset($parse_data['path'])) {
            $parse_data['path'] = '';
        }

		if( substr($parse_data['path'], -1) == '/' ) {
			$URL = substr($URL, 0, -1);
            $parse_data['path'] = substr($parse_data['path'], 0, -1);
		}

		$this->is_SSL = ($force_ssl || $parse_data['scheme']=='https');

		$this->URL = $URL;
		$this->parsed_URL_data = $parse_data;
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
	 * @return bool|string
	 */
	public function getSchemePart() {
		return $this->parseURL( 'scheme' );
	}

	/**
	 * @return bool|string
	 */
	public function getHostPart() {
		return $this->parseURL( 'host' );
	}

	/**
	 * @return bool|string
	 */
	public function getPostPart() {
		return $this->parseURL( 'port' );
	}

	/**
	 * @return bool|string
	 */
	public function getPathPart() {
		return $this->parseURL( 'path' );
	}


	/**
	 * @return bool
	 */
	public function getIsSSL() {
		return $this->is_SSL;
	}

	/**
	 * @param bool $is_SSL
	 */
	public function setIsSSL( $is_SSL ) {
		$this->is_SSL = (bool)$is_SSL;
	}

	/**
	 * @see parse_url
	 *
	 * @param string $return_what (scheme, host, port, user, pass, path, query, fragment)
	 *
	 * @return string|bool
	 */
	protected function parseURL( $return_what ) {
		if(!$this->parsed_URL_data) {
			$this->parsed_URL_data = parse_url($this->URL);
		}

		if(!$this->parsed_URL_data) {
			return false;
		}

		return $this->parsed_URL_data[$return_what];
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return get_object_vars($this);
	}

}