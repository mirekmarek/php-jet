<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Closure;

/**
 *
 */
abstract class Form_Renderer_Single extends Form_Renderer
{
	/**
	 * @var ?string
	 */
	protected ?string $view_script = null;
	

	protected ?Closure $custom_renderer = null;


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
	 * @return Closure|null
	 */
	public function getCustomRenderer(): ?Closure
	{
		return $this->custom_renderer;
	}
	
	/**
	 * @param Closure|null $custom_renderer
	 * @return void
	 */
	public function setCustomRenderer( ?Closure $custom_renderer ): void
	{
		$this->custom_renderer = $custom_renderer;
	}
	
	/**
	 * @return string
	 */
	public function render(): string
	{
		if($this->custom_renderer) {
			ob_start();
			$this->custom_renderer->call( $this );
			return ob_get_clean();
		} else {
			return $this->renderByView();
		}
	}
	
	public function renderByView() : string
	{
		try {
			return $this->getView()->render( $this->getViewScript() );
		} catch( \Exception $e ) {
			Debug_ErrorHandler::handleException( $e );
			die();
		}
		
	}

}