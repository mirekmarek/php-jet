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
	 * @var string
	 */
	protected static string $default_renderer_script = '';

	/**
	 * @var ?string
	 */
	protected ?string $renderer_script = null;

	/**
	 * @var array
	 */
	protected array $js_actions = [];

	/**
	 * @return string
	 */
	public static function getDefaultRendererScript(): string
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( string $default_renderer_script ): void
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRendererScript(): string
	{
		if( !$this->renderer_script ) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 *
	 * @return $this
	 */
	public function setRendererScript( string $renderer_script ): static
	{
		$this->renderer_script = $renderer_script;

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
	 * @return Mvc_View
	 */
	public function getView(): Mvc_View
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
		return $this->getView()->render( $this->getRendererScript() );
	}


}