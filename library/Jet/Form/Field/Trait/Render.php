<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Form_Field_Trait_Render
{

	/**
	 * @var ?string
	 */
	protected ?string $custom_views_dir = null;


	/**
	 * @var ?string
	 */
	protected ?string $renderer_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $row_start_renderer_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $row_end_renderer_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $input_container_start_renderer_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $input_container_end_renderer_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $error_renderer = null;

	/**
	 * @var ?string
	 */
	protected ?string $label_renderer = null;

	/**
	 * @var string string
	 */
	protected string $input_renderer = '';


	/**
	 * @return ?Form_Renderer_Pair
	 */
	protected ?Form_Renderer_Pair $_tag_row = null;

	/**
	 * @return ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_tag_label = null;

	/**
	 * @return ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_tag_error = null;

	/**
	 * @return ?Form_Renderer_Pair
	 */
	protected ?Form_Renderer_Pair $_tag_container = null;

	/**
	 * @return ?Form_Renderer_Single
	 */
	protected ?Form_Renderer_Single $_tag_input = null;


	/**
	 * @return string
	 */
	public function getCustomViewsDir(): string
	{
		return $this->custom_views_dir;
	}

	/**
	 * @param string $custom_views_dir
	 */
	public function setCustomViewsDir( string $custom_views_dir ): void
	{
		$this->custom_views_dir = $custom_views_dir;
	}

	/**
	 * @return string
	 */
	public function getViewsDir(): string
	{
		/**
		 * @var Form_Field $this
		 * @var Form $form
		 */

		if( $this->custom_views_dir ) {
			return $this->custom_views_dir;
		}
		$form = $this->_form;

		return $form->getViewsDir();
	}

	/**
	 * @return Mvc_View
	 */
	public function getView(): Mvc_View
	{

		return Mvc_Factory::getViewInstance( $this->getViewsDir() );
	}


	/**
	 * @return string
	 */
	public static function getDefaultRendererScript(): string
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( string $default_renderer_script ): void
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRowStartRendererScript(): string
	{
		return static::$default_row_start_renderer_script;
	}

	/**
	 * @param string $default_row_start_renderer_script
	 */
	public static function setDefaultRowStartRendererScript( string $default_row_start_renderer_script ): void
	{
		static::$default_row_start_renderer_script = $default_row_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRowEndRendererScript(): string
	{
		return static::$default_row_end_renderer_script;
	}

	/**
	 * @param string $default_row_end_renderer_script
	 */
	public static function setDefaultRowEndRendererScript( string $default_row_end_renderer_script ): void
	{
		static::$default_row_end_renderer_script = $default_row_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputContainerStartRendererScript(): string
	{
		return static::$default_input_container_start_renderer_script;
	}

	/**
	 * @param string $default_input_container_start_renderer_script
	 */
	public static function setDefaultInputContainerStartRendererScript( string $default_input_container_start_renderer_script ): void
	{
		static::$default_input_container_start_renderer_script = $default_input_container_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputContainerEndRendererScript(): string
	{
		return static::$default_input_container_end_renderer_script;
	}

	/**
	 * @param string $default_input_container_end_renderer_script
	 */
	public static function setDefaultInputContainerEndRendererScript( string $default_input_container_end_renderer_script ): void
	{
		static::$default_input_container_end_renderer_script = $default_input_container_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultErrorRenderer(): string
	{
		return static::$default_error_renderer;
	}

	/**
	 * @param string $default_error_renderer
	 */
	public static function setDefaultErrorRenderer( string $default_error_renderer ): void
	{
		static::$default_error_renderer = $default_error_renderer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultLabelRenderer(): string
	{
		return static::$default_label_renderer;
	}

	/**
	 * @param string $default_label_renderer
	 */
	public static function setDefaultLabelRenderer( string $default_label_renderer ): void
	{
		static::$default_label_renderer = $default_label_renderer;
	}

	/**
	 * @return string
	 */
	public static function getDefaultInputRenderer(): string
	{
		return static::$default_input_renderer;
	}

	/**
	 * @param string $default_input_renderer
	 */
	public static function setDefaultInputRenderer( string $default_input_renderer ): void
	{
		static::$default_input_renderer = $default_input_renderer;
	}

	/**
	 * @return string
	 */
	public function getRendererScript(): string
	{
		if( !$this->renderer_script ) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 */
	public function setRendererScript( string $renderer_script ): void
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRowStartRendererScript(): string
	{
		if( !$this->row_start_renderer_script ) {
			$this->row_start_renderer_script = static::getDefaultRowStartRendererScript();
		}

		return $this->row_start_renderer_script;
	}

	/**
	 * @param string $row_start_renderer_script
	 */
	public function setRowStartRendererScript( string $row_start_renderer_script ): void
	{
		$this->row_start_renderer_script = $row_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRowEndRendererScript(): string
	{
		if( !$this->row_end_renderer_script ) {
			$this->row_end_renderer_script = static::getDefaultRowEndRendererScript();
		}

		return $this->row_end_renderer_script;
	}

	/**
	 * @param string $row_end_renderer_script
	 */
	public function setRowEndRendererScript( string $row_end_renderer_script ): void
	{
		$this->row_end_renderer_script = $row_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerStartRendererScript(): string
	{
		if( !$this->input_container_start_renderer_script ) {
			$this->input_container_start_renderer_script = static::getDefaultInputContainerStartRendererScript();
		}

		return $this->input_container_start_renderer_script;
	}

	/**
	 * @param string $input_container_start_renderer_script
	 */
	public function setInputContainerStartRendererScript( string $input_container_start_renderer_script ): void
	{
		$this->input_container_start_renderer_script = $input_container_start_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerEndRendererScript(): string
	{
		if( !$this->input_container_end_renderer_script ) {
			$this->input_container_end_renderer_script = static::getDefaultInputContainerEndRendererScript();
		}

		return $this->input_container_end_renderer_script;
	}

	/**
	 * @param string $input_container_end_renderer_script
	 */
	public function setInputContainerEndRendererScript( string $input_container_end_renderer_script ): void
	{
		$this->input_container_end_renderer_script = $input_container_end_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getErrorRenderer(): string
	{
		if( !$this->error_renderer ) {
			$this->error_renderer = static::getDefaultErrorRenderer();
		}

		return $this->error_renderer;
	}

	/**
	 * @param string $error_renderer
	 */
	public function setErrorRenderer( string $error_renderer ): void
	{
		$this->error_renderer = $error_renderer;
	}

	/**
	 * @return string
	 */
	public function getLabelRenderer(): string
	{
		if( !$this->label_renderer ) {
			$this->label_renderer = static::getDefaultLabelRenderer();
		}

		return $this->label_renderer;
	}

	/**
	 * @param string $label_renderer
	 */
	public function setLabelRenderer( string $label_renderer ): void
	{
		$this->label_renderer = $label_renderer;
	}

	/**
	 * @return string
	 */
	public function getInputRenderer(): string
	{
		if( !$this->input_renderer ) {
			$this->input_renderer = static::getDefaultInputRenderer();
		}

		return $this->input_renderer;
	}

	/**
	 * @param string $input_renderer
	 */
	public function setInputRenderer( string $input_renderer ): void
	{
		$this->input_renderer = $input_renderer;
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->getView();
		$view->setVar( 'field', $this );

		return $view->render( $this->getRendererScript() );
	}

	/**
	 * @return Form_Renderer_Pair
	 */
	public function row(): Form_Renderer_Pair
	{
		/**
		 * @var Form_Field $this
		 */
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
	public function container(): Form_Renderer_Pair
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_container ) {
			$this->_tag_container = Form_Factory::gerRendererPairInstance( $this->_form, $this );
			$this->_tag_container->setViewScriptStart( $this->getInputContainerStartRendererScript() );
			$this->_tag_container->setViewScriptEnd( $this->getInputContainerEndRendererScript() );

			/**
			 * @var Form $form
			 */
			$form = $this->_form;

			$this->_tag_container->setWidth( $form->getDefaultFieldWidth() );

		}

		return $this->_tag_container;
	}


	/**
	 * @return Form_Renderer_Single
	 */
	public function error(): Form_Renderer_Single
	{
		/**
		 * @var Form_Field $this
		 */
		if( !$this->_tag_error ) {
			$this->_tag_error = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_error->setViewScript( $this->getErrorRenderer() );
		}

		return $this->_tag_error;
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function label(): Form_Renderer_Single
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_label ) {
			$this->_tag_label = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_label->setViewScript( $this->getLabelRenderer() );
			/**
			 * @var Form $form
			 */
			$form = $this->_form;

			$this->_tag_label->setWidth( $form->getDefaultLabelWidth() );
		}

		return $this->_tag_label;
	}

	/**
	 * @return Form_Renderer_Single
	 */
	public function input(): Form_Renderer_Single
	{
		/**
		 * @var Form_Field_Trait_Render|Form_Field $this
		 */

		if( !$this->_tag_input ) {
			$this->_tag_input = Form_Factory::gerRendererSingleInstance( $this->_form, $this );
			$this->_tag_input->setViewScript( $this->getInputRenderer() );

			/**
			 * @var Form $form
			 */
			$form = $this->_form;

			$this->_tag_input->setWidth( $form->getDefaultFieldWidth() );
		}

		return $this->_tag_input;
	}

}