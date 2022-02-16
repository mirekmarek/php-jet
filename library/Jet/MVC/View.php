<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class MVC_View extends MVC_View_Abstract
{

	/**
	 * @var ?MVC_Controller
	 */
	protected ?MVC_Controller $controller = null;

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
	 * @return MVC_Controller|null
	 */
	public function getController(): MVC_Controller|null
	{
		return $this->controller;
	}

	/**
	 * @param MVC_Controller $controller
	 */
	public function setController( MVC_Controller $controller ) : void
	{
		$this->controller = $controller;
	}


	/**
	 *
	 * @param string $script_name
	 *
	 * @return string
	 * @throws MVC_View_Exception
	 *
	 */
	public function render( string $script_name ): string
	{
		$this->setScriptName( $script_name );
		$this->getScriptPath();

		ob_start();

		if( SysConf_Jet_MVC_View::getAddScriptPathInfo() ) {
			echo '<!-- VIEW START: ' . $this->_script_path . ' -->';
		}

		require $this->_script_path;

		if( SysConf_Jet_MVC_View::getAddScriptPathInfo() ) {
			echo '<!-- VIEW END: ' . $this->_script_path . ' -->';
		}

		return ob_get_clean();
	}

}