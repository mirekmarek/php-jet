<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Validators;

use Jet\Form_Definition_Interface;
use JetStudio\ClassFinder as JS_ClassFinder;
use JetStudio\ClassMetaInfo;
use ReflectionClass;

class ClassFinder extends JS_ClassFinder
{
	
	protected array $interfaces = [
		Form_Definition_Interface::class
	];

	/**
	 * @return ValidatorClass[]
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}
	
	
	protected function classMetaInfoFactory( string $path, string $namespace, string $class_name, ReflectionClass $reflection ): ClassMetaInfo
	{
		return new ValidatorClass(
			$path,
			$namespace,
			$class_name,
			$reflection
		);
	}
}