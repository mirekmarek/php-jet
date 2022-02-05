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
	protected ?string $view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $row_start_view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $row_end_view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $input_container_start_view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $input_container_end_view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $error_view_script = null;

	/**
	 * @var ?string
	 */
	protected ?string $label_view_script = null;

	/**
	 * @var string string
	 */
	protected string $input_view_script = '';


	/**
	 * @return ?Form_Renderer_Field_Row
	 */
	protected ?Form_Renderer_Field_Row $_tag_row = null;

	/**
	 * @return ?Form_Renderer_Field_Label
	 */
	protected ?Form_Renderer_Field_Label $_tag_label = null;

	/**
	 * @return ?Form_Renderer_Field_Error
	 */
	protected ?Form_Renderer_Field_Error $_tag_error = null;

	/**
	 * @return ?Form_Renderer_Field_Container
	 */
	protected ?Form_Renderer_Field_Container $_tag_container = null;

	/**
	 * @return ?Form_Renderer_Field_Input
	 */
	protected ?Form_Renderer_Field_Input $_tag_input = null;


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
		if( $this->custom_views_dir ) {
			return $this->custom_views_dir;
		}

		return $this->_form->getViewsDir();
	}

	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{
		return Factory_MVC::getViewInstance( $this->getViewsDir() );
	}


	/**
	 * @return string
	 */
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'field');
		}

		return $this->view_script;
	}

	/**
	 * @param string $view_script
	 */
	public function setViewScript( string $view_script ): void
	{
		$this->view_script = $view_script;
	}

	/**
	 * @return string
	 */
	public function getRowStartViewScript(): string
	{
		if( !$this->row_start_view_script ) {
			$this->row_start_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'row_start');
		}

		return $this->row_start_view_script;
	}

	/**
	 * @param string $row_start_view_script
	 */
	public function setRowStartViewScript( string $row_start_view_script ): void
	{
		$this->row_start_view_script = $row_start_view_script;
	}

	/**
	 * @return string
	 */
	public function getRowEndViewScript(): string
	{
		if( !$this->row_end_view_script ) {
			$this->row_end_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'row_end');
		}

		return $this->row_end_view_script;
	}

	/**
	 * @param string $row_end_view_script
	 */
	public function setRowEndViewScript( string $row_end_view_script ): void
	{
		$this->row_end_view_script = $row_end_view_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerStartViewScript(): string
	{
		if( !$this->input_container_start_view_script ) {
			$this->input_container_start_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'input_container_start');
		}

		return $this->input_container_start_view_script;
	}

	/**
	 * @param string $input_container_start_view_script
	 */
	public function setInputContainerStartViewScript( string $input_container_start_view_script ): void
	{
		$this->input_container_start_view_script = $input_container_start_view_script;
	}

	/**
	 * @return string
	 */
	public function getInputContainerEndViewScript(): string
	{
		if( !$this->input_container_end_view_script ) {
			$this->input_container_end_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'input_container_end');
		}

		return $this->input_container_end_view_script;
	}

	/**
	 * @param string $input_container_end_view_script
	 */
	public function setInputContainerEndViewScript( string $input_container_end_view_script ): void
	{
		$this->input_container_end_view_script = $input_container_end_view_script;
	}

	/**
	 * @return string
	 */
	public function getErrorViewScript(): string
	{
		if( !$this->error_view_script ) {
			$this->error_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'error');
		}

		return $this->error_view_script;
	}

	/**
	 * @param string $error_view_script
	 */
	public function setErrorViewScript( string $error_view_script ): void
	{
		$this->error_view_script = $error_view_script;
	}

	/**
	 * @return string
	 */
	public function getLabelViewScript(): string
	{
		if( !$this->label_view_script ) {
			$this->label_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'label');
		}

		return $this->label_view_script;
	}

	/**
	 * @param string $label_view_script
	 */
	public function setLabelViewScript( string $label_view_script ): void
	{
		$this->label_view_script = $label_view_script;
	}

	/**
	 * @return string
	 */
	public function getInputViewScript(): string
	{
		if( !$this->input_view_script ) {
			$this->input_view_script = SysConf_Jet_Form_DefaultViews::get($this->_type, 'input');
		}

		return $this->input_view_script;
	}

	/**
	 * @param string $input_view_script
	 */
	public function setInputViewScript( string $input_view_script ): void
	{
		$this->input_view_script = $input_view_script;
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

		return $view->render( $this->getViewScript() );
	}

	/**
	 * @return Form_Renderer_Field_Row
	 */
	public function row(): Form_Renderer_Field_Row
	{
		if( !$this->_tag_row ) {
			$this->_tag_row = Factory_Form::getRendererFieldRowInstance( $this );
		}

		return $this->_tag_row;
	}


	/**
	 * @return Form_Renderer_Field_Container
	 */
	public function container(): Form_Renderer_Field_Container
	{
		if( !$this->_tag_container ) {
			$this->_tag_container = Factory_Form::getRendererFieldContainerInstance( $this );
		}

		return $this->_tag_container;
	}


	/**
	 * @return Form_Renderer_Field_Error
	 */
	public function error(): Form_Renderer_Field_Error
	{
		if( !$this->_tag_error ) {
			$this->_tag_error = Factory_Form::getRendererFieldErrorInstance( $this );
		}

		return $this->_tag_error;
	}

	/**
	 * @return Form_Renderer_Field_Label
	 */
	public function label(): Form_Renderer_Field_Label
	{
		if( !$this->_tag_label ) {
			$this->_tag_label = Factory_Form::getRendererFieldLabelInstance( $this );
		}

		return $this->_tag_label;
	}

	/**
	 * @return Form_Renderer_Field_Input
	 */
	public function input(): Form_Renderer_Field_Input
	{
		if( !$this->_tag_input ) {
			$this->_tag_input = Factory_Form::getRendererFieldInputInstance( $this );
		}

		return $this->_tag_input;
	}

}