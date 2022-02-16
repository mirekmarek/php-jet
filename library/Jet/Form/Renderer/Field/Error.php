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
class Form_Renderer_Field_Error extends Form_Renderer_Single
{
	
	/**
	 * @var Form_Field
	 */
	protected Form_Field $field;
	
	/**
	 *
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = SysConf_Jet_Form_DefaultViews::get($field->getType(), 'error');
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
			return $this->field->renderer()->getViewDir();
		}
		
		return $this->view_dir;
	}
	
}