<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_View
 * @package Jet
 */
class Mvc_View extends Mvc_View_Abstract {


	/**
	* Constructor
	*
	* @param string $scripts_dir
	*/
	public function __construct( $scripts_dir ) {
		$this->setScriptsDir($scripts_dir);


		$this->_data = new Data_Array();
	}


	/**
	*
	* @param string $script_name
	*
	* @throws Mvc_View_Exception
	*
	* @return string
	*/
	public function render( $script_name ) {
		$this->setScriptName($script_name);
		$this->getScriptPath();

		ob_start();

		if(static::$_add_script_path_info) {
			echo JET_EOL.'<!-- VIEW START: '.$this->_script_path.' -->'.JET_EOL.JET_EOL;
		}


		/** @noinspection PhpIncludeInspection */
		include $this->_script_path;

		if(static::$_add_script_path_info) {
			echo JET_EOL.'<!-- VIEW END: '.$this->_script_path.' --> '.JET_EOL;
		}

		$result = ob_get_clean();

		$this->handlePostprocessors($result);

		return $result;
	}

}