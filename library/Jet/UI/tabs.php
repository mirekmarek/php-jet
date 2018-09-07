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
class UI_tabs extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tabs';

	/**
	 * @var UI_tabs_tab[]
	 */
	protected $tabs = [];

	/**
	 * @var string
	 */
	protected $selected_tab_id;


	/**
	 * @param array       $tabs
	 * @param callable    $tab_url_creator
	 * @param string|null $selected_tab_id
	 */
	public function __construct( array $tabs, callable $tab_url_creator, $selected_tab_id=null)
	{
		foreach( $tabs as $id => $title ) {
			$this->tabs[$id] = new UI_tabs_tab( $id, $title, $tab_url_creator );
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
	 * @return UI_tabs_tab
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
	 * @return UI_tabs_tab[]
	 */
	public function getTabs()
	{
		return $this->tabs;
	}

}