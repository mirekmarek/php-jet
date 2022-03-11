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
class UI_tabsJS extends UI_Renderer_Pair
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
		
		$this->view_script_start = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'start');
		$this->view_script_end = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'end');
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
}