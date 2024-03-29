<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_Range extends Form_Field_Input implements Form_Field_Part_NumberRangeFloat_Interface
{
	use Form_Field_Part_NumberRangeFloat_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_RANGE;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
		Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Out of range',
	];
	
	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		parent::catchInput( $data );
		
		if($this->_value!=='') {
			$this->_value = (float)$this->_value_raw;
		}
	}
	
}