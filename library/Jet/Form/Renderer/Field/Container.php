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
class Form_Renderer_Field_Container extends Form_Renderer_Pair
{
	
	/**
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script_start = SysConf_Jet_Form_DefaultViews::get($field->getType(), 'input_start');
		$this->view_script_end = SysConf_Jet_Form_DefaultViews::get($field->getType(), 'input_end');
	}
	
	/**
	 * @return array|null
	 */
	public function getWidth(): array|null
	{
		if(!$this->width) {
			return $this->field->getForm()->renderer()->getDefaultFieldWidth();
		}
		
		return $this->width;
	}
	
	
	/**
	 * @return string
	 */
	public function getViewDir(): string
	{
		if(!$this->view_dir) {
			return $this->field->renderer()->getViewDir();
		}
		
		return $this->view_dir;
	}
	
}