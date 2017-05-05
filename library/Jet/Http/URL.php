<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Http_URL
 * @package Jet
 */
class Http_URL extends BaseObject
{

	/**
	 *
	 * @var string
	 */
	protected $URL;

	/**
	 *
	 * @var bool
	 */
	protected $is_valid = false;


	/**
	 *
	 * @var string
	 */
	protected $scheme = 'http';

	/**
	 *
	 * @var string
	 */
	protected $host = '';

	/**
	 *
	 * @var string
	 */
	protected $user = null;

	/**
	 *
	 * @var string
	 */
	protected $pass = null;

	/**
	 *
	 * @var string
	 */
	protected $path = '/';

	/**
	 *
	 * @var string
	 */
	protected $query = '';

	/**
	 *
	 * @var array
	 */
	protected $query_data = null;

	/**
	 *
	 * @var string
	 */
	protected $fragment = null;

	/**
	 *
	 * @var int
	 */
	protected $port = null;

	/**
	 *
	 * @param string $URL
	 */
	public function __construct( $URL )
	{
		$this->URL = $URL;
		$this->parse();
	}

	/**
	 *
	 * @return bool
	 */
	protected function parse()
	{
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$parsed = @parse_url( $this->URL );

		if( $parsed===false||!isset( $parsed['scheme'] ) ) {
			$this->is_valid = false;

			return false;
		}

		$this->is_valid = true;

		foreach( $parsed as $k => $v ) {
			$this->{$k} = $v;
		}

		return true;
	}

	/**
	 *
	 * @param string $URL
	 *
	 * @return Http_URL
	 */
	public static function parseRequestURL( $URL )
	{
		return new self( $URL );
	}

	/**
	 * @return bool
	 */
	public function getIsValid()
	{
		return $this->is_valid;
	}

	/**
	 * @param string $pass
	 */
	public function setPassword( $pass )
	{
		$this->pass = $pass;
	}

	/**
	 * @return array
	 */
	public function getQueryData()
	{
		if( $this->query_data===null&&$this->is_valid ) {
			if( $this->query!=='' ) {
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				@parse_str( $this->query, $this->query_data );
				if( !is_array( $this->query_data ) ) {
					$this->query_data = [];
				}
			}
		}

		return $this->query_data;
	}

	/**
	 * @return bool
	 */
	public function getIsSSL()
	{
		return ( $this->scheme=='https' );
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
		$res = $this->getScheme().'://';

		if( $this->getUser()&&$this->getPassword() ) {
			$res .= $this->getUser().':'.$this->getPassword();
		}

		$res .= $this->getHost();
		if( $this->getPort() ) {
			$res .= ':'.$this->getPort();
		}

		$res .= $this->getPath();

		if( $this->getQuery() ) {
			$res .= '?'.$this->getQuery();
		}

		if( $this->getFragment() ) {
			$res .= '#'.$this->getFragment();
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @param string $scheme
	 */
	public function setScheme( $scheme )
	{
		$this->scheme = $scheme;
	}

	/**
	 * @return string
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param string $user
	 */
	public function setUser( $user )
	{
		$this->user = $user;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->pass;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 */
	public function setHost( $host )
	{
		$this->host = $host;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort( $port )
	{
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath( $path )
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param string $query
	 */
	public function setQuery( $query )
	{
		$this->query = $query;
		$this->query_data = null;
	}

	/**
	 * @return string
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * @param string $fragment
	 */
	public function setFragment( $fragment )
	{
		$this->fragment = $fragment;
	}

}