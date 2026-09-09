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

class InputCatcher_Callable extends InputCatcher {
	
	protected static string $type = 'callable';
	
	protected string $class_context = '';
	
	public function getClassContext(): string
	{
		return $this->class_context;
	}
	
	public function setClassContext( string $class_context ): void
	{
		$this->class_context = $class_context;
	}
	
	public function catchInput( array|Data_Array $data ): void
	{
		if(is_array($data)) {
			$data = new Data_Array($data);
		}
		
		$this->value = null;
		$name = (($this->name[0]=='/') ? $this->name : '/'.$this->name).'/';
		
		$this->value_exists_in_the_input = $data->exists( $name.'class' ) && $data->exists( $name.'method' );
		
		if( $this->value_exists_in_the_input ) {
			$this->value = [
				trim( $data->getString( $name.'class' ) ),
				trim( $data->getString( $name.'method' ) )
			];
			$this->value_raw = $this->value;
			
		} else {
			$this->value_raw = null;
			$this->value = $this->default_value;
		}

	}
	
	
	protected function checkValue(): void
	{
	}
}