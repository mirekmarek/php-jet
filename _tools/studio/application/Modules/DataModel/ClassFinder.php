<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Error;
use Exception;
use Jet\DataModel;
use Jet\DataModel_Related_1to1;
use Jet\DataModel_Related_1toN;
use JetStudio\ClassFinder as JS_ClassFinder;
use JetStudio\ClassMetaInfo;
use ReflectionClass;

class ClassFinder extends JS_ClassFinder
{

	
	protected array $parent_classes = [
		DataModel::class,
		DataModel_Related_1to1::class,
		DataModel_Related_1toN::class
	];

	public function find(): void
	{
		parent::find();

		foreach( $this->classes as $class ) {
			try {
				/**
				 * @var DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $definition
				 */
				$definition = DataModel::getDataModelDefinition( $class->getFullClassName() );
			} catch( Exception|Error $e ) {
				$class->setError( $e->getMessage() );
				
				$this->problems[$class->getFullClassName()] = $e->getMessage();
				continue;
			}
			$class->setDefinition( $definition );
		}

	}
	
	protected function classMetaInfoFactory(
		string $path,
		string $namespace,
		string $class_name,
		ReflectionClass $reflection
	): ClassMetaInfo
	{
		return new DataModel_Class(
			$path,
			$namespace,
			$class_name,
			$reflection
		);
	}


	/**
	 * @return DataModel_Class[]
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}
}