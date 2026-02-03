<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Ints extends InputCatcher
{
	protected string $_type = self::TYPE_INTS;
	
	
	#[InputCatcher_Definition_InputCatcherOption(
		type: InputCatcher_Definition_InputCatcherOption::TYPE_BOOL,
		label: 'Ignore zeros',
		getter: 'getIgnoreZeros',
		setter: 'setIgnoreZeros',
	)]
	protected bool $ignore_zeros = false;
	
	
	public function getIgnoreZeros(): bool
	{
		return $this->ignore_zeros;
	}
	
	public function setIgnoreZeros( bool $ignore_zeros ): void
	{
		$this->ignore_zeros = $ignore_zeros;
	}
	
	
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
						$v = $this->retype( $item );
						if(!$v && $this->ignore_zeros) {
							continue;
						}
						$this->value[] = $v;
					}
				}
			} else {
				$this->value = [$this->retype( $this->value_raw )];
			}
		} else {
			$this->value = $this->default_value;
		}
		
		$this->value_raw = $this->value;
	}
	
	protected function retype( string $string ) : int
	{
		return (int)$string;
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
	 * @return array<int>
	 */
	public function getValue() :array
	{
		return $this->value;
	}
	
}