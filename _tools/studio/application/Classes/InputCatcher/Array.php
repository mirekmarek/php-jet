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

class InputCatcher_Array extends InputCatcher {
	
	protected static string $type = 'array';
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$name = (($this->name[0]=='/') ? $this->name : '/'.$this->name);
		
		$this->value_exists_in_the_input = $data->exists( $name );
		
		$this->value = null;
		
		
		if( $this->value_exists_in_the_input ) {
			
			$values = $data->getRaw( $name );
			
			$this->value = [];
			
			foreach($values as $value) {
				$value = trim($value);
				if(!$value) {
					continue;
				}
				
				$this->value[] = $value;
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