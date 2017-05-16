<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Form_Field_Trait_Render
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
	protected static $default_input_renderer = 'Field/input/';

	/**
	 * @var string
	 */
	protected $custom_views_dir;


	/**
	 * @var string
	 */
	protected $renderer_script;

	/**
	 * @var string
	 */
	protected $row_start_renderer_script;

	/**
	 * @var string
	 */
	protected $row_end_renderer_script;

	/**
	 * @var string
	 */
	protected $input_container_start_renderer_script;

	/**
	 * @var string
	 */
	protected $input_container_end_renderer_script;

	/**
	 * @var string
	 */
	protected $error_renderer;

	/**
	 * @var string
	 */
	protected $label_renderer;

	/**
	 * @var string string
	 */
	protected $input_renderer = '';


	/**
	 * @return Form_Renderer_Pair
	 */
	protected $_tag_row;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_label;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_error;

	/**
	 * @return Form_Renderer_Pair
	 */
	protected $_tag_container;

	/**
	 * @return Form_Renderer_Single
	 */
	protected $_tag_input;



	/**
	 * @return string
	 */
	public function getCustomViewsDir()
	{
		return $this->custom_views_dir;
	}

	/**
	 * @param string $custom_views_dir
	 */
	public function setCustomViewsDir( $custom_views_dir )
	{
		$this->custom_views_dir = $custom_views_dir;
	}

	/**
	 * @return string
	 */
	public function getViewsDir()
	{
		/**
		 * @var Form_Field $this
		 */
		if($this->custom_views_dir) {
			return $this->custom_views_dir;
		}

		return $this->_form->getViewsDir();
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() {

		return Mvc_Factory::getViewInstance($this->getViewsDir());
	}




	/**
	 * @return string
	 */
	public static function getDefaultRendererScript()
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( $default_renderer_script )
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRowStartRendererScript()
	{
		return static::$default_row_start_renderer_script;
	}

	/**
	 * @param string $default_row_start_renderer_script
	 */
	public static function setDefaultRowStartRendererScript( $default_row_start_renderer_script )
	{
		static::$default_row_start_renderer_script = $default_row_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRowEndRendererScript()
	{
		return static::$default_row_end_renderer_script;
	}

	/**
	 * @param string $default_row_end_renderer_script
	 */
	public static function setDefaultRowEndRendererScript( $default_row_end_renderer_script )
	{
		static::$default_row_end_renderer_script = $default_row_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputContainerStartRendererScript()
	{
		return static::$default_input_container_start_renderer_script;
	}

	/**
	 * @param string $default_input_container_start_renderer_script
	 */
	public static function setDefaultInputContainerStartRendererScript( $default_input_container_start_renderer_script )
	{
		static::$default_input_container_start_renderer_script = $default_input_container_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputContainerEndRendererScript()
	{
		return static::$default_input_container_end_renderer_script;
	}

	/**
	 * @param string $default_input_container_end_renderer_script
	 */
	public static function setDefaultInputContainerEndRendererScript( $default_input_container_end_renderer_script )
	{
		static::$default_input_container_end_renderer_script = $default_input_container_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultErrorRenderer()
	{
		return static::$default_error_renderer;
	}

	/**
	 * @param string $default_error_renderer
	 */
	public static function setDefaultErrorRenderer( $default_error_renderer )
	{
		static::$default_error_renderer = $default_error_renderer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultLabelRenderer()
	{
		return static::$default_label_renderer;
	}

	/**
	 * @param string $default_label_renderer
	 */
	public static function setDefaultLabelRenderer( $default_label_renderer )
	{
		static::$default_label_renderer = $default_label_renderer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputRenderer()
	{
		return static::$default_input_renderer;
	}

	/**
	 * @param string $default_input_renderer
	 */
	public static function setDefaultInputRenderer( $default_input_renderer )
	{
		static::$default_input_renderer = $default_input_renderer;
	}

	/**
	 * @return string
	 */
	public function getRendererScript()
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRowStartRendererScript()
	{
		if(!$this->row_start_renderer_script) {
			$this->row_start_renderer_script = static::getDefaultRowStartRendererScript();
		}

		return $this->row_start_renderer_script;
	}

	/**
	 * @param string $row_start_renderer_script
	 */
	public function setRowStartRendererScript( $row_start_renderer_script )
	{
		$this->row_start_renderer_script = $row_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRowEndRendererScript()
	{
		if(!$this->row_end_renderer_script) {
			$this->row_end_renderer_script = static::getDefaultRowEndRendererScript();
		}

		return $this->row_end_renderer_script;
	}

	/**
	 * @param string $row_end_renderer_script
	 */
	public function setRowEndRendererScript( $row_end_renderer_script )
	{
		$this->row_end_renderer_script = $row_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerStartRendererScript()
	{
		if(!$this->input_container_start_renderer_script) {
			$this->input_container_start_renderer_script = static::getDefaultInputContainerStartRendererScript();
		}

		return $this->input_container_start_renderer_script;
	}

	/**
	 * @param string $input_container_start_renderer_script
	 */
	public function setInputContainerStartRendererScript( $input_container_start_renderer_script )
	{
		$this->input_container_start_renderer_script = $input_container_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerEndRendererScript()
	{
		if(!$this->input_container_end_renderer_script) {
			$this->input_container_end_renderer_script = static::getDefaultInputContainerEndRendererScript();
		}

		return $this->input_container_end_renderer_script;
	}

	/**
	 * @param string $input_container_end_renderer_script
	 */
	public function setInputContainerEndRendererScript( $input_container_end_renderer_script )
	{
		$this->input_container_end_renderer_script = $input_container_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getErrorRenderer()
	{
		if(!$this->error_renderer) {
			$this->error_renderer = static::getDefaultErrorRenderer();
		}

		return $this->error_renderer;
	}

	/**
	 * @param string $error_renderer
	 */
	public function setErrorRenderer( $error_renderer )
	{
		$this->error_renderer = $error_renderer;
	}

	/**
	 * @return string
	 */
	public function getLabelRenderer()
	{
		if(!$this->label_renderer ) {
			$this->label_renderer = static::getDefaultLabelRenderer();
		}

		return $this->label_renderer;
	}

	/**
	 * @param string $label_renderer
	 */
	public function setLabelRenderer( $label_renderer )
	{
		$this->label_renderer = $label_renderer;
	}

	/**
	 * @return string
	 */
	public function getInputRenderer()
	{
		if(!$this->input_renderer ) {
			$this->input_renderer = static::getDefaultInputRenderer();
		}

		return $this->input_renderer;
	}

	/**
	 * @param string $input_renderer
	 */
	public function setInputRenderer( $input_renderer )
	{
		$this->input_renderer = $input_renderer;
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$view = $this->getView();
		$view->setVar('field', $this);

		return $view->render( $this->getRendererScript() );
	}

	/**
	 * @return Form_Renderer_Pair
	 */
	public function row()
	{

		if( !$this->_tag_row ) {
			$this->_tag_row = Form_Factory::gerRendererPairInstance( $this->_form, $this );
			$this->_tag_row->setViewScriptStart( $this->getRowStartRendererScript() );
			$this->_tag_row->setViewScriptEnd( $this->getRowEndRendererScript() );
		}

		return $this->_tag_row;
	}


	/**
	 * @return Form_Renderer_Pair
	 */
	public function container()
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_container ) {
			$this->_tag_container = Form_Factory::gerRendererPairInstance( $this->_form, $this );
			$this->_tag_container->setViewScriptStart( $this->getInputContainerStartRendererScript() );
			$this->_tag_container->setViewScriptEnd( $this->getInputContainerEndRendererScript() );
			$this->_tag_container->setWidth( $this->_form->getDefaultFieldWidth() );

		}

		return $this->_tag_container;
	}


	/**
	 * @return Form_Renderer_Single
	 */
	public function error()
	{
		if( !$this->_tag_error ) {
			$this->_tag_error = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_error->setViewScript( $this->getErrorRenderer() );
		}

		return $this->_tag_error;
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function label()
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_label ) {
			$this->_tag_label = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_label->setViewScript( $this->getLabelRenderer() );
			$this->_tag_label->setWidth( $this->_form->getDefaultLabelWidth() );
		}

		return $this->_tag_label;
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function input()
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_input ) {
			$this->_tag_input = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_input->setViewScript( $this->getInputRenderer() );
			$this->_tag_input->setWidth( $this->_form->getDefaultFieldWidth() );
		}

		return $this->_tag_input;
	}

}