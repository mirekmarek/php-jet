<?php 
/**
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

class Form_Field_Password extends Form_Field_Abstract {

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_PASSWORD;


	/**
	 * @var bool
	 */
	protected $is_required = true;

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => ''
	];


	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'password' );
		$tag_data->setProperty( 'value', '' );
		if($this->getIsReadonly()) {
			$tag_data->setProperty( 'readonly', 'readonly' );
		}

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';

	}


	/**
	 * @param string|null $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {
		if(!$template) {
			$template = $this->__form->getTemplate_field();
		}

		$result = Data_Text::replaceData($template, [
			'LABEL' => '<jet_form_field_label name="'.$this->_name.'"/>',
			'FIELD' => '<jet_form_field_error_msg name="'.$this->_name.'"/>'
					  .'<jet_form_field name="'.$this->_name.'" class="form-control"/>'
		]);

		return $result;

	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		return $codes;
	}

}