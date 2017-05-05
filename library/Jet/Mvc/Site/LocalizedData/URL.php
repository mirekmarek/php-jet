<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Site_LocalizedData_URL extends BaseObject implements Mvc_Site_LocalizedData_URL_Interface
{

	/**
	 *
	 * @var string
	 */
	protected $site_id = '';

	/**
	 *
	 * @var Locale
	 */
	protected $locale = '';

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
	 * @param bool   $is_default (optional)
	 */
	public function __construct( $URL = '', $is_default = false )
	{
		if( $URL ) {
			$this->setURL( $URL );
			$this->setIsDefault( $is_default );
		}

	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->URL;
	}

	/**
	 * @return string
	 */
	public function getSiteId()
	{
		return $this->site_id;
	}

	/**
	 * @param string $site_id
	 */
	public function setSiteId( $site_id )
	{
		$this->site_id = $site_id;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param Locale $locale
	 */
	public function setLocale( Locale $locale )
	{
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getAsNonSchemaURL()
	{

		$host = $this->getHostPart();
		$port = $this->getPostPart();
		$path = $this->getPathPart();

		$URL = '//'.$host;
		if( $port ) {
			$URL .= ':'.$port;
		}

		$URL .= $path;

		return $URL;
	}

	/**
	 * @return bool|string
	 */
	public function getHostPart()
	{
		return $this->parseURL( 'host' );
	}

	/**
	 * @see parse_url
	 *
	 * @param string $return_what (scheme, host, port, user, pass, path, query, fragment)
	 *
	 * @return string|bool
	 */
	protected function parseURL( $return_what )
	{
		if( !$this->parsed_URL_data ) {
			$this->parsed_URL_data = parse_url( $this->URL );
		}

		if( !$this->parsed_URL_data ) {
			return false;
		}

		return $this->parsed_URL_data[$return_what];
	}

	/**
	 * @return bool|string
	 */
	public function getPostPart()
	{
		return $this->parseURL( 'port' );
	}

	/**
	 * @return bool|string
	 */
	public function getPathPart()
	{
		return $this->parseURL( 'path' );
	}

	/**
	 * @return string
	 */
	public function getURL()
	{
		return $this->URL;
	}

	/**
	 * @param string $URL
	 *
	 * @throws Mvc_Site_Exception
	 */
	public function setURL( $URL )
	{

		if( !$URL ) {
			throw new Mvc_Site_Exception(
				'URL is not defined', Mvc_Site_Exception::CODE_URL_NOT_DEFINED
			);
		}

		$force_ssl = false;
		if( substr( $URL, 0, 4 )=='SSL:' ) {
			$force_ssl = true;

			$URL = substr( $URL, 4 );
		}

		$parse_data = parse_url( $URL );
		if( $parse_data===false||!empty( $parse_data['user'] )||!empty( $parse_data['pass'] )||!empty( $parse_data['query'] )||!empty( $parse_data['fragment'] ) ) {
			throw new Mvc_Site_Exception(
				'URL format is not valid! Valid format examples: http://host/, https://host/, http://host:80/, http://host/path/, .... ',
				Mvc_Site_Exception::CODE_URL_INVALID_FORMAT
			);
		}

		if( !isset( $parse_data['path'] ) ) {
			$parse_data['path'] = '';
		}

		if( substr( $parse_data['path'], -1 )=='/' ) {
			$URL = substr( $URL, 0, -1 );
			$parse_data['path'] = substr( $parse_data['path'], 0, -1 );
		}

		$this->is_SSL = ( $force_ssl||$parse_data['scheme']=='https' );

		$this->URL = $URL;
		$this->parsed_URL_data = $parse_data;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault()
	{
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( $is_default )
	{
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @return bool|string
	 */
	public function getSchemePart()
	{
		return $this->parseURL( 'scheme' );
	}

	/**
	 * @return bool
	 */
	public function getIsSSL()
	{
		return $this->is_SSL;
	}

	/**
	 * @param bool $is_SSL
	 */
	public function setIsSSL( $is_SSL )
	{
		$this->is_SSL = (bool)$is_SSL;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return get_object_vars( $this );
	}

}