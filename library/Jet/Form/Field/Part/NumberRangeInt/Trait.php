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
trait Form_Field_Part_NumberRangeInt_Trait
{
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Minimal value',
		getter: 'getMinValue',
		setter: 'setMinValue',
	)]
	protected ?int $min_value = null;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal value',
		getter: 'getMaxValue',
		setter: 'setMaxValue',
	)]
	protected ?int $max_value = null;
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Step',
		getter: 'getStep',
		setter: 'setStep',
	)]
	protected ?int $step = null;
	
	
	/**
	 * @return ?int
	 */
	public function getMinValue(): ?int
	{
		return $this->min_value;
	}
	
	/**
	 * @param ?int $min
	 */
	public function setMinValue( ?int $min ) : void
	{
		$this->min_value = $min;
	}
	
	/**
	 * @return ?int
	 */
	public function getMaxValue(): ?int
	{
		return $this->max_value;
	}
	
	/**
	 * @param ?int $max
	 */
	public function setMaxValue( ?int $max ) : void
	{
		$this->max_value = $max;
	}
	
	/**
	 * @return ?int
	 */
	public function getStep(): ?int
	{
		return $this->step;
	}
	
	/**
	 * @param ?int $step
	 */
	public function setStep( ?int $step ) : void
	{
		$this->step = $step;
	}
	
	
	public function getValidator() : Validator
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		/**
		 * @var Validator_Int $validator
		 */
		$validator = $this->validator;
		$validator->setMinValue( $this->getMinValue() );
		$validator->setMaxValue( $this->getMaxValue() );
		
		return $validator;
	}
	
}