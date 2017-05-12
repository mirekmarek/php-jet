<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Navigation_Breadcrumb_Item extends BaseObject
{

	/**
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @var string
	 */
	protected $URL = '';

	/**
	 *
	 * @var Mvc_Page_Interface
	 */
	protected $page = null;

	/**
	 * @var int
	 */
	protected $index = 0;

	/**
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_last = false;

	/**
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page )
	{
		$this->page = $page;
		$this->URL = $page->getURL();
		$this->title = $page->getBreadcrumbTitle();
	}

	/**
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 *
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 *
	 * @return string
	 */
	public function getURL()
	{
		return $this->URL;
	}

	/**
	 *
	 * @param string $URL
	 */
	public function setURL( $URL )
	{
		$this->URL = $URL;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsLast()
	{
		return $this->is_last;
	}

	/**
	 *
	 * @param bool $is_last
	 */
	public function setIsLast( $is_last )
	{
		$this->is_last = (bool)$is_last;
	}

	/**
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( $index )
	{
		$this->index = $index;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active||$this->is_last;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = $is_active;
	}


}