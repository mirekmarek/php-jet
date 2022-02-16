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
class Form_Field_AssocArray extends Form_Field
{
	
	protected string $_type = 'assoc-array';

	protected int $new_rows_count = 5;
	
	protected string $assoc_char = '=>';
	
	public function getRequiredErrorCodes(): array
	{
		return [];
	}
	
	public function catchInput( Data_Array $data ): void
	{
		$name = (($this->_name[0]=='/') ? $this->_name : '/'.$this->_name).'/';
		
		$this->_has_value = $data->exists( $name.'key' ) && $data->exists( $name.'value' );
		
		$this->_value = null;
		
		
		if( $this->_has_value ) {
			
			$keys = $data->getRaw( $name.'key' );
			$values = $data->getRaw( $name.'value' );
			
			$this->_value = [];
			
			foreach($keys as $i=>$key) {
				$key = trim($key);
				if(!$key) {
					continue;
				}
				
				$val = trim($values[$i]);
				
				$this->_value[$key] = $val;
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
	
	/**
	 * @return string
	 */
	public function getAssocChar(): string
	{
		return $this->assoc_char;
	}
	
	/**
	 * @param string $assoc_char
	 */
	public function setAssocChar( string $assoc_char ): void
	{
		$this->assoc_char = $assoc_char;
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
	field_type: 'assoc-array',
	field_class_name: Form_Field_AssocArray::class,
	renderers: [
		'input' => Form_Renderer_Field_Input_AssocArray::class
	]
);

SysConf_Jet_Form_DefaultViews::registerNewFieldType('assoc-array', [
	'input' => 'field/input/assoc-array'
]);