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
class UI_tabsJS_tab extends UI_BaseElement
{

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
	protected string $content_start_view_script = '';

	/**
	 * @var string
	 */
	protected string $content_end_view_script = '';

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
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ): void
	{
		$this->id = $id;
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
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js/tab');
		}

		return $this->view_script;
	}


	/**
	 * @return string
	 */
	public function getContentStartViewScript(): string
	{
		if( !$this->content_start_view_script ) {
			$this->content_start_view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js/tab', 'content_start');
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
			$this->content_end_view_script = SysConf_Jet_UI_DefaultViews::get('tabs-js/tab', 'content_end');
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