<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Exception;
use Jet\DataModel_Related_1to1;
use Jet\DataModel_Related_1toN;
use Jet\IO_File;
use Jet\IO_Dir;
use ReflectionClass;

/**
 */
class DataModels_ClassFinder
{

	/**
	 * @var DataModel_Class[]
	 */
	protected array $classes = [];

	/**
	 * @var bool
	 */
	protected bool $_new_founded = false;

	/**
	 * @var array
	 */
	protected array $dirs = [];

	/**
	 * @var string[]
	 */
	protected array $parent_classes = [
		DataModel::class,
		DataModel_Related_1to1::class,
		DataModel_Related_1toN::class
	];

	/**
	 *
	 * @param array $dirs
	 */
	public function __construct( array $dirs )
	{
		$this->dirs = $dirs;
	}

	/**
	 *
	 */
	public function find(): void
	{
		do {
			$this->_new_founded = false;
			foreach( $this->dirs as $dir ) {
				$this->readDir( $dir );
			}
		} while( $this->_new_founded );

		ksort( $this->classes );

		foreach( $this->classes as $class ) {
			try {
				/**
				 * @var DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $definition
				 */
				$definition = DataModel::getDataModelDefinition( $class->getFullClassName() );
			} catch( DataModel_Exception $e ) {
				$class->setError( $e->getMessage() );
				continue;
			}
			$class->setDefinition( $definition );
		}

	}

	/**
	 * @param string $dir
	 */
	protected function readDir( string $dir ): void
	{
		$dirs = IO_Dir::getList( $dir, '*', true, false );
		$files = IO_Dir::getList( $dir, '*.php', false, true );

		foreach( $files as $path => $name ) {
			$file_data = IO_File::read( $path );

			if( !str_contains( $file_data, 'DataModel' ) ) {
				continue;
			}

			$parser = new ClassParser( $file_data );

			foreach( $parser->classes as $class ) {
				$full_name = $parser->namespace->namespace . '\\' . $class->name;
				if( isset( $this->classes[$full_name] ) ) {
					continue;
				}

				$reflection = new ReflectionClass( $full_name );
				
				
				$is_dm = false;
				foreach($this->parent_classes as $dm_class) {
					if($reflection->isSubclassOf($dm_class)) {
						$is_dm = true;
						break;
					}
				}
				
				if(!$is_dm) {
					continue;
				}

				$cl = new DataModel_Class(
					$path,
					$parser->namespace->namespace,
					$class->name,
					$reflection
				);

				$this->classes[$cl->getFullClassName()] = $cl;
				$this->parent_classes[] = $cl->getFullClassName();

				$this->_new_founded = true;

			}

		}

		foreach( $dirs as $path => $name ) {
			$this->readDir( $path );
		}
	}

	/**
	 * @return DataModel_Class[]
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}


}