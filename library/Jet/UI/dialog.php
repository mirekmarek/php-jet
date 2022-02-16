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
class UI_dialog extends BaseObject
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
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * @var string
	 */
	protected string $view_script_start = '';

	/**
	 * @var string
	 */
	protected string $view_script_footer = '';

	/**
	 * @var string
	 */
	protected string $view_script_end = '';


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
	}

	/**
	 * @return string
	 */
	public function getViewScriptStart(): string
	{
		if( !$this->view_script_start ) {
			$this->view_script_start = SysConf_Jet_UI_DefaultViews::get('dialog', 'start');
		}

		return $this->view_script_start;
	}

	/**
	 * @param string $view_script_start
	 */
	public function setViewScriptStart( string $view_script_start ): void
	{
		$this->view_script_start = $view_script_start;
	}

	/**
	 * @return string
	 */
	public function getViewScriptFooter(): string
	{
		if( !$this->view_script_footer ) {
			$this->view_script_footer = SysConf_Jet_UI_DefaultViews::get('dialog', 'footer');
		}

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
	public function getViewScriptEnd(): string
	{
		if( !$this->view_script_end ) {
			$this->view_script_end = SysConf_Jet_UI_DefaultViews::get('dialog', 'end');
		}

		return $this->view_script_end;
	}

	/**
	 * @param string $view_script_end
	 */
	public function setViewScriptEnd( string $view_script_end ): void
	{
		$this->view_script_end = $view_script_end;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
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
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;

	}


	/**
	 * @return string
	 */
	public function start(): string
	{
		return $this->getView()->render( $this->getViewScriptStart() );
	}

	/**
	 * @return string
	 */
	public function footer(): string
	{
		return $this->getView()->render( $this->getViewScriptFooter() );
	}

	/**
	 * @return string
	 */
	public function end(): string
	{
		return $this->getView()->render( $this->getViewScriptEnd() );
	}

}