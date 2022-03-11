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
class UI_icon extends UI_Renderer_Single
{

	/**
	 * @var string
	 */
	protected string $tag = 'span';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var int
	 */
	protected int $size = 0;

	/**
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * @var string
	 */
	protected string $color = '';

	/**
	 * @var string
	 */
	protected string $title = '';


	/**
	 * @param string $icon
	 */
	public function __construct( string $icon )
	{
		$this->icon = $icon;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('icon');
	}


	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}


	/**
	 * @param int $size
	 *
	 * @return static
	 */
	public function setSize( int $size ): static
	{
		$this->size = $size;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getSize(): int
	{
		return $this->size;
	}

	/**
	 * @param int $width
	 *
	 * @return static
	 */
	public function setWidth( int $width ): static
	{
		$this->width = $width;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
	}

	/**
	 * @param string $color
	 *
	 * @return static
	 */
	public function setColor( string $color ): static
	{
		$this->color = $color;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor(): string
	{
		return $this->color;
	}

	/**
	 * @param string $tag
	 *
	 * @return static
	 */
	public function setTag( string $tag ): static
	{
		$this->tag = $tag;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTag(): string
	{
		return $this->tag;
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
	 *
	 * @return static
	 */
	public function setTitle( string $title ): static
	{
		$this->title = $title;

		return $this;
	}


}