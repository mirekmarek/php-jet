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

class InputCatcher_MetaTags extends InputCatcher {
	
	protected static string $type = 'meta-tags';
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$name = (($this->name[0]=='/') ? $this->name : '/'.$this->name).'/';
		
		$this->value_exists_in_the_input = $data->exists( $name.'attribute' ) && $data->exists( $name.'attribute_value' ) && $data->exists( $name.'content' );
		
		$this->value = null;
		
		
		if( $this->value_exists_in_the_input ) {
			
			$attributes = $data->getRaw( $name.'attribute' );
			$attribute_values = $data->getRaw( $name.'attribute_value' );
			$contents = $data->getRaw( $name.'content' );
			
			$this->value = [];
			
			foreach($attributes as $i=>$attribute) {
				$attribute = trim($attribute);
				$attribute_value = trim($attribute_values[$i]);
				$content = trim($contents[$i]);
				
				if(!$attribute && !$attribute_value && !$content) {
					continue;
				}
				
				$this->value[] = [
					'attribute' => $attribute,
					'attribute_value' => $attribute_value,
					'content' => $content
				];
			}
			
			$this->value_raw = $this->value;
			
		} else {
			$this->value_raw = null;
			$this->value = $this->default_value;
		}
		
	}
	
	protected function checkValue(): void
	{
	}
	
	public function getValue(): mixed
	{
		if(!$this->value) {
			return [];
		}
		
		return $this->value;
	}

}