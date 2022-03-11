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
class UI_dataGrid_header extends UI_Renderer_Single
{
	protected UI_dataGrid $__grid;
	
	protected string $prepend = '';
	
	protected string $append = '';
	
	public function __construct( UI_dataGrid $grid )
	{
		$this->__grid = $grid;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('data-grid', 'header');
	}
	
	/**
	 * @return UI_dataGrid
	 */
	public function getGrid() : UI_dataGrid
	{
		return $this->__grid;
	}
	
	/**
	 * @return string
	 */
	public function getPrepend(): string
	{
		return $this->prepend;
	}
	
	/**
	 * @param string $prepend
	 *
	 * @return $this
	 */
	public function setPrepend( string $prepend ): static
	{
		$this->prepend = $prepend;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAppend(): string
	{
		return $this->append;
	}
	
	/**
	 * @param string $append
	 *
	 * @return $this
	 */
	public function setAppend( string $append ): static
	{
		$this->append = $append;
		
		return $this;
	}
	
}
