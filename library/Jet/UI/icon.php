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
	const SIZE_EXTRA_SMALL = 'xs';
	const SIZE_SMALL = 'sm';
	const SIZE_NORMAL = 'normal';
	const SIZE_LARGE = 'lg';
	const SIZE_EXTRA_LARGE = 'xl';
	const SIZE_ULTRA_LARGE = 'ul';
	
	/**
	 * @var string
	 */
	protected string $tag = 'span';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var string
	 */
	protected string $size = self::SIZE_NORMAL;

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
	 * @param string $size
	 *
	 * @return static
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