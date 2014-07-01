<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

/**
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getRouterMapUrlInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Router_Map_URL_Abstract'
 */
abstract class Mvc_Router_Map_URL_Abstract extends Object implements \JsonSerializable {

	/**
	 * @var Mvc_Pages_Page_ID_Abstract
	 */
	protected $page_ID = '';

	/**
	 * @var Mvc_Pages_Page_ID_Abstract
	 */
	protected $page_parent_ID;

	/**
	 * @var string
	 */
	protected $page_title = '';

	/**
	 * @var string
	 */
	protected $page_menu_title = '';

	/**
	 * @var string
	 */
	protected $page_breadcrumb_title = '';

	/**
	 * @var string
	 */
	protected $page_authentication_required = '';

	/**
	 * @var bool
	 */
	protected $page_SSL_required = false;

	/**
	 *
	 * @var string
	 */
	protected $URL = '';

	/**
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 * @var bool
	 */
	protected $is_main = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_SSL = false;

	/**
	 * @var array|null|bool
	 */
	protected $parsed_URL_data = null;


	/**
	 * @param bool $is_main
	 */
	public function setIsMain($is_main) {
		$this->is_main = (bool)$is_main;
	}

	/**
	 * @return bool
	 */
	public function getIsMain() {
		return $this->is_main;
	}

	/**
	 * @param Mvc_Pages_Page_Abstract $page
	 * @return void
	 */
	abstract public function takePageData( Mvc_Pages_Page_Abstract $page );



	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 */
	public function setPageID(Mvc_Pages_Page_ID_Abstract $page_ID) {
		$this->page_ID = $page_ID;
	}

	/**
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public function getPageID() {
		return $this->page_ID;
	}


	/**
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public function getPageParentID() {
		return $this->page_parent_ID;
	}



	/**
	 * @return string
	 */
	public function getPageTitle() {
		return $this->page_title;
	}

	/**
	 * @return string
	 */
	public function getPageMenuTitle() {
		return $this->page_menu_title;
	}

	/**
	 * @return string
	 */
	public function getPageBreadcrumbTitle() {
		return $this->page_breadcrumb_title;
	}

	/**
	 * @return boolean
	 */
	public function getPageSSLRequired() {
		return $this->page_SSL_required;
	}

	/**
	 * @return string
	 */
	public function getPageAuthenticationRequired() {
		return $this->page_authentication_required;
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
	 * @throws Mvc_Sites_Site_Exception
	 */
	public function setURL($URL) {

		if(!$URL) {
			throw new Mvc_Sites_Site_Exception(
				'URL is not defined',
				Mvc_Sites_Site_Exception::CODE_URL_NOT_DEFINED
			);
		}

		$parse_data = parse_url($URL);
		if(
			$parse_data===false ||
			!empty($parse_data['user']) ||
			!empty($parse_data['pass']) ||
			!empty($parse_data['query']) ||
			!empty($parse_data['fragment'])
		) {
			throw new Mvc_Sites_Site_Exception(
				'URL format is not valid! Valid format examples: http://host/, https://host/, http://host:80/, http://host/path/, .... ',
				Mvc_Sites_Site_Exception::CODE_URL_INVALID_FORMAT
			);
		}

		if(empty($parse_data['path']) || $parse_data['path'][strlen($parse_data['path'])-1]!='/') {
			$URL .= '/';
		}

		$this->is_SSL = $parse_data['scheme']=='https';

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
	public function jsonSerialize() {
		return [
			'URL' => $this->URL,
			'is_default' => $this->is_default,
			'is_main' => $this->is_main,
			'is_SSL' => $this->is_SSL,

		];
	}


}
