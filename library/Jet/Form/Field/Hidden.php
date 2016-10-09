<?php
/**
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

class Form_Field_Hidden extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_HIDDEN;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_INVALID_FORMAT => ''
	];


	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {
		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'hidden' );
		$tag_data->setProperty( 'value', $this->getValue() );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';

	}

	/**
	 * @return string
	 */
	public function helper_getFormCellHTMLPrefix() {
		return '';
	}

	/**
	 * @return string
	 */
	public function helper_getFormCellHTMLSuffix() {
		return '';
	}

	/**
	 * @param null $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {
		return JET_TAB.'<jet_form_field name="'.$this->_name.'"/>'.JET_EOL;

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

		if($this->validation_regexp) {
			$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		}

		return $codes;
	}

}