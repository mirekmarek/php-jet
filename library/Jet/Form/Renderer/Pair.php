<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Form_Renderer_Pair extends Form_Renderer
{
	/**
	 * @var string
	 */
	protected $view_script_start;

	/**
	 * @var string
	 */
	protected $view_script_end;

	/**
	 * @return string
	 */
	public function getViewScriptStart()
	{
		return $this->view_script_start;
	}

	/**
	 * @param string $view_script_start
	 *
	 * @return $this
	 */
	public function setViewScriptStart( $view_script_start )
	{
		$this->view_script_start = $view_script_start;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getViewScriptEnd()
	{
		return $this->view_script_end;
	}

	/**
	 * @param string $view_script_end
	 *
	 * @return $this
	 */
	public function setViewScriptEnd( $view_script_end )
	{
		$this->view_script_end = $view_script_end;

		return $this;
	}


	/**
	 * @return string
	 */
	public function start() {
		try {
			return $this->getView()->render($this->getViewScriptStart());
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException($e);
			die();
		}
	}


	/**
	 * @return string
	 */
	public function end() {
		try {
			return $this->getView()->render($this->getViewScriptEnd());
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException($e);
			die();
		}
	}

}