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
abstract class Form_Renderer_Pair extends Form_Renderer
{
	/**
	 * @var ?string
	 */
	protected ?string $view_script_start = null;

	/**
	 * @var ?string
	 */
	protected ?string $view_script_end = null;

	/**
	 * @return string
	 */
	public function getViewScriptStart(): string
	{
		return $this->view_script_start;
	}

	/**
	 * @param string $view_script_start
	 *
	 * @return $this
	 */
	public function setViewScriptStart( string $view_script_start ): static
	{
		$this->view_script_start = $view_script_start;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getViewScriptEnd(): string
	{
		return $this->view_script_end;
	}

	/**
	 * @param string $view_script_end
	 *
	 * @return $this
	 */
	public function setViewScriptEnd( string $view_script_end ): static
	{
		$this->view_script_end = $view_script_end;

		return $this;
	}


	/**
	 * @return string
	 */
	public function start(): string
	{
		try {
			return $this->getView()->render( $this->getViewScriptStart() );
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException( $e );
			die();
		}
	}


	/**
	 * @return string
	 */
	public function end(): string
	{
		try {
			return $this->getView()->render( $this->getViewScriptEnd() );
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException( $e );
			die();
		}
	}

}