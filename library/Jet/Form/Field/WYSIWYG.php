<?php
/**
 *
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

class Form_Field_WYSIWYG extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_WYSIWYG;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_INVALID_FORMAT => ''
	];


	/**
	 * Editor config name in main configuration /js_libs/TinyMCE/editor_configs/CONFIG_NAME
	 *
	 * @var string
	 */
	protected $editor_config_name = 'default';

	/**
	 * @var string
	 */
	protected $WYSIWYG_editor = 'Javascript_Lib_TinyMCE';

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {
        $class_name = Object_Reflection::parseClassName($this->WYSIWYG_editor);

        /**
         * @var JavaScript_Lib_TinyMCE $WYSIWYG
         */
        $WYSIWYG = new $class_name();

		$this->__form->getLayout()->requireJavascriptLib( $WYSIWYG );

		$tag_data->setProperty('name', $this->getName());
		$tag_data->setProperty('id', $this->getID());


		$result = /** @lang text */
			'<textarea '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$this->getValue().'</textarea>'.JET_EOL;

		$ID = json_encode($this->getID());
		$config_name = json_encode($this->editor_config_name);

		$result .= /** @lang text */
			'<script type="text/javascript">Jet_WYSIWYG.init('.$ID.','.$config_name.');</script>'.JET_EOL;

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