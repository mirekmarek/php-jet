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
class UI_searchField extends BaseObject
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'searchField';

	/**
	 * @var string|null
	 */
	protected ?string $renderer_script = null;


	/**
	 * @var string
	 */
	protected string $search_key = 'search';

	/**
	 * @var string
	 */
	protected static string $default_placeholder = 'Search for...';

	/**
	 * @var string
	 */
	protected string $placeholder = '';

	/**
	 * @var string
	 */
	protected string $name = '';
	/**
	 * @var string|null
	 */
	protected ?string $value = null;

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
	public static function getDefaultPlaceholder(): string
	{
		return static::$default_placeholder;
	}

	/**
	 * @param string $default_placeholder
	 */
	public static function setDefaultPlaceholder( string $default_placeholder ): void
	{
		static::$default_placeholder = $default_placeholder;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct( string $name, string $value )
	{
		$this->name = $name;
		$this->value = $value;
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
	 */
	public function setRendererScript( string $renderer_script ): void
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		if( !$this->placeholder ) {
			$this->placeholder = static::getDefaultPlaceholder();
		}

		return UI::_( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 *
	 * @return static
	 */
	public function setPlaceholder( string $placeholder ): static
	{
		$this->placeholder = $placeholder;

		return $this;
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
	public function getAction(): string
	{
		return Http_Request::currentURI();
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