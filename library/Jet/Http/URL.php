<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Http
 * @subpackage Http_URL
 */
namespace Jet;

class Http_URL extends Object {
	
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
	public function __construct($URL){
		$this->URL = $URL;
		$this->parse();
	}

	/**
	 *
	 * @return bool
	 */
	protected function parse(){
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$parsed = @parse_url($this->URL);

		if(
			$parsed === false ||
			!isset($parsed['scheme'])
		){
			$this->is_valid = false;
			return false;
		}

		$this->is_valid = true;
		
		foreach($parsed as $k=>$v){
			$this->{$k} = $v;
		}

		return true;
	}

	/**
	 *
	 * @param $URL
	 *
	 * @return Http_URL
	 */
	public static function parseURL($URL){
		return new self($URL);
	}

	/**
	 * @return string
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @return boolean
	 */
	public function getIsValid() {
		return $this->is_valid;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->pass;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @return string
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @return array
	 */
	public function getQueryData() {
		if($this->query_data===null && $this->is_valid) {
			if($this->query !== ''){
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				@parse_str($this->query, $this->query_data);
				if(!is_array($this->query_data)){
					$this->query_data = array();
				}
			}
		}

		return $this->query_data;
	}

	/**
	 * @return string
	 */
	public function getScheme() {
		return $this->scheme;
	}

	/**
	 * @return string
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return bool
	 */
	public function getIsSSL() {
		return ($this->scheme=='https');
	}


}