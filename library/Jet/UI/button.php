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
	public const TYPE_BUTTON = 'button';
	public const TYPE_SUBMIT = 'submit';
	public const TYPE_RESET = 'reset';
	
	public const CLASS_PRIMARY = 'primary';
	public const CLASS_SECONDARY = 'secondary';
	public const CLASS_SUCCESS = 'success';
	public const CLASS_DANGER = 'danger';
	public const CLASS_WARNING = 'warning';
	public const CLASS_INFO = 'info';
	public const CLASS_LIGHT = 'light';
	public const CLASS_DARK = 'dark';
	public const CLASS_LINK = 'link';
	
	public const SIZE_SMALL = 'sm';
	public const SIZE_NORMAL = 'normal';
	public const SIZE_LARGE = 'lg';
	public const SIZE_EXTRA_SMALL = 'xs';

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
	 * @var array<string|mixed>|null
	 */
	protected ?array $post_data = null;

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
	
	/**
	 * @return array<string,mixed>|null
	 */
	public function getPostData(): ?array
	{
		return $this->post_data;
	}
	
	/**
	 * @param array<string,mixed>|null $post_data
	 *
	 * @return $this
	 */
	public function setPostData( ?array $post_data ): static
	{
		$this->post_data = $post_data;
		
		return $this;
	}
	
	
}