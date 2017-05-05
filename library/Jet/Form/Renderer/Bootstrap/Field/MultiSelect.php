<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_MultiSelect
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_MultiSelect extends Form_Renderer_Bootstrap_Field_Abstract
{

	/**
	 * @var string
	 */
	protected $tag = 'select';

	/**
	 * @var bool
	 */
	protected $has_content = true;

	/**
	 * @return string
	 */
	public function render()
	{

		$value = $this->_field->getValue();
		$options = $this->_field->getSelectOptions();

		$options_str = '';
		foreach( $options as $val => $label ) {

			$selected = false;

			if( is_array( $value )&&!empty( $value ) ) {
				foreach( $value as $valIn ) {
					if( (string)$val==(string)$valIn ) {
						$selected = true;
						continue;
					}
				}
			} else {
				if( $val==$value ) {
					$selected = true;
				}
			}

			$css = '';
			if( $label instanceof Form_Field_Select_Option_Interface ) {
				/**
				 * @var Form_Field_Select_Option_Interface $label
				 */

				if( ( $class = $label->getSelectOptionCssClass() ) ) {
					$css .= ' class="'.$class.'"';
				}
				if( ( $style = $label->getSelectOptionCssStyle() ) ) {
					$css .= ' style="'.$style.'"';
				}
			}

			if( $selected ) {
				$options_str .= '<option value="'.Data_Text::htmlSpecialChars(
						$val
					).'" '.$css.' selected="selected">'.Data_Text::htmlSpecialChars( $label ).'</option>'.JET_EOL;
			} else {
				$options_str .= '<option value="'.Data_Text::htmlSpecialChars(
						$val
					).'" '.$css.'>'.Data_Text::htmlSpecialChars( $label ).'</option>'.JET_EOL;
			}
		}


		$tag_options = [
			'name' => $this->getTagNameValue().'[]', 'id' => $this->getTagId(), 'multiple' => 'multiple',
		];

		if( $this->_field->getIsReadonly() ) {
			$tag_options['disabled'] = 'disabled';
		}


		$result = $this->render_containerStart().$this->generate( $tag_options, $options_str ).JET_EOL;

		$result .= $this->render_containerEnd().JET_EOL;


		return $result;
	}

}