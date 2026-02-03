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
trait Form_Field_Part_NumberRangeFloat_Trait
{
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_FLOAT,
		label: 'Minimal value',
		getter: 'getMinValue',
		setter: 'setMinValue',
	)]
	protected null|float $min_value = null;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_FLOAT,
		label: 'Maximal value',
		getter: 'getMaxValue',
		setter: 'setMaxValue',
	)]
	protected null|float $max_value = null;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_FLOAT,
		label: 'Step',
		getter: 'getStep',
		setter: 'setStep',
	)]
	protected float $step = 0.01;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Places',
		getter: 'getPlaces',
		setter: 'setPlaces',
	)]
	protected null|int $places = null;
	
	/**
	 * @return ?float
	 */
	public function getMinValue(): ?float
	{
		return $this->min_value;
	}
	
	/**
	 * @param ?float $min
	 */
	public function setMinValue( ?float $min ) : void
	{
		$this->min_value = $min;
	}
	
	/**
	 * @return ?float
	 */
	public function getMaxValue(): ?float
	{
		return $this->max_value;
	}
	
	/**
	 * @param ?float $max
	 */
	public function setMaxValue( ?float $max ) : void
	{
		$this->max_value = $max;
	}
	
	/**
	 * @return ?float
	 */
	public function getStep(): ?float
	{
		return $this->step;
	}
	
	/**
	 * @param ?float $step
	 */
	public function setStep( ?float $step ) : void
	{
		$this->step = $step;
	}
	
	
	/**
	 * @return ?int
	 */
	public function getPlaces(): ?int
	{
		return $this->places;
	}
	
	/**
	 * @param ?int $places
	 */
	public function setPlaces( ?int $places ) : void
	{
		$this->places = $places;
	}
	
	public function getValidator() : Validator
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		/**
		 * @var Validator_Float $validator
		 */
		$validator = $this->validator;
		$validator->setMinValue( $this->getMinValue() );
		$validator->setMaxValue( $this->getMaxValue() );
		
		return $validator;
	}
	
}