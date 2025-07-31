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
	protected Form_Field $field;
	
	protected ?Form_Renderer_Field_Row $_renderer_row = null;
	protected ?Form_Renderer_Field_Label $_renderer_label = null;
	protected ?Form_Renderer_Field_Error $_renderer_error = null;
	protected ?Form_Renderer_Field_Help $_renderer_help = null;
	protected ?Form_Renderer_Field_Container $_renderer_container = null;
	protected ?Form_Renderer_Field_Input $_renderer_input = null;
	
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = SysConf_Jet_Form_DefaultViews::get( $field->getType() , 'field');
	}
	
	public function getField(): Form_Field
	{
		return $this->field;
	}
	
	public function getViewDir(): string
	{
		if(!$this->view_dir) {
			return $this->field->getForm()->renderer()->getViewDir();
		}
		
		return $this->view_dir;
	}
	
	public function row(): Form_Renderer_Field_Row
	{
		if( !$this->_renderer_row ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_row = Factory_Form::getRendererFieldInstance( $this->field, 'row' );
		}
		
		return $this->_renderer_row;
	}
	
	
	public function container(): Form_Renderer_Field_Container
	{
		if( !$this->_renderer_container ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_container = Factory_Form::getRendererFieldInstance( $this->field, 'container' );
		}
		
		return $this->_renderer_container;
	}
	
	public function help(): Form_Renderer_Field_Help
	{
		if( !$this->_renderer_help ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_help = Factory_Form::getRendererFieldInstance( $this->field, 'help' );
		}
		
		return $this->_renderer_help;
	}

	public function error(): Form_Renderer_Field_Error
	{
		if( !$this->_renderer_error ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_error = Factory_Form::getRendererFieldInstance( $this->field, 'error' );
		}
		
		return $this->_renderer_error;
	}

	public function label(): Form_Renderer_Field_Label
	{
		if( !$this->_renderer_label ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_label = Factory_Form::getRendererFieldInstance( $this->field, 'label' );
		}
		
		return $this->_renderer_label;
	}

	public function input(): Form_Renderer_Field_Input
	{
		if( !$this->_renderer_input ) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			/** @phpstan-ignore assign.propertyType */
			$this->_renderer_input = Factory_Form::getRendererFieldInstance( $this->field, 'input' );
		}
		
		return $this->_renderer_input;
	}
	
}