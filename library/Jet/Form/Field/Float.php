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
class Form_Field_Float extends Form_Field_Input implements Form_Field_Part_NumberRangeFloat_Interface
{
	use Form_Field_Part_NumberRangeFloat_Trait;

	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_FLOAT;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_OUT_OF_RANGE => '',
	];
	
	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		parent::catchInput( $data );
		
		if($this->_value!=='') {
			$this->_value_raw = str_replace( ',', '.', $this->_value_raw );
			$this->_value = (float)$this->_value_raw;
			
			if($this->places>0) {
				$this->_value = round($this->_value, $this->places);
			}
		}
	}
}