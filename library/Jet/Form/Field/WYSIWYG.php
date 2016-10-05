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
	 * @var array
	 */
	protected $WYSIWYG_editor_CSS_files = [
		'screen' => []
	];

	/**
	 * @var array
	 */
	protected $WYSIWYG_editor_JavaScript_files = [
		'//tinymce.cachefly.net/4.3/tinymce.min.js'
	];

	/**
	 * @var array
	 */
	protected $WISIWYG_editor_config = [
		'mode' => 'exact',
		'theme' => 'modern',
		'apply_source_formatting' => true,
		'remove_linebreaks' => false,
		'entity_encoding' => 'raw',
		'convert_urls' => false,
		'verify_html' => true,
		'auto_focus' => false,
	];

	/**
	 * @var callable
	 */
	protected $WYSIWYG_editor_initialize_code_generator;

	/**
	 * @return array
	 */
	public function getWYSIWYGEditorCSSFiles()
	{
		return $this->WYSIWYG_editor_CSS_files;
	}

	/**
	 * @param array $WYSIWYG_editor_CSS_files
	 */
	public function setWYSIWYGEditorCSSFiles( array $WYSIWYG_editor_CSS_files)
	{
		$this->WYSIWYG_editor_CSS_files = $WYSIWYG_editor_CSS_files;
	}

	/**
	 * @param string $URI
	 * @param string $media
	 */
	public function appendWYSIWYGEditorCSSFile( $URI, $media='screen' ) {
		if(!isset($this->WYSIWYG_editor_CSS_files[$media])) {
			$this->WYSIWYG_editor_CSS_files[$media] = [];
		}

		$this->WYSIWYG_editor_CSS_files[$media][] = $URI;
	}

	/**
	 * @return array
	 */
	public function getWYSIWYGEditorJavaScriptFiles()
	{
		return $this->WYSIWYG_editor_JavaScript_files;
	}

	/**
	 * @param array $WYSIWYG_editor_JavaScript_files
	 */
	public function setWYSIWYGEditorJavaScriptFiles( array $WYSIWYG_editor_JavaScript_files)
	{
		$this->WYSIWYG_editor_JavaScript_files = $WYSIWYG_editor_JavaScript_files;
	}

	/**
	 * @param $URI
	 */
	public function appendWYSIWYGEditorJavaScriptFile( $URI ) {
		$this->WYSIWYG_editor_JavaScript_files[] = $URI;
	}

	/**
	 * @return array
	 */
	public function getWISIWYGEditorConfig()
	{
		return $this->WISIWYG_editor_config;
	}

	/**
	 * @param array $WISIWYG_editor_config
	 */
	public function setWISIWYGEditorConfig( array $WISIWYG_editor_config)
	{
		$this->WISIWYG_editor_config = $WISIWYG_editor_config;
	}

	/**
	 * @return callable
	 */
	public function getWYSIWYGEditorInitializeCodeGenerator()
	{
		return $this->WYSIWYG_editor_initialize_code_generator;
	}

	/**
	 * @param callable $WYSIWYG_editor_initialize_code_generator
	 */
	public function setWYSIWYGEditorInitializeCodeGenerator(callable $WYSIWYG_editor_initialize_code_generator)
	{
		$this->WYSIWYG_editor_initialize_code_generator = $WYSIWYG_editor_initialize_code_generator;
	}



	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {


		$layout = $this->__form->getLayout();

		if($layout) {
			foreach( $this->WYSIWYG_editor_CSS_files as $media=>$CSS_files ) {
				foreach( $CSS_files as $URI ) {
					$layout->requireCssFile($URI, $media);
				}
			}

			foreach( $this->WYSIWYG_editor_JavaScript_files as $URI ) {
				$layout->requireJavascriptFile($URI);
			}

		}

		$tag_data->setProperty('id', $this->getID());
		$tag_data->setProperty( 'name', $this->getName() );


		$result = /** @lang text */
			'<textarea '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$this->getValue().'</textarea>'.JET_EOL;

		if(!$this->WYSIWYG_editor_initialize_code_generator) {
			$this->WYSIWYG_editor_initialize_code_generator = function( $node_ID, $editor_config ) {
				$editor_config['selector'] = '#'.$node_ID;
				if($this->getIsReadonly()) {
					$editor_config['readonly'] = 1;
				}

				return '<script type="text/javascript">'
						.'tinymce.init('.json_encode($editor_config).');'
					.'</script>'.JET_EOL;
			};
		}

		$callback = $this->WYSIWYG_editor_initialize_code_generator;

		$result .= $callback( $this->getID(), $this->WISIWYG_editor_config );

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