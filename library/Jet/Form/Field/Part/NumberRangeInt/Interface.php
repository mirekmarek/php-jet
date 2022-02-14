<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface Form_Field_Part_NumberRangeInt_Interface
{
	
	/**
	 * @return  ?int 
	 */
	public function getMinValue():  ?int ;
	
	/**
	 * @param ?int $min
	 */
	public function setMinValue( ?int $min ) : void;
	
	/**
	 * @return  ?int 
	 */
	public function getMaxValue():  ?int ;
	
	/**
	 * @param ?int $max
	 */
	public function setMaxValue( ?int $max ) : void;
	
	/**
	 * @return  ?int 
	 */
	public function getStep():  ?int ;
	
	/**
	 * @param ?int $step
	 */
	public function setStep( ?int $step ) : void;
	
}