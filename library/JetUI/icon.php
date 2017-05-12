<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;


/**
 * Class icon
 * @package JetUI
 */
class icon extends BaseElement
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
	 * @return icon
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
	 * @return icon
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
	 * @return icon
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
	 * @return icon
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