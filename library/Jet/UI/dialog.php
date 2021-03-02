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
class UI_dialog extends BaseObject
{

	/**
	 * @var string
	 */
	protected static string $default_renderer_script_start = 'dialog/start';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script_footer = 'dialog/footer';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script_end = 'dialog/end';

	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var string
	 */
	protected string $title = '';

	/**
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * @var string
	 */
	protected string $renderer_script_start = 'dialog/start';

	/**
	 * @var string
	 */
	protected string $renderer_script_footer = 'dialog/footer';

	/**
	 * @var string
	 */
	protected string $renderer_script_end = 'dialog/end';


	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptStart(): string
	{
		return static::$default_renderer_script_start;
	}

	/**
	 * @param string $default_renderer_script_start
	 */
	public static function setDefaultRendererScriptStart( string $default_renderer_script_start ): void
	{
		static::$default_renderer_script_start = $default_renderer_script_start;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptFooter(): string
	{
		return static::$default_renderer_script_footer;
	}

	/**
	 * @param string $default_renderer_script_footer
	 */
	public static function setDefaultRendererScriptFooter( string $default_renderer_script_footer ): void
	{
		static::$default_renderer_script_footer = $default_renderer_script_footer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptEnd(): string
	{
		return static::$default_renderer_script_end;
	}

	/**
	 * @param string $default_renderer_script_end
	 */
	public static function setDefaultRendererScriptEnd( string $default_renderer_script_end ): void
	{
		static::$default_renderer_script_end = $default_renderer_script_end;
	}

	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 */
	public function __construct( string $id, string $title, int $width )
	{
		$this->id = $id;
		$this->title = $title;
		$this->width = $width;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptStart(): string
	{
		if( !$this->renderer_script_start ) {
			$this->renderer_script_start = static::getDefaultRendererScriptStart();
		}

		return $this->renderer_script_start;
	}

	/**
	 * @param string $renderer_script_start
	 */
	public function setRendererScriptStart( string $renderer_script_start ): void
	{
		$this->renderer_script_start = $renderer_script_start;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptFooter(): string
	{
		if( !$this->renderer_script_footer ) {
			$this->renderer_script_footer = static::getDefaultRendererScriptFooter();
		}

		return $this->renderer_script_footer;
	}

	/**
	 * @param string $renderer_script_footer
	 */
	public function setRendererScriptFooter( string $renderer_script_footer ): void
	{
		$this->renderer_script_footer = $renderer_script_footer;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptEnd(): string
	{
		if( !$this->renderer_script_end ) {
			$this->renderer_script_end = static::getDefaultRendererScriptEnd();
		}

		return $this->renderer_script_end;
	}

	/**
	 * @param string $renderer_script_end
	 */
	public function setRendererScriptEnd( string $renderer_script_end ): void
	{
		$this->renderer_script_end = $renderer_script_end;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
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
	public function start(): string
	{
		return $this->getView()->render( $this->getRendererScriptStart() );
	}

	/**
	 * @return string
	 */
	public function footer(): string
	{
		return $this->getView()->render( $this->getRendererScriptFooter() );
	}

	/**
	 * @return string
	 */
	public function end(): string
	{
		return $this->getView()->render( $this->getRendererScriptEnd() );
	}

}