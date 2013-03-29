<?php
/**
 *
 *
 *
 * class representing single form field - type string
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	protected $_type = "Hidden";

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = false;

	/**
	 * @var array
	 */
	protected $_tags_list =  array(
			"field"
		);

	/**
	 * @param array $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field( $tag_data ) {
		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName();
		$properties["id"] = $this->getID();
		$properties["type"] = "hidden";
		$properties["value"] = $this->getValue();
				
		return '<input '
				.$this->_getTagPropertiesAsString($properties, "field")
				.'/>';
	}

	/**
	 * @return string
	 */
	public function helper_getFormCellHTMLPrefix() {
		return "";
	}

	/**
	 * @return string
	 */
	public function helper_getFormCellHTMLSuffix() {
		return "";
	}

	/**
	 * @param null $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {
		return "\t<jet_form_field name=\"{$this->_name}\"/>\n";

	}

}