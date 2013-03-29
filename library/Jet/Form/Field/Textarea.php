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

class Form_Field_Textarea extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "Textarea";

	/**
	 * @param string $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field( $tag_data ) {
		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName();
		$properties["id"] = $this->getID();
		
		return '<textarea '
				.$this->_getTagPropertiesAsString($properties, "field")
				.'>'.$this->getValue().'</textarea>';
	}
	
}