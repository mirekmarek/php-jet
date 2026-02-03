<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_StringRaw extends InputCatcher
{
	protected string $_type = self::TYPE_STRING_RAW;
	
	protected function checkValue() : void
	{
		$this->value_raw = trim( $this->value_raw );
		$this->value_raw = Data_Text::emojiToHTMLEntities( $this->value_raw );
		$this->value = $this->value_raw;
	}
	
	
	public function getValue() : string
	{
		return $this->value;
	}
	
}