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
class UI_tabsJS extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'tabsJS';

	/**
	 * @var string
	 */
	protected static string $default_content_start_renderer_script = 'tabsJS/content_start';

	/**
	 * @var string
	 */
	protected static string $default_content_end_renderer_script = 'tabsJS/content_end';

	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var UI_tabsJS_tab[]
	 */
	protected array $tabs = [];

	/**
	 * @var string
	 */
	protected string $selected_tab_id;

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
	 * @param string $id
	 * @param array $tabs
	 * @param string|null $selected_tab_id
	 */
	public function __construct( string $id, array $tabs, ?string $selected_tab_id = null )
	{
		$this->id = $id;

		foreach( $tabs as $id => $title ) {
			$this->tabs[$id] = new UI_tabsJS_tab( $id, $title );
		}

		$this->selected_tab_id = (string)$selected_tab_id;

		if(
			!$this->selected_tab_id ||
			!isset( $this->tabs[$this->selected_tab_id] )
		) {
			$this->selected_tab_id = array_keys( $this->tabs )[0];
		}

		foreach( $this->tabs as $id => $tab ) {
			$tab->setIsSelected( $id == $this->selected_tab_id );
		}

	}


	/**
	 * @param string $id
	 *
	 * @return UI_tabsJS_tab
	 */
	public function getTab( string $id ): UI_tabsJS_tab
	{
		return $this->getTabs()[$id];
	}

	/**
	 * @param string $id
	 *
	 * @return UI_tabsJS_tab
	 */
	public function tab( string $id ): UI_tabsJS_tab
	{
		return $this->getTabs()[$id];
	}

	/**
	 * @return string
	 */
	public function getSelectedTabId(): string
	{
		return $this->selected_tab_id;
	}

	/**
	 * @return UI_tabsJS_tab[]
	 */
	public function getTabs(): array
	{
		return $this->tabs;
	}

	/**
	 * @return string
	 */
	public function getContentStartRendererScript(): string
	{
		if( !$this->content_start_renderer_script ) {
			$this->content_start_renderer_script = static::getDefaultContentStartRendererScript();
		}

		return $this->content_start_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentStartRendererScript( string $script ): void
	{
		$this->content_start_renderer_script = $script;
	}


	/**
	 * @return string
	 */
	public function getContentEndRendererScript(): string
	{
		if( !$this->content_end_renderer_script ) {
			$this->content_end_renderer_script = static::getDefaultContentEndRendererScript();
		}

		return $this->content_end_renderer_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentEndRendererScript( string $script ): void
	{
		$this->content_end_renderer_script = $script;
	}

	/**
	 * @return string
	 */
	public function contentStart(): string
	{
		return $this->getView()->render( $this->getContentStartRendererScript() );
	}

	/**
	 * @return string
	 */
	public function contentEnd(): string
	{
		return $this->getView()->render( $this->getContentEndRendererScript() );
	}

}