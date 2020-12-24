<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public function getController() : Mvc_Controller|null
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
	 * @throws Mvc_View_Exception
	 *
	 * @return string
	 */
	public function render( string $script_name ) : string
	{
		$this->setScriptName( $script_name );
		$this->getScriptPath();

		ob_start();

		if( static::getAddScriptPathInfoEnabled() ) {
			echo '<!-- VIEW START: '.$this->_script_path.' -->';
		}


		/** @noinspection PhpIncludeInspection */
		require $this->_script_path;

		if( static::getAddScriptPathInfoEnabled() ) {
			echo '<!-- VIEW END: '.$this->_script_path.' -->';
		}

		$result = ob_get_clean();

		$this->handlePostprocessors( $result );

		return $result;
	}

}