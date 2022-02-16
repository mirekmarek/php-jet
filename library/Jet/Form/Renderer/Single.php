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
abstract class Form_Renderer_Single extends Form_Renderer
{
	/**
	 * @var ?string
	 */
	protected ?string $view_script = null;


	/**
	 * @return string
	 */
	public function getViewScript(): string
	{
		return $this->view_script;
	}


	/**
	 * @param string $view_script
	 *
	 * @return $this
	 */
	public function setViewScript( string $view_script ): static
	{
		$this->view_script = $view_script;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		try {
			return $this->getView()->render( $this->getViewScript() );
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException( $e );
			die();
		}

	}

}