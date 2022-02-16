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
class Form_Field_Array extends Form_Field
{
	
	protected string $_type = 'array';

	protected int $new_rows_count = 5;
	
	protected string $prepend_text = '';
	
	protected string $append_text = '';
	
	public function getRequiredErrorCodes(): array
	{
		return [];
	}
	
	public function catchInput( Data_Array $data ): void
	{
		$name = (($this->_name[0]=='/') ? $this->_name : '/'.$this->_name);
		
		$this->_has_value = $data->exists( $name );
		
		$this->_value = null;
		
		
		if( $this->_has_value ) {
			
			$values = $data->getRaw( $name );
			
			$this->_value = [];
			
			foreach($values as $value) {
				$value = trim($value);
				if(!$value) {
					continue;
				}
				
				$this->_value[] = $value;
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
	
	/**
	 * @return string
	 */
	public function getPrependText(): string
	{
		return $this->prepend_text;
	}
	
	/**
	 * @param string $prepend_text
	 */
	public function setPrependText( string $prepend_text ): void
	{
		$this->prepend_text = $prepend_text;
	}
	
	/**
	 * @return string
	 */
	public function getAppendText(): string
	{
		return $this->append_text;
	}
	
	/**
	 * @param string $append_text
	 */
	public function setAppendText( string $append_text ): void
	{
		$this->append_text = $append_text;
	}
	
	
	
}

Factory_Form::registerNewFieldType(
	field_type: 'array',
	field_class_name: Form_Field_Array::class,
	renderers: [
		'input' => Form_Renderer_Field_Input_Array::class
	]
);

SysConf_Jet_Form_DefaultViews::registerNewFieldType('array', [
	'input' => 'field/input/array'
]);