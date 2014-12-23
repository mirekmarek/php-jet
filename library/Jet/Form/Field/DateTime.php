<?php 
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_DateTime extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = 'DateTime';

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		parent::catchValue($data);

		if($this->_has_value) {

			$name = $this->_name.'_time';

			if($data->exists($name)) {
				$this->_value_raw .= ' '.$data->getRaw($name);
				$this->_value .= ' '.trim( $data->getRaw($name) );
			} else {
				$this->_value_raw = null;
				$this->setValueError('input_missing');
			}
		}

		$this->_value = trim($this->_value);

		if($this->_value) {
			$this->_value = date('c',strtotime($this->_value));
		}
	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
		if(!$this->is_required && $this->_value==='') {
			return true;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if(!@strtotime($this->_value)) {
			$this->setValueError('invalid_format');
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

		$value = $this->getValue();

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'text' );
		$tag_data->setProperty( 'value', $value ? date('Y-m-d', strtotime($value)):'' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field_time( Form_Parser_TagData $tag_data ) {

		$value = $this->getValue();

		$tag_data->setProperty( 'name', $this->getName().'_time');
		$tag_data->setProperty( 'id', $this->getID().'_time');
		$tag_data->setProperty( 'type', 'text');
		$tag_data->setProperty( 'value', $value ? 'T'.date('H:i:s', strtotime($value)):'' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';

	}

	/**
	 * @param null|string $template (optional)
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {

		if(!$template) {
			$template = $this->__form->getTemplate_field();
		}

		return Data_Text::replaceData($template, array(
			'LABEL' => '<jet_form_field_label name="'.$this->_name.'"/>',
			'FIELD' =>
				'<jet_form_field_error_msg name="'.$this->_name.'" class="error"/>'.JET_EOL
				.'<jet_form_field name="'.$this->_name.'" class="form-control"/>'.JET_EOL
				.'<jet_form_field_time name="'.$this->_name.'" class="form-control"/>'
		));
	}


}