<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $default_renderer_script_start = 'dialog/start';

	/**
	 * @var string
	 */
	protected static $default_renderer_script_footer = 'dialog/footer';

	/**
	 * @var string
	 */
	protected static $default_renderer_script_end = 'dialog/end';

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var int
	 */
	protected $width = 0;

	/**
	 * @var string
	 */
	protected $renderer_script_start = 'dialog/start';

	/**
	 * @var string
	 */
	protected $renderer_script_footer = 'dialog/footer';

	/**
	 * @var string
	 */
	protected $renderer_script_end = 'dialog/end';


	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptStart()
	{
		return static::$default_renderer_script_start;
	}

	/**
	 * @param string $default_renderer_script_start
	 */
	public static function setDefaultRendererScriptStart( $default_renderer_script_start )
	{
		static::$default_renderer_script_start = $default_renderer_script_start;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptFooter()
	{
		return static::$default_renderer_script_footer;
	}

	/**
	 * @param string $default_renderer_script_footer
	 */
	public static function setDefaultRendererScriptFooter( $default_renderer_script_footer )
	{
		static::$default_renderer_script_footer = $default_renderer_script_footer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptEnd()
	{
		return static::$default_renderer_script_end;
	}

	/**
	 * @param string $default_renderer_script_end
	 */
	public static function setDefaultRendererScriptEnd( $default_renderer_script_end )
	{
		static::$default_renderer_script_end = $default_renderer_script_end;
	}

	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param int    $width
	 */
	public function __construct( $id, $title, $width )
	{
		$this->id = $id;
		$this->title = $title;
		$this->width = $width;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptStart()
	{
		if(!$this->renderer_script_start) {
			$this->renderer_script_start = static::getDefaultRendererScriptStart();
		}

		return $this->renderer_script_start;
	}

	/**
	 * @param string $renderer_script_start
	 */
	public function setRendererScriptStart( $renderer_script_start )
	{
		$this->renderer_script_start = $renderer_script_start;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptFooter()
	{
		if(!$this->renderer_script_footer) {
			$this->renderer_script_footer = static::getDefaultRendererScriptFooter();
		}

		return $this->renderer_script_footer;
	}

	/**
	 * @param string $renderer_script_footer
	 */
	public function setRendererScriptFooter( $renderer_script_footer )
	{
		$this->renderer_script_footer = $renderer_script_footer;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptEnd()
	{
		if(!$this->renderer_script_end) {
			$this->renderer_script_end = static::getDefaultRendererScriptEnd();
		}

		return $this->renderer_script_end;
	}

	/**
	 * @param string $renderer_script_end
	 */
	public function setRendererScriptEnd( $renderer_script_end )
	{
		$this->renderer_script_end = $renderer_script_end;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
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
	public function start()
	{
		return $this->getView()->render( $this->getRendererScriptStart() );
	}

	/**
	 * @return string
	 */
	public function footer()
	{
		return $this->getView()->render( $this->getRendererScriptFooter() );
	}

	/**
	 * @return string
	 */
	public function end()
	{
		return $this->getView()->render( $this->getRendererScriptEnd() );
	}

}