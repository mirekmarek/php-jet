<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Float extends InputCatcher
{
	protected string $_type = self::TYPE_STRING;
	
	#[InputCatcher_Definition_InputCatcherOption(
		type: InputCatcher_Definition_InputCatcherOption::TYPE_INT,
		label: 'Decimal places',
		getter: 'getPlaces',
		setter: 'setPlaces',
	)]
	protected int $places = 2;
	
	public function getPlaces(): int
	{
		return $this->places;
	}
	
	public function setPlaces( int $places ): void
	{
		$this->places = $places;
	}
	
	
	
	protected function checkValue() : void
	{
		if($this->value!=='') {
			$this->value_raw = str_replace( ',', '.', $this->value_raw );
			$this->value = (float)$this->value_raw;
			
			if($this->places>0) {
				$this->value = round($this->value, $this->places);
			}
		} else {
			$this->value = null;
		}
	}
	
	
	public function getValue(): ?float
	{
		return $this->value;
	}
	
	
}