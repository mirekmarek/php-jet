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
trait Form_Field_Trait_Render
{
	
	/**
	 * @return ?Form_Renderer_Field
	 */
	protected ?Form_Renderer_Field $_renderer = null;
	

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
		return $this->renderer()->render();
	}
	
	/**
	 * @return Form_Renderer_Field
	 */
	public function renderer() : Form_Renderer_Field
	{
		if(!$this->_renderer) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			$this->_renderer = Factory_Form::getRendererFieldInstance( $this, 'field' );
		}
		
		return $this->_renderer;
	}

	/**
	 * @return Form_Renderer_Field_Row
	 */
	public function row(): Form_Renderer_Field_Row
	{
		return $this->renderer()->row();
	}


	/**
	 * @return Form_Renderer_Field_Container
	 */
	public function container(): Form_Renderer_Field_Container
	{
		return $this->renderer()->container();
	}


	/**
	 * @return Form_Renderer_Field_Error
	 */
	public function error(): Form_Renderer_Field_Error
	{
		return $this->renderer()->error();
	}
	
	
	/**
	 * @return Form_Renderer_Field_Help
	 */
	public function help(): Form_Renderer_Field_Help
	{
		return $this->renderer()->help();
	}
	
	/**
	 * @return Form_Renderer_Field_Label
	 */
	public function label(): Form_Renderer_Field_Label
	{
		return $this->renderer()->label();
	}

	/**
	 * @return Form_Renderer_Field_Input
	 */
	public function input(): Form_Renderer_Field_Input
	{
		return $this->renderer()->input();
	}

}