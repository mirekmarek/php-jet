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
class UI_searchField extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'searchField';

	/**
	 * @var string
	 */
	protected $renderer_script;


	/**
	 * @var string
	 */
	protected $search_key = 'search';

	/**
	 * @var string
	 */
	protected static $default_placeholder = 'Search for...';

	/**
	 * @var string
	 */
	protected $placeholder = '';

	/**
	 * @var string
	 */
	protected $name = '';
	/**
	 * @var string
	 */
	protected $value;

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
	public static function getDefaultPlaceholder()
	{
		return static::$default_placeholder;
	}

	/**
	 * @param string $default_placeholder
	 */
	public static function setDefaultPlaceholder( $default_placeholder )
	{
		static::$default_placeholder = $default_placeholder;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct( $name, $value )
	{
		$this->name = $name;
		$this->value = $value;
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
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder()
	{
		if(!$this->placeholder) {
			$this->placeholder = static::getDefaultPlaceholder();
		}

		return UI::_( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 *
	 * @return $this
	 */
	public function setPlaceholder( $placeholder )
	{
		$this->placeholder = $placeholder;

		return $this;
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
	public function getAction()
	{
		return Http_Request::currentURI();
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