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
class UI_icon extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'icon';

	/**
	 * @var string
	 */
	protected $tag = 'span';

	/**
	 * @var string
	 */
	protected $icon;

	/**
	 * @var int
	 */
	protected $size;

	/**
	 * @var int
	 */
	protected $width;

	/**
	 * @var string
	 */
	protected $color;


	/**
	 * @param string $icon
	 */
	public function __construct( $icon )
	{
		$this->icon = $icon;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}


	/**
	 * @param int $size
	 *
	 * @return UI_icon
	 */
	public function setSize( $size )
	{
		$this->size = (int)$size;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param int $width
	 *
	 * @return UI_icon
	 */
	public function setWidth( $width )
	{
		$this->width = (int)$width;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @param string $color
	 *
	 * @return UI_icon
	 */
	public function setColor( $color )
	{
		$this->color = $color;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor()
	{
		return $this->color;
	}

	/**
	 * @param string $tag
	 *
	 * @return UI_icon
	 */
	public function setTag( $tag )
	{
		$this->tag = $tag;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}

}