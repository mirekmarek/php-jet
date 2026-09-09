<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Data_Array;
use Jet\InputCatcher;

class InputCatcher_AssocArray extends InputCatcher {
	
	protected static string $type = 'assoc-array';
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$name = (($this->name[0]=='/') ? $this->name : '/'.$this->name).'/';
		
		$this->value_exists_in_the_input = $data->exists( $name.'key' ) && $data->exists( $name.'value' );
		
		$this->value = null;
		
		
		if( $this->value_exists_in_the_input ) {
			
			$keys = $data->getRaw( $name.'key' );
			$values = $data->getRaw( $name.'value' );
			
			$this->value = [];
			
			foreach($keys as $i=>$key) {
				$key = trim($key);
				if(!$key) {
					continue;
				}
				
				$val = trim($values[$i]);
				
				$this->value[$key] = $val;
			}
			
			$this->value_raw = $this->value;
			
		} else {
			$this->value_raw = null;
			$this->value = $this->default_value;
		}
		
	}
	
	public function getValue() : array
	{
		if(!$this->value) {
			return [];
		}
		return $this->value;
	}
	
	protected function checkValue(): void
	{
	}
	
}