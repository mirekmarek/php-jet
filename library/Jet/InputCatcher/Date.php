<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Date extends InputCatcher
{
	protected string $_type = self::TYPE_DATE;
	
	protected function checkValue() : void
	{
		if($this->value!=='') {
			try {
				$this->value = Data_DateTime::catchDateTime( $this->value );
			} catch(\Exception $e) {
				$this->value = $this->getDefaultValue();
			}
		} else {
			$this->value = $this->getDefaultValue();
		}
		
		if($this->value) {
			$this->value->setOnlyDate( true );
		}
		
	}
	
	
	public function getDefaultValue() : ?Data_DateTime
	{
		if(!$this->default_value) {
			return null;
		}
		
		$date =  Data_DateTime::catchDateTime( $this->default_value );
		$date->setOnlyDate( true );
		
		return $date;
	}
	
	public function getValue() :?Data_DateTime
	{
		return $this->value;
	}
	
	
}