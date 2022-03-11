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
class UI_tabs_tab extends UI_Renderer_Single
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
	 * @var callable
	 */
	protected $tab_url_creator;

	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param callable $tab_url_creator
	 */
	public function __construct( string $id, string $title, callable $tab_url_creator )
	{
		$this->id = $id;
		$this->title = $title;
		$this->tab_url_creator = $tab_url_creator;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('tabs/tab' );
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
	 * @return string
	 */
	public function getUrl(): string
	{
		$creator = $this->tab_url_creator;

		return $creator( $this->id );
	}

	protected function generateTagAttributes_Standard(): void
	{
		parent::generateTagAttributes_Standard();
		
		$this->_tag_attributes['href'] = $this->getUrl();
	}
	
	
}