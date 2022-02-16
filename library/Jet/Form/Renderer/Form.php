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
class Form_Renderer_Form extends Form_Renderer_Pair
{
	
	/**
	 * @var Form
	 */
	protected Form $form;
	
	/**
	 * @var array
	 */
	protected array $default_label_width = [
		Form_Renderer::LJ_SIZE_MEDIUM => 4
	];
	
	/**
	 * @var array
	 */
	protected array $default_field_width = [
		Form_Renderer::LJ_SIZE_MEDIUM => 8
	];
	
	/**
	 * @var ?Form_Renderer_Form_Message
	 */
	protected ?Form_Renderer_Form_Message $_message_renderer = null;
	
	/**
	 *
	 * @param Form $form
	 */
	public function __construct( Form $form )
	{
		$this->form = $form;
		$this->view_dir = SysConf_Jet_Form::getDefaultViewsDir();
		$this->view_script_start = SysConf_Jet_Form_DefaultViews::get('Form', 'start');
		$this->view_script_end = SysConf_Jet_Form_DefaultViews::get('Form', 'end');
	}
	
	/**
	 * @return Form
	 */
	public function getForm(): Form
	{
		return $this->form;
	}
	
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$form = $this->form;
		
		$this->_tag_attributes['name'] = $form->getName();
		$this->_tag_attributes['id'] = $form->getId();
		$this->_tag_attributes['method'] = $form->getMethod();
		
		
		if($form->getAction()) {
			$this->_tag_attributes['action'] = $form->getAction();
		}
		if($form->getTarget()) {
			$this->_tag_attributes['target'] = $form->getTarget();
		}
		if($form->getEnctype()) {
			$this->_tag_attributes['enctype'] = $form->getEnctype();
		}
		if($form->getAcceptCharset()) {
			$this->_tag_attributes['accept-charset'] = $form->getAcceptCharset();
		}
		if($form->getNovalidate()) {
			$this->_tag_attributes['novalidate'] = 'novalidate';
		}
		if(!$form->getAutocomplete()) {
			$this->_tag_attributes['autocomplete'] = 'off';
		}
	}
	
	/**
	 *
	 * @return Form_Renderer_Form_Message
	 */
	public function message(): Form_Renderer_Form_Message
	{
		if( !$this->_message_renderer ) {
			$this->_message_renderer = Factory_Form::getRendererFormMessageInstance( $this->form );
		}
		
		return $this->_message_renderer;
	}
	
	/**
	 * @return array
	 */
	public function getDefaultLabelWidth(): array
	{
		return $this->default_label_width;
	}
	
	/**
	 * @param array $default_label_width
	 *
	 * @return $this
	 */
	public function setDefaultLabelWidth( array $default_label_width ): static
	{
		$this->default_label_width = $default_label_width;
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getDefaultFieldWidth(): array
	{
		return $this->default_field_width;
	}
	
	/**
	 * @param array $default_field_width
	 *
	 * @return $this
	 */
	public function setDefaultFieldWidth( array $default_field_width ): static
	{
		$this->default_field_width = $default_field_width;
		
		return $this;
	}
	
}