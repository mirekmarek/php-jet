<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\InputCatchers;

use Jet\InputCatcher;

class InputCatcherTest {
	protected string $name;
	protected InputCatcher $input_catcher;
	protected array $inputs;
	
	public function __construct( InputCatcher $input_catcher, array $inputs )
	{
		$this->name = get_class( $input_catcher );
		$this->input_catcher = $input_catcher;
		$this->inputs = $inputs;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName( string $name ): void
	{
		$this->name = $name;
	}
	
	public function getInputCatcher(): InputCatcher
	{
		return $this->input_catcher;
	}
	
	public function getInputs(): array
	{
		return $this->inputs;
	}
	
	
	
}