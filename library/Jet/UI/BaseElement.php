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
abstract class UI_BaseElement extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $default_renderer_script = '';

	/**
	 * @var string
	 */
	protected $renderer_script;

	/**
	 * @var array
	 */
	protected $js_actions = [];

	/**
	 * @return string
	 */
	public static function getDefaultRendererScript()
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( $default_renderer_script )
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRendererScript()
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 *
	 * @return $this
	 */
	public function setRendererScript( $renderer_script )
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
	public function setJsAction( $event, $handler_code )
	{
		/**
		 * @var UI_icon $this
		 */

		$event = strtolower( $event );

		if( !isset( $this->js_actions[$event] ) ) {
			$this->js_actions[$event] = $handler_code;
		} else {
			$this->js_actions[$event] .= ';'.$handler_code;

		}

		return $this;
	}

	/**
	 * @param bool $as_string (optional)
	 *
	 * @return array|string
	 */
	public function getJsActions( $as_string=true )
	{
		if($as_string) {
			$js_actions = [];

			foreach( $this->js_actions as $vent => $handler ) {
				$js_actions[] = ' '.$vent.'="'.$handler.'"';
			}

			return implode( '', $js_actions );
		}

		return $this->js_actions;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() {

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;
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
	public function toString()
	{
		return $this->getView()->render($this->getRendererScript());
	}


}