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
class UI_tabsJS_content extends UI_Renderer_Pair
{
	protected UI_tabsJS_tab $tab;
	
	public function __construct( string $id, UI_tabsJS_tab $tab )
	{
		$this->id = $id;
		
		$this->tab = $tab;
		
		$this->view_script_start = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'content/start');
		$this->view_script_end = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'content/end');
	}
	
	public function getIsSelected() : bool
	{
		return $this->tab->getIsSelected();
	}
}