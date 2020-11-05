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
class UI_tabsJS_tab extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tabsJS/tab';

	/**
	 * @var string
	 */
	protected static $default_content_start_renderer_script = 'tabsJS/tab/content_start';

	/**
	 * @var string
	 */
	protected static $default_content_end_renderer_script = 'tabsJS/tab/content_end';


	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var bool
	 */
	protected $is_selected = false;

	/**
	 * @var string
	 */
	protected $content_start_renderer_script = '';

	/**
	 * @var string
	 */
	protected $content_end_renderer_script = '';

	/**
	 * @return string
	 */
	public static function getDefaultContentStartRendererScript(): string
	{
		return static::$default_content_start_renderer_script;
	}

	/**
	 * @param string $default_content_start_renderer_script
	 */
	public static function setDefaultContentStartRendererScript( string $default_content_start_renderer_script ): void
	{
		static::$default_content_start_renderer_script = $default_content_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultContentEndRendererScript(): string
	{
		return static::$default_content_end_renderer_script;
	}

	/**
	 * @param string $default_content_end_renderer_script
	 */
	public static function setDefaultContentEndRendererScript( string $default_content_end_renderer_script ): void
	{
		static::$default_content_end_renderer_script = $default_content_end_renderer_script;
	}



	/**
	 *
	 * @param string   $id
	 * @param string   $title
	 */
	public function __construct( $id, $title )
	{
		$this->id = $id;
		$this->title = $title;
	}


	/**
	 * @return bool
	 */
	public function getIsSelected()
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( $is_selected )
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getContentStartRendererScript()
	{
		if(!$this->content_start_renderer_script) {
			$this->content_start_renderer_script = static::getDefaultContentStartRendererScript();
		}

		return $this->content_start_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentStartRendererScript( $script )
	{
		$this->content_start_renderer_script = $script;
	}


	/**
	 * @return string
	 */
	public function getContentEndRendererScript()
	{
		if(!$this->content_end_renderer_script) {
			$this->content_end_renderer_script = static::getDefaultContentEndRendererScript();
		}

		return $this->content_end_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentEndRendererScript( $script )
	{
		$this->content_end_renderer_script = $script;
	}

	/**
	 * @return string
	 */
	public function contentStart()
	{
		return $this->getView()->render($this->getContentStartRendererScript());
	}

	/**
	 * @return string
	 */
	public function contentEnd()
	{
		return $this->getView()->render($this->getContentEndRendererScript());
	}

}