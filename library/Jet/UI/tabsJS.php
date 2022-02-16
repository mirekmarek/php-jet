<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $content_start_view_script = '';

	/**
	 * @var string
	 */
	protected string $content_end_view_script = '';


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
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js');
		}

		return $this->view_script;
	}

	/**
	 * @return string
	 */
	public function getContentStartViewScript(): string
	{
		if( !$this->content_start_view_script ) {
			$this->content_start_view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'content_start');
		}

		return $this->content_start_view_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentStartViewScript( string $script ): void
	{
		$this->content_start_view_script = $script;
	}


	/**
	 * @return string
	 */
	public function getContentEndViewScript(): string
	{
		if( !$this->content_end_view_script ) {
			$this->content_end_view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'content_end');
		}

		return $this->content_end_view_script;
	}

	/**
	 * @param string $script
	 */
	public function setContentEndViewScript( string $script ): void
	{
		$this->content_end_view_script = $script;
	}

	/**
	 * @return string
	 */
	public function contentStart(): string
	{
		return $this->getView()->render( $this->getContentStartViewScript() );
	}

	/**
	 * @return string
	 */
	public function contentEnd(): string
	{
		return $this->getView()->render( $this->getContentEndViewScript() );
	}

}