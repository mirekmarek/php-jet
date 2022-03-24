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
class UI_button extends UI_Renderer_Single
{
	const TYPE_BUTTON = 'button';
	const TYPE_SUBMIT = 'submit';
	const TYPE_RESET = 'reset';
	
	const CLASS_PRIMARY = 'primary';
	const CLASS_SECONDARY = 'secondary';
	const CLASS_SUCCESS = 'success';
	const CLASS_DANGER = 'danger';
	const CLASS_WARNING = 'warning';
	const CLASS_INFO = 'info';
	const CLASS_LIGHT = 'light';
	const CLASS_DARK = 'dark';
	const CLASS_LINK = 'link';
	
	const SIZE_SMALL = 'sm';
	const SIZE_NORMAL = 'normal';
	const SIZE_LARGE = 'lg';
	const SIZE_EXTRA_SMALL = 'xs';

	/**
	 * @var string
	 */
	protected string $type = self::TYPE_BUTTON;

	/**
	 * @var string
	 */
	protected string $label = '';

	/**
	 * @var string
	 */
	protected string $class = '';

	/**
	 * @var string
	 */
	protected string $size = self::SIZE_NORMAL;

	/**
	 * @var string
	 */
	protected string $icon = '';


	/**
	 * @var string
	 */
	protected string $url = '';

	/**
	 * @param string $label
	 */
	public function __construct( string $label )
	{
		$this->label = $label;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('button' );
	}
	
	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function setLabel( string $label ): static
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function setType( string $type ): static
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass( string $class ): static
	{
		$this->class = $class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClass(): string
	{
		return $this->class;
	}

	/**
	 * @param string $size
	 *
	 * @return $this
	 */
	public function setSize( string $size ): static
	{
		$this->size = $size;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSize(): string
	{
		return $this->size;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon( string $icon ): static
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @param string $onclick
	 *
	 * @return $this
	 */
	public function setOnClick( string $onclick ): static
	{
		$this->addJsAction( 'onclick', $onclick );

		return $this;
	}


	/**
	 * @param string $url
	 *
	 * @return $this
	 */
	public function setUrl( string $url ): static
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}
}