<?php
/**
 *
 *
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
 * @subpackage Mvc_Router
 */
namespace Jet;

/**
 * Class Mvc_Router_Cache_Backend_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Router_Cache_Backend_Abstract'
 */
abstract class Mvc_Router_Cache_Backend_Abstract extends Object {

	/**
	 * @var Mvc_Router_Cache_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 *
	 * @param Mvc_Router_Cache_Backend_Config_Abstract $config
	 *
	 */
	public function  __construct( Mvc_Router_Cache_Backend_Config_Abstract $config ) {
		$this->config = $config;

		$this->initialize();
	}

	/**
	 * Initializes the cache backend
	 *
	 * @abstract
	 *
	 */
	abstract function initialize();

	/**
	 * Get cache item for given URL or null if does not exist
	 *
	 * @abstract
	 *
	 * @param string $URL
	 * @return  null|Mvc_Router_Abstract
	 */
	abstract function load( $URL );

	/**
	 *
	 * @abstract
	 * @param string $URL
	 * @param Mvc_Router_Abstract $item
	 *
	 */
	abstract function save( $URL, Mvc_Router_Abstract $item );

	/**
	 * Truncate cache. URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @abstract
	 * @param null|string|string[] $URL
	 *
	 */
	abstract function truncate( $URL=null );

	/**
	 * @abstract
	 * @return mixed
	 */
	abstract function helper_getCreateCommand();

	/**
	 * @abstract
	 *
	 */
	abstract function helper_create();
}