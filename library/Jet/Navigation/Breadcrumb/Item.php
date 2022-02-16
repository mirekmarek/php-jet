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
class Navigation_Breadcrumb_Item extends BaseObject
{

	/**
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 *
	 * @var string
	 */
	protected string $URL = '';

	/**
	 *
	 * @var ?MVC_Page_Interface
	 */
	protected ?MVC_Page_Interface $page = null;

	/**
	 * @var int
	 */
	protected int $index = 0;

	/**
	 * @var bool
	 */
	protected bool $is_active = false;

	/**
	 *
	 * @var bool
	 */
	protected bool $is_last = false;

	/**
	 *
	 * @return MVC_Page_Interface|null
	 */
	public function getPage(): MVC_Page_Interface|null
	{
		return $this->page;
	}

	/**
	 * @param MVC_Page_Interface $page
	 */
	public function setPage( MVC_Page_Interface $page ): void
	{
		$this->page = $page;
		$this->URL = $page->getURL();
		$this->title = $page->getBreadcrumbTitle();
	}

	/**
	 *
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 *
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = $title;
	}

	/**
	 *
	 * @return string
	 */
	public function getURL(): string
	{
		return $this->URL;
	}

	/**
	 *
	 * @param string $URL
	 */
	public function setURL( string $URL )
	{
		$this->URL = $URL;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsLast(): bool
	{
		return $this->is_last;
	}

	/**
	 *
	 * @param bool $is_last
	 */
	public function setIsLast( bool $is_last ): void
	{
		$this->is_last = $is_last;
	}

	/**
	 * @return int
	 */
	public function getIndex(): int
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( int $index ): void
	{
		$this->index = $index;
	}

	/**
	 * @return bool
	 */
	public function getIsActive(): bool
	{
		return $this->is_active || $this->is_last;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void
	{
		$this->is_active = $is_active;
	}


}