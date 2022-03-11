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
class UI_searchField extends UI_Renderer_Single
{

	/**
	 * @var string
	 */
	protected string $placeholder = '';

	/**
	 * @var string
	 */
	protected string $name = '';
	/**
	 * @var string|null
	 */
	protected ?string $value = null;


	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct( string $name, string $value )
	{
		$this->name = $name;
		$this->value = $value;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('search-field');
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		if( !$this->placeholder ) {
			return '';
		}

		return $this->placeholder;
	}

	/**
	 * @param string $placeholder
	 *
	 * @return static
	 */
	public function setPlaceholder( string $placeholder ): static
	{
		$this->placeholder = $placeholder;

		return $this;
	}
	
	protected function generateTagAttributes_Standard(): void
	{
		parent::generateTagAttributes_Standard();
		
		$this->_tag_attributes['name'] = $this->name;
		$this->_tag_attributes['value'] = $this->value;
		
		if($this->placeholder) {
			$this->_tag_attributes['placeholder'] = $this->placeholder;
		}
		
	}
}