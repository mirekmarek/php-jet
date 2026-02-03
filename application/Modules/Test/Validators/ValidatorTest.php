<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Validators;

use Jet\Validator;

class ValidatorTest
{
	protected string $name;
	protected Validator $validator;
	protected array $valid_values;
	protected array $invalid_values;
	
	public function __construct( Validator $validator, array $valid_values, array $invalid_values )
	{
		$this->name = get_class( $validator );
		$this->validator = $validator;
		$this->valid_values = $valid_values;
		$this->invalid_values = $invalid_values;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName( string $name ): void
	{
		$this->name = $name;
	}
	
	
	
	public function getValidator(): Validator
	{
		return $this->validator;
	}
	
	public function getValidValues(): array
	{
		return $this->valid_values;
	}
	
	public function getInvalidValues(): array
	{
		return $this->invalid_values;
	}
	
	
	
}