<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Renderer_Field extends Form_Renderer_Single
{
	
	/**
	 * @var Form_Field
	 */
	protected Form_Field $field;
	
	/**
	 * @return ?Form_Renderer_Field_Row
	 */
	protected ?Form_Renderer_Field_Row $_renderer_row = null;
	
	/**
	 * @return ?Form_Renderer_Field_Label
	 */
	protected ?Form_Renderer_Field_Label $_renderer_label = null;
	
	/**
	 * @return ?Form_Renderer_Field_Error
	 */
	protected ?Form_Renderer_Field_Error $_renderer_error = null;
	
	/**
	 * @return ?Form_Renderer_Field_Help
	 */
	protected ?Form_Renderer_Field_Help $_renderer_help = null;
	
	/**
	 * @return ?Form_Renderer_Field_Container
	 */
	protected ?Form_Renderer_Field_Container $_renderer_container = null;
	
	/**
	 * @return ?Form_Renderer_Field_Input
	 */
	protected ?Form_Renderer_Field_Input $_renderer_input = null;

	
	/**
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = SysConf_Jet_Form_DefaultViews::get( $field->getType() , 'field');
	}
	
	/**
	 * @return Form_Field
	 */
	public function getField(): Form_Field
	{
		return $this->field;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getViewDir(): string
	{
		if(!$this->view_dir) {
			return $this->field->getForm()->renderer()->getViewDir();
		}
		
		return $this->view_dir;
	}
	
	/**
	 * @return Form_Renderer_Field_Row
	 */
	public function row(): Form_Renderer_Field_Row
	{
		if( !$this->_renderer_row ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_row = Factory_Form::getRendererFieldInstance( $this->field, 'row' );
		}
		
		return $this->_renderer_row;
	}
	
	
	/**
	 * @return Form_Renderer_Field_Container
	 */
	public function container(): Form_Renderer_Field_Container
	{
		if( !$this->_renderer_container ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_container = Factory_Form::getRendererFieldInstance( $this->field, 'container' );
		}
		
		return $this->_renderer_container;
	}
	
	
	/**
	 * @return Form_Renderer_Field_Help
	 */
	public function help(): Form_Renderer_Field_Help
	{
		if( !$this->_renderer_help ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_help = Factory_Form::getRendererFieldInstance( $this->field, 'help' );
		}
		
		return $this->_renderer_help;
	}
	
	/**
	 * @return Form_Renderer_Field_Error
	 */
	public function error(): Form_Renderer_Field_Error
	{
		if( !$this->_renderer_error ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_error = Factory_Form::getRendererFieldInstance( $this->field, 'error' );
		}
		
		return $this->_renderer_error;
	}
	
	/**
	 * @return Form_Renderer_Field_Label
	 */
	public function label(): Form_Renderer_Field_Label
	{
		if( !$this->_renderer_label ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_label = Factory_Form::getRendererFieldInstance( $this->field, 'label' );
		}
		
		return $this->_renderer_label;
	}
	
	/**
	 * @return Form_Renderer_Field_Input
	 */
	public function input(): Form_Renderer_Field_Input
	{
		if( !$this->_renderer_input ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer_input = Factory_Form::getRendererFieldInstance( $this->field, 'input' );
		}
		
		return $this->_renderer_input;
	}
	
}