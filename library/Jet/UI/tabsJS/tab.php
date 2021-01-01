<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static string $default_renderer_script = 'tabsJS/tab';

	/**
	 * @var string
	 */
	protected static string $default_content_start_renderer_script = 'tabsJS/tab/content_start';

	/**
	 * @var string
	 */
	protected static string $default_content_end_renderer_script = 'tabsJS/tab/content_end';


	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var string
	 */
	protected string $title = '';

	/**
	 * @var bool
	 */
	protected bool $is_selected = false;

	/**
	 * @var string
	 */
	protected string $content_start_renderer_script = '';

	/**
	 * @var string
	 */
	protected string $content_end_renderer_script = '';

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
	 * @param string $id
	 * @param string $title
	 */
	public function __construct( string $id, string $title )
	{
		$this->id = $id;
		$this->title = $title;
	}


	/**
	 * @return bool
	 */
	public function getIsSelected() : bool
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( bool $is_selected ) : void
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ) : void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ) : void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getContentStartRendererScript() : string
	{
		if(!$this->content_start_renderer_script) {
			$this->content_start_renderer_script = static::getDefaultContentStartRendererScript();
		}

		return $this->content_start_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentStartRendererScript( string $script ) : void
	{
		$this->content_start_renderer_script = $script;
	}


	/**
	 * @return string
	 */
	public function getContentEndRendererScript() : string
	{
		if(!$this->content_end_renderer_script) {
			$this->content_end_renderer_script = static::getDefaultContentEndRendererScript();
		}

		return $this->content_end_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentEndRendererScript( string $script ) : void
	{
		$this->content_end_renderer_script = $script;
	}

	/**
	 * @return string
	 */
	public function contentStart() : string
	{
		return $this->getView()->render($this->getContentStartRendererScript());
	}

	/**
	 * @return string
	 */
	public function contentEnd() : string
	{
		return $this->getView()->render($this->getContentEndRendererScript());
	}

}