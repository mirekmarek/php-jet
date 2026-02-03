<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Strings extends InputCatcher
{
	protected string $_type = self::TYPE_STRINGS;
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$this->value = [];
		
		if( $data->exists( $this->name ) ) {
			$this->value_exists_in_the_input = true;
			$this->value_raw = $data->getRaw( $this->name );
			
			if( is_array( $this->value_raw ) ) {
				if( !empty( $this->value_raw ) ) {
					$this->value = [];
					foreach( $this->value_raw as $item ) {
						$this->value[] = $this->encodeString( $item );
					}
				}
			} else {
				$this->value = [$this->encodeString( $this->value_raw )];
			}
		}
		
		$this->value_raw = $this->value;
	}
	
	protected function encodeString( string $string ) : string
	{
		return Data_Text::emojiToHTMLEntities(trim(Data_Text::htmlSpecialChars( $string )));
	}
	
	
	protected function checkValue() : void
	{
		if(!is_array($this->default_value)) {
			$this->default_value = [$this->default_value];
		}
		if(!is_array($this->value)) {
			$this->value = [$this->value];
		}
	}
	
	
	/**
	 * @return array<string>
	 */
	public function getValue() : array
	{
		return $this->value;
	}
	
	
}