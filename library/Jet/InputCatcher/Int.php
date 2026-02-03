<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_Int extends InputCatcher
{
	protected string $_type = self::TYPE_INT;
	
	protected function checkValue() : void
	{
		if($this->value!=='') {
			$this->value_raw = (int)$this->value_raw;
			$this->value = $this->value_raw;
		} else {
			$this->value = null;
		}
	}
	
	public function getValue() : ?int
	{
		return $this->value;
	}
}