<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Data_Array;
use Jet\Factory_Form;
use Jet\Form_Field;
use Jet\SysConf_Jet_Form_DefaultViews;

/**
 *
 */
class Form_Field_MetaTags extends Form_Field
{
	
	protected string $_type = 'meta-tags';

	protected int $new_rows_count = 5;
	
	public function getRequiredErrorCodes(): array
	{
		return [];
	}
	
	public function catchInput( Data_Array $data ): void
	{
		$name = (($this->_name[0]=='/') ? $this->_name : '/'.$this->_name).'/';
		
		$this->_has_value = $data->exists( $name.'attribute' ) && $data->exists( $name.'attribute_value' ) && $data->exists( $name.'content' );
		
		$this->_value = null;
		
		
		if( $this->_has_value ) {
			
			$attributes = $data->getRaw( $name.'attribute' );
			$attribute_values = $data->getRaw( $name.'attribute_value' );
			$contents = $data->getRaw( $name.'content' );
			
			$this->_value = [];
			
			foreach($attributes as $i=>$attribute) {
				$attribute = trim($attribute);
				$attribute_value = trim($attribute_values[$i]);
				$content = trim($contents[$i]);
				
				if(!$attribute && !$attribute_value && !$content) {
					continue;
				}
				
				$this->_value[] = [
					'attribute' => $attribute,
					'attribute_value' => $attribute_value,
					'content' => $content
				];
			}
			
			$this->_value_raw = $this->_value;
			
		} else {
			$this->_value_raw = null;
			$this->_value = $this->default_value;
		}

	}
	
	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		$this->setIsValid();
		
		return true;
	}
	
	/**
	 * @return int
	 */
	public function getNewRowsCount(): int
	{
		return $this->new_rows_count;
	}
	
	/**
	 * @param int $new_rows_count
	 */
	public function setNewRowsCount( int $new_rows_count ): void
	{
		$this->new_rows_count = $new_rows_count;
	}
	
	
	
	public function getValue(): mixed
	{
		if(!$this->_value) {
			return [];
		}
		
		return $this->_value;
	}
	
}

Factory_Form::registerNewFieldType(
	field_type: 'meta-tags',
	field_class_name: Form_Field_MetaTags::class,
	renderers: [
		'input' => Form_Renderer_Field_Input_MetaTags::class
	]
);

SysConf_Jet_Form_DefaultViews::registerNewFieldType('meta-tags', [
	'input' => 'field/input/meta-tags'
]);