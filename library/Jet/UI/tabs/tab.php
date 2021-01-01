<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class UI_tabs_tab extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'tabs/tab';


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
	}


	/**
	 * @return bool
	 */
	public function getIsSelected() : bool
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( bool $is_selected ) : void
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ) : void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ) : void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getUrl() : string
	{
		$creator = $this->tab_url_creator;

		return $creator( $this->id );
	}

}