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
class UI_tabsJS_tab extends UI_Renderer_Single
{
	
	/**
	 * @var string
	 */
	protected string $title = '';

	/**
	 * @var bool
	 */
	protected bool $is_selected = false;
	
	/**
	 * @var UI_tabsJS_content
	 */
	protected UI_tabsJS_content $content;

	/**
	 *
	 * @param string $id
	 * @param string $title
	 */
	public function __construct( string $id, string $title )
	{
		$this->id = $id.'_tab';
		$this->title = $title;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js', 'tab');
		
		$this->content = new UI_tabsJS_content( $id, $this );
	}


	/**
	 * @return bool
	 */
	public function getIsSelected(): bool
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( bool $is_selected ): void
	{
		$this->is_selected = $is_selected;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = $title;
	}
	
	/**
	 * @return UI_tabsJS_content
	 */
	public function content(): UI_tabsJS_content
	{
		return $this->content;
	}
	
	
}