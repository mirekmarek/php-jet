<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class Form_Field_WYSIWYG extends Form_Field
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static $default_row_start_renderer_script = 'Field/row/start';

	/**
	 * @var string
	 */
	protected static $default_row_end_renderer_script = 'Field/row/end';

	/**
	 * @var string
	 */
	protected static $default_input_container_start_renderer_script = 'Field/input/container/start';

	/**
	 * @var string
	 */
	protected static $default_input_container_end_renderer_script = 'Field/input/container/end';

	/**
	 * @var string
	 */
	protected static $default_error_renderer = 'Field/error';

	/**
	 * @var string
	 */
	protected static $default_label_renderer = 'Field/label';

	/**
	 * @var string string
	 */
	protected static $default_input_renderer = 'Field/input/WYSIWYG';


	/**
	 * @var callable
	 */
	protected static $default_initialize_code_generator;

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_WYSIWYG;
	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => ''
	];
	/**
	 * @var array
	 */
	protected $editor_CSS_files = [];

	/**
	 * @var array
	 */
	protected $editor_JavaScript_files = [];

	/**
	 * @var array
	 */
	protected $editor_config;

	/**
	 * @var callable
	 */
	protected $editor_initialize_code_generator;




	/**
	 * @param string $URI
	 * @param string $media
	 */
	public function appendWYSIWYGEditorCSSFile( $URI, $media = 'screen' )
	{
		if( !isset( $this->editor_CSS_files[$media] ) ) {
			$this->editor_CSS_files[$media] = [];
		}

		$this->editor_CSS_files[$media][] = $URI;
	}

	/**
	 * @return array
	 */
	public function getEditorCSSFiles()
	{
		return $this->editor_CSS_files;
	}

	/**
	 * @param array $editor_CSS_files
	 */
	public function setEditorCSSFiles( array $editor_CSS_files )
	{
		$this->editor_CSS_files = $editor_CSS_files;
	}

	/**
	 * @param string $URI
	 */
	public function appendEditorJavaScriptFile( $URI )
	{
		$this->editor_JavaScript_files[] = $URI;
	}

	/**
	 * @return array
	 */
	public function getEditorJavaScriptFiles()
	{
		return $this->editor_JavaScript_files;
	}

	/**
	 * @param array $editor_JavaScript_files
	 */
	public function setEditorJavaScriptFiles( array $editor_JavaScript_files )
	{
		$this->editor_JavaScript_files = $editor_JavaScript_files;
	}

	/**
	 * @return array
	 */
	public function getEditorConfig()
	{
		return $this->editor_config;
	}

	/**
	 * @param array $editor_config
	 */
	public function setEditorConfig( array $editor_config )
	{
		$this->editor_config = $editor_config;
	}


	/**
	 * @return callable
	 */
	public function getEditorInitializeCodeGenerator()
	{
		return $this->editor_initialize_code_generator;
	}

	/**
	 * @param callable $editor_initialize_code_generator
	 */
	public function setEditorInitializeCodeGenerator( callable $editor_initialize_code_generator )
	{
		$this->editor_initialize_code_generator = $editor_initialize_code_generator;
	}


	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data )
	{
		$this->_value = null;
		$this->_has_value = $data->exists( $this->_name );

		if( $this->_has_value ) {
			$this->_value_raw = $data->getRaw( $this->_name );
			$this->_value = trim( $data->getRaw( $this->_name ) );
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

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		return $codes;
	}

}