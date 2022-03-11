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
class UI_dialog extends UI_Renderer_Pair
{

	/**
	 * @var string
	 */
	protected string $title = '';

	/**
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * @var string
	 */
	protected string $view_script_footer = '';


	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 */
	public function __construct( string $id, string $title, int $width )
	{
		$this->id = $id;
		$this->title = $title;
		$this->width = $width;
		$this->view_script_start = SysConf_Jet_UI_DefaultViews::get('dialog', 'start');
		$this->view_script_footer = SysConf_Jet_UI_DefaultViews::get('dialog', 'footer');
		$this->view_script_end = SysConf_Jet_UI_DefaultViews::get('dialog', 'end');
	}



	/**
	 * @return string
	 */
	public function getViewScriptFooter(): string
	{
		return $this->view_script_footer;
	}

	/**
	 * @param string $view_script_footer
	 */
	public function setViewScriptFooter( string $view_script_footer ): void
	{
		$this->view_script_footer = $view_script_footer;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
	}

	/**
	 * @return string
	 */
	public function footer(): string
	{
		return $this->getView()->render( $this->getViewScriptFooter() );
	}

}