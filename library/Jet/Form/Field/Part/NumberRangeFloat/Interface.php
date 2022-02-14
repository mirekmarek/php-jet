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
interface Form_Field_Part_NumberRangeFloat_Interface
{

	/**
	 * @return ?float
	 */
	public function getMinValue(): ?float;
	
	/**
	 * @param ?float $min
	 */
	public function setMinValue( ?float $min );
	
	/**
	 * @return ?float
	 */
	public function getMaxValue(): ?float;
	
	/**
	 * @param ?float $max
	 */
	public function setMaxValue( ?float $max ) : void;
	
	/**
	 * @return ?float
	 */
	public function getStep(): ?float;
	
	/**
	 * @param ?float $step
	 */
	public function setStep( ?float $step ) : void;
	
	
	/**
	 * @return ?int
	 */
	public function getPlaces(): ?int;
	
	/**
	 * @param ?int $places
	 */
	public function setPlaces( ?int $places ) : void;
	
}