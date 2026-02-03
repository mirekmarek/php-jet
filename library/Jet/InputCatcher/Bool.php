<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Bool extends InputCatcher
{
	protected string $_type = self::TYPE_BOOL;
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$this->value_raw = false;
		$this->value = false;
		$this->value_exists_in_the_input = true;
		
		if( $data->exists( $this->name ) ) {
			$this->value_raw = $data->getRaw( $this->name );
			$this->value = $data->getBool( $this->name );
		}
		
		$data->set( $this->name, $this->value );
	}
	
	
	protected function checkValue() : void
	{
	}
	
	public function getValue() : bool
	{
		return $this->value;
	}
}