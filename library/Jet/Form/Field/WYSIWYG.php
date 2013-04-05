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

class Form_Field_WYSIWYG extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "WYSIWYG";

	/**
	 * Editor config name in main configuration /js_libs/TinyMCE/editor_configs/CONFIG_NAME
	 *
	 * @var string
	 */
	protected $editor_config_name = "default";

	/**
	 * @var string
	 */
	protected $WYSIWYG_editor = "TinyMCE";

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {
		$this->__form->getLayout()->requireJavascriptLib( $this->WYSIWYG_editor );

		$tag_data->setProperty("name", $this->getName());
		$tag_data->setProperty("id", $this->getID());


		$result = "<textarea {$this->_getTagPropertiesAsString( $tag_data )}>{$this->getValue()}</textarea>\n";
		$result .= "<script type=\"text/javascript\">jet_WYSIWYG.init(".json_encode($this->getID()).",".json_encode($this->editor_config_name).");</script>\n";

		return $result;
	}

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = $data->exists($this->_name);

		if($this->_has_value) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = trim( $data->getRaw($this->_name) );
		} else {
			$this->_value_raw = null;
			$this->setValueError("input_missing");
		}
	}


	/**
	 * @param $editor_config_name
	 */
	public function setEditorConfigName($editor_config_name) {
		$this->editor_config_name = $editor_config_name;
	}

	/**
	 * @return string
	 */
	public function getEditorConfigName() {
		return $this->editor_config_name;
	}

	/**
	 * @param string $WYSIWYG_editor
	 */
	public function setWYSIWYGEditor($WYSIWYG_editor) {
		$this->WYSIWYG_editor = $WYSIWYG_editor;
	}

	/**
	 * @return string
	 */
	public function getWYSIWYGEditor() {
		return $this->WYSIWYG_editor;
	}

}