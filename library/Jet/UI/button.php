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
class UI_button extends UI_BaseElement
{

	const SIZE_LARGE = 'lg';
	const SIZE_NORMAL = 'normal';
	const SIZE_SMALL = 'sm';
	const SIZE_EXTRA_SMALL = 'xs';

	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'button';

	/**
	 * @var string
	 */
	protected $type = 'button';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $class = '';

	/**
	 * @var string
	 */
	protected $size = self::SIZE_NORMAL;

	/**
	 * @var string
	 */
	protected $icon = '';


	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * @param string $label
	 */
	public function __construct( $label )
	{
		$this->label = $label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function setLabel( $label )
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function setType( $type )
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass( $class )
	{
		$this->class = $class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param string $size
	 *
	 * @return UI_button
	 */
	public function setSize( $size )
	{
		$this->size = $size;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $onclick
	 *
	 * @return $this
	 */
	public function setOnclick( $onclick )
	{
		$this->setJsAction('onclick', $onclick);

		return $this;
	}


	/**
	 * @param string $url
	 *
	 * @return $this
	 */
	public function setUrl( $url )
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}


}