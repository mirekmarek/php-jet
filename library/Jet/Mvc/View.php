<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Mvc_View extends Mvc_View_Abstract
{

	/**
	 * @var ?Mvc_Controller
	 */
	protected ?Mvc_Controller $controller = null;

	/**
	 *
	 * @param string $scripts_dir
	 */
	public function __construct( string $scripts_dir )
	{
		$this->setScriptsDir( $scripts_dir );


		$this->_data = new Data_Array();
	}

	/**
	 * @return Mvc_Controller|null
	 */
	public function getController(): Mvc_Controller|null
	{
		return $this->controller;
	}

	/**
	 * @param Mvc_Controller $controller
	 */
	public function setController( Mvc_Controller $controller )
	{
		$this->controller = $controller;
	}


	/**
	 *
	 * @param string $script_name
	 *
	 * @return string
	 * @throws Mvc_View_Exception
	 *
	 */
	public function render( string $script_name ): string
	{
		$this->setScriptName( $script_name );
		$this->getScriptPath();

		ob_start();

		if( SysConf_Jet_Mvc_View::getAddScriptPathInfo() ) {
			echo '<!-- VIEW START: ' . $this->_script_path . ' -->';
		}

		require $this->_script_path;

		if( SysConf_Jet_Mvc_View::getAddScriptPathInfo() ) {
			echo '<!-- VIEW END: ' . $this->_script_path . ' -->';
		}

		return ob_get_clean();
	}

}