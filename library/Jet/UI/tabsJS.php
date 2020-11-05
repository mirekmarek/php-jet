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
class UI_tabsJS extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tabsJS';

	/**
	 * @var string
	 */
	protected static $default_content_start_renderer_script = 'tabsJS/content_start';

	/**
	 * @var string
	 */
	protected static $default_content_end_renderer_script = 'tabsJS/content_end';

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var UI_tabsJS_tab[]
	 */
	protected $tabs = [];

	/**
	 * @var string
	 */
	protected $selected_tab_id;

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
	 * @param string      $id
	 * @param array       $tabs
	 * @param string|null $selected_tab_id
	 */
	public function __construct( $id, array $tabs, $selected_tab_id=null)
	{
		$this->id = $id;

		foreach( $tabs as $id => $title ) {
			$this->tabs[$id] = new UI_tabsJS_tab( $id, $title );
		}

		$this->selected_tab_id = (string)$selected_tab_id;

		if(
			!$this->selected_tab_id ||
			!isset($this->tabs[$this->selected_tab_id])
		) {
			$this->selected_tab_id = array_keys($this->tabs)[0];
		}

		foreach( $this->tabs as $id=>$tab ) {
			$tab->setIsSelected( $id==$this->selected_tab_id );
		}

	}


	/**
	 * @param string $id
	 *
	 * @return UI_tabsJS_tab
	 */
	public function getTab( $id )
	{
		return $this->getTabs()[$id];
	}

	/**
	 * @return string
	 */
	public function getSelectedTabId()
	{
		return $this->selected_tab_id;
	}

	/**
	 * @return UI_tabsJS_tab[]
	 */
	public function getTabs()
	{
		return $this->tabs;
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