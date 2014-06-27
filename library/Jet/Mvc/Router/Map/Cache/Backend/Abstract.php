<?php
/**
 *
 *
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
 * @subpackage Mvc_Router
 */
namespace Jet;

/**
 * Class Mvc_Router_Cache_Backend_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Router_Map_Cache_Backend_Abstract'
 */
abstract class Mvc_Router_Map_Cache_Backend_Abstract extends Object {

	/**
	 * @var Mvc_Router_Map_Cache_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 *
	 * @param Mvc_Router_Map_Cache_Backend_Config_Abstract $config
	 *
	 */
	public function  __construct( Mvc_Router_Map_Cache_Backend_Config_Abstract $config ) {
		$this->config = $config;

		$this->initialize();
	}

	/**
	 * Initializes the cache backend
	 *
	 * @abstract
	 *
	 */
	abstract public function initialize();

	/**
	 * @abstract
	 *
	 * @return  Mvc_Router_Map_Abstract|null
	 */
	abstract public function load();

	/**
	 *
	 * @abstract
	 *
	 * @param Mvc_Router_Map_Abstract $item
	 *
	 */
	abstract public function save( Mvc_Router_Map_Abstract $item );

	/**
	 *
	 * @abstract
	 *
	 */
	abstract public function truncate();

	/**
	 * @abstract
	 * @return mixed
	 */
	abstract public function helper_getCreateCommand();

	/**
	 * @abstract
	 *
	 */
	abstract public function helper_create();
}