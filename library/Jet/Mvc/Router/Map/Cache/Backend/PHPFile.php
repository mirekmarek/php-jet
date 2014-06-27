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
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Map_Cache_Backend_PHPFile extends Mvc_Router_Map_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Map_Cache_Backend_PHPFile_Config
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $file_path = '';

	/**
	 * Initializes the cache backend
	 *
	 * @abstract
	 *
	 */
	public function initialize() {
		$this->file_path = $this->config->getPath();
	}

	/**
	 *
	 * @return  Mvc_Router_Map_Abstract|null
	 */
	public function load() {

		if(is_readable($this->file_path)) {
			/** @noinspection PhpIncludeInspection */
			$data = require $this->file_path;

			if( $data instanceof Mvc_Router_Map_Abstract ) {
				return $data;
			}

			$this->truncate();

		}

		return null;
	}

	/**
	 *
	 * @param Mvc_Router_Map_Abstract $item
	 *
	 */
	public function save( Mvc_Router_Map_Abstract $item ) {

		$data = '<?php'.JET_EOL.' return unserialize(\''.serialize( $item).'\');'.JET_EOL;

		IO_File::write($this->file_path, $data);
	}

	/**
	 *
	 */
	public function truncate() {
		if(IO_File::exists($this->file_path)) {
			IO_File::delete($this->file_path);

		}
	}

	/**
	 * @return mixed
	 */
	public function helper_getCreateCommand() {

	}

	/**
	 *
	 */
	public function helper_create() {
	}

}