<?php
/**
 *
 *
 *
 * @see Mvc/readme.txt
 *
 * NOTICE: @see Mvc_View_Postprocessor_Interface
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_View
 */
namespace Jet;

class Mvc_View extends Mvc_View_Abstract {

	/**
	 * @var Mvc_Layout
	 */
	protected $layout;

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
	 * @return Mvc_Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @param Mvc_Layout Mvc_Layout $layout
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
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
			echo JET_EOL.'<!-- VIEW START: '.$this->_script_path.' -->'.JET_EOL;
		}


		/** @noinspection PhpIncludeInspection */
		include $this->_script_path;

		if(static::$_add_script_path_info) {
			echo JET_EOL.'<!-- VIEW END: '.$this->_script_path.' --> '.JET_EOL;
		}

		$result = ob_get_clean();

        $this->handleParts($result);
		$this->handleModules($result);

		foreach( $this->_data->getRawData() as $item ) {

			if(
				is_object($item) &&
				($item instanceof Mvc_View_Postprocessor_Interface)
			) {
				/**
				 * @var Mvc_View_Postprocessor_Interface $item
				 */
				$item->viewPostProcess( $result, $this );
			}
		}

		if(
			($this->layout) &&
			($router = $this->layout->getRouter()) &&
			($dispatcher = $router->getDispatcherInstance()) &&
			($current_queue_item = $dispatcher->getCurrentQueueItem())
		) {
			$module_name = $current_queue_item->getModuleName();
			$data = array(
				'JET_CURRENT_MODULE_NAME' => $module_name
			);
			$result = Data_Text::replaceData($result, $data );
		}


		return $result;
	}
}