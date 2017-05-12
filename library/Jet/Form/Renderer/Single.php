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
class Form_Renderer_Single extends Form_Renderer
{
	/**
	 * @var string
	 */
	protected $view_script;



	/**
	 * @return string
	 */
	public function getViewScript()
	{
		return $this->view_script;
	}


	/**
	 * @param string $view_script
	 *
	 * @return $this
	 */
	public function setViewScript( $view_script )
	{
		$this->view_script = $view_script;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render()
	{
		try {
			return $this->getView()->render($this->getViewScript());
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException($e);
			die();
		}

	}

}