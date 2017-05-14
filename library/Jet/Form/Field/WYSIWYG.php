<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_WYSIWYG
 * @package Jet
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
	 * @var array
	 */
	protected static $default_editor_CSS_files = [
		'' => [
			'//cdn.tinymce.com/4/skins/lightgray/skin.min.css',
			//'//cdn.tinymce.com/4/skins/lightgray/content.min.css',
		],
	];
	/**
	 * @var array
	 */
	protected static $default_editor_JavaScript_files = [
		'//cdn.tinymce.com/4/tinymce.min.js',
	];
	/**
	 * @var array
	 */
	protected static $default_editor_config = [
		'mode'              => 'exact', 'theme' => 'modern', 'apply_source_formatting' => true,
		'remove_linebreaks' => false, 'entity_encoding' => 'raw', 'convert_urls' => false, 'verify_html' => true,

		'force_br_newlines' => false, 'force_p_newlines' => false, 'forced_root_block' => '',

		'plugins'       => 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality template paste textcolor colorpicker textpattern imagetools',
		'paste_as_text' => true,

		//'content_css' => 'wysiwyg.css',
		//'language_url' => .'tinymce/language/cs_CZ.js'
	];
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
	protected $editor_CSS_files;

	/**
	 * @var array
	 */
	protected $editor_JavaScript_files;

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
		$this->getEditorCSSFiles();

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
		if( !$this->editor_CSS_files ) {
			$this->editor_CSS_files = static::getDefaultEditorCSSFiles();
		}

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
	 * @return array
	 */
	public static function getDefaultEditorCSSFiles()
	{
		return static::$default_editor_CSS_files;
	}

	/**
	 * @param array $default_editor_CSS_files
	 */
	public static function setDefaultEditorCSSFiles( $default_editor_CSS_files )
	{
		static::$default_editor_CSS_files = $default_editor_CSS_files;
	}

	/**
	 * @param string $URI
	 */
	public function appendEditorJavaScriptFile( $URI )
	{
		$this->getEditorJavaScriptFiles();

		$this->editor_JavaScript_files[] = $URI;
	}

	/**
	 * @return array
	 */
	public function getEditorJavaScriptFiles()
	{
		if( !$this->editor_JavaScript_files ) {
			$this->editor_JavaScript_files = static::getDefaultEditorJavaScriptFiles();
		}

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
	public static function getDefaultEditorJavaScriptFiles()
	{
		return static::$default_editor_JavaScript_files;
	}

	/**
	 * @param array $default_editor_JavaScript_files
	 */
	public static function setDefaultEditorJavaScriptFiles( $default_editor_JavaScript_files )
	{
		static::$default_editor_JavaScript_files = $default_editor_JavaScript_files;
	}

	/**
	 * @return array
	 */
	public function getEditorConfig()
	{
		if( !$this->editor_config ) {
			$this->editor_config = static::getDefaultEditorConfig();
		}

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
	 * @return array
	 */
	public static function getDefaultEditorConfig()
	{
		return static::$default_editor_config;
	}

	/**
	 * @param array $default_editor_config
	 */
	public static function setDefaultEditorConfig( $default_editor_config )
	{
		static::$default_editor_config = $default_editor_config;
	}

	/**
	 * @return string
	 */
	public function generateJsInitCode()
	{
		foreach( $this->getEditorCSSFiles() as $media => $CSS_files ) {
			foreach( $CSS_files as $URI ) {
				Mvc_Layout::getCurrentLayout()->requireCssFile( $URI, $media );
			}
		}

		foreach( $this->getEditorJavaScriptFiles() as $URI ) {
			Mvc_Layout::getCurrentLayout()->requireJavascriptFile( $URI );
		}

		$callback = $this->getEditorInitializeCodeGenerator();

		return $callback( $this->getId(), $this->editor_config );

	}

	/**
	 * @return callable
	 */
	public function getEditorInitializeCodeGenerator()
	{
		if( !$this->editor_initialize_code_generator ) {
			$this->editor_initialize_code_generator = function( $node_id, $editor_config ) {
				$editor_config['selector'] = '#'.$node_id;
				if( $this->getIsReadonly() ) {
					$editor_config['readonly'] = 1;
				}

				return '<script type="text/javascript">'.'tinymce.init('.json_encode(
					$editor_config
				).');'.'</script>'.JET_EOL;
			};
		}

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
	public function catchValue( Data_Array $data )
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

		if( $this->validation_regexp ) {
			$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		}

		return $codes;
	}

}