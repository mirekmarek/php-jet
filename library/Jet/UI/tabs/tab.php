<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $default_renderer_script = 'tabs/tab';


	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var bool
	 */
	protected $is_selected = false;

	/**
	 * @var callable
	 */
	protected $tab_url_creator;

	/**
	 *
	 * @param string   $id
	 * @param string   $title
	 * @param callable $tab_url_creator
	 */
	public function __construct( $id, $title, callable $tab_url_creator)
	{
		$this->id = $id;
		$this->title = $title;
		$this->tab_url_creator = $tab_url_creator;
	}


	/**
	 * @return bool
	 */
	public function getIsSelected()
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( $is_selected )
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		$creator = $this->tab_url_creator;

		return $creator( $this->id );
	}

}