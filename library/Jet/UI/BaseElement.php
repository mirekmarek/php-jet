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
abstract class UI_BaseElement extends BaseObject
{
	/**
	 * @var ?string
	 */
	protected ?string $view_script = null;

	/**
	 * @var array
	 */
	protected array $js_actions = [];


	/**
	 * @return string
	 */
	abstract public function getViewScript(): string;

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
	 * @param string $event
	 * @param string $handler_code
	 *
	 * @return $this
	 */
	public function setJsAction( string $event, string $handler_code ): static
	{
		/**
		 * @var UI_icon $this
		 */

		$event = strtolower( $event );

		if( !isset( $this->js_actions[$event] ) ) {
			$this->js_actions[$event] = $handler_code;
		} else {
			$this->js_actions[$event] .= ';' . $handler_code;

		}

		return $this;
	}

	/**
	 * @param bool $as_string (optional)
	 *
	 * @return array|string
	 */
	public function getJsActions( bool $as_string = true ): array|string
	{
		if( $as_string ) {
			$js_actions = [];

			foreach( $this->js_actions as $vent => $handler ) {
				$js_actions[] = ' ' . $vent . '="' . $handler . '"';
			}

			return implode( '', $js_actions );
		}

		return $this->js_actions;
	}

	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;
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
		return $this->getView()->render( $this->getViewScript() );
	}


}