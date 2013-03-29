<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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

abstract class Mvc_Router_Cache_Backend_Abstract extends Object {
	/**
	 * @var null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null
	 */
	protected static $__factory_class_method = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Router_Cache_Backend_Abstract";

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
	 * @return void
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
	 * @return void
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
	 * @return void
	 */
	abstract function truncate( $URL=null );

	/**
	 * @abstract
	 * @return mixed
	 */
	abstract function helper_getCreateCommand();

	/**
	 * @abstract
	 * @return void
	 */
	abstract function helper_create();
}