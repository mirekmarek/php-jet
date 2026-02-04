<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_String extends InputCatcher
{
	protected string $_type = self::TYPE_STRING;
	
	protected function checkValue() : void
	{
		$this->value_raw = $this->value_raw ? $this->encodeString( $this->value_raw ) : $this->value_raw;
		$this->value = $this->encodeString( $this->value );
	}
	
	protected function encodeString( string $string ) : string
	{
		return Data_Text::emojiToHTMLEntities(
			trim(Data_Text::htmlSpecialChars( $string ))
		);
	}
	
	
	public function getValue() : string
	{
		return $this->value;
	}
}