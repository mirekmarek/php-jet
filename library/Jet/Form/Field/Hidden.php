<?php
/**
 *
 *
 *
 * class representing single form field - type string
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

class Form_Field_Hidden extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = 'Hidden';

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = false;


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

}