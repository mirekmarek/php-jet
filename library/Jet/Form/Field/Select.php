<?php 
/**
 *
 *
 *
 *
*
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_Select extends Form_Field_Abstract {
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_SELECT;

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => '',
				self::ERROR_CODE_INVALID_VALUE => ''
	];

	/**
	 * @return bool
	 */
	public function validateValue() {
		
		$options = $this->select_options;
		
		if(!isset($options[$this->_value])) {

			$this->setValueError(self::ERROR_CODE_INVALID_VALUE);
			return false;
		}
		
		$this->_setValueIsValid();
		
		return true;
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {
		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		if($this->getIsReadonly()) {
			$tag_data->setProperty( 'disabled', 'disabled' );
		}

		$value = $this->getValue();
		$options = $this->select_options;
				
		$result = '<select '.$this->_getTagPropertiesAsString($tag_data).'>'.JET_EOL;

		foreach($options as $val=>$label) {

			$css = '';
			if($label instanceof Form_Field_Select_Option_Interface) {
				/**
				 * @var Form_Field_Select_Option_Interface $label
				 */

				if( ($class = $label->getSelectOptionCssClass()) ) {
					$css .= ' class="'.$class.'"';
				}
				if( ($style = $label->getSelectOptionCssStyle()) ) {
					$css .= ' style="'.$style.'"';
				}
			}

			if( ((string)$val)==((string)$value) ) {
				$result .= '<option value="'.Data_Text::htmlSpecialChars($val).'" '.$css.' selected="selected">'.Data_Text::htmlSpecialChars($label).'</option>'.JET_EOL;
			} else {
				$result .= '<option value="'.Data_Text::htmlSpecialChars($val).'" '.$css.'>'.Data_Text::htmlSpecialChars($label).'</option>'.JET_EOL;
			}
		}
		$result .= '</select>'.JET_EOL;

		return $result;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_INVALID_VALUE;

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}


		return $codes;
	}

}