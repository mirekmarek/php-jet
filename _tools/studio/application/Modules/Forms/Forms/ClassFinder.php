<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Forms;

use Jet\Form_Definition_Interface;
use JetStudio\ClassFinder;
use JetStudio\ClassMetaInfo;
use ReflectionClass;

class Forms_ClassFinder extends ClassFinder
{
	
	protected array $interfaces = [
		Form_Definition_Interface::class
	];

	/**
	 * @return Forms_Class[]
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}
	
	
	protected function classMetaInfoFactory( string $path, string $namespace, string $class_name, ReflectionClass $reflection ): ClassMetaInfo
	{
		return new Forms_Class(
			$path,
			$namespace,
			$class_name,
			$reflection
		);
	}
}