<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Exception;
use Jet\IO_File;
use Jet\IO_Dir;
use ReflectionClass;

/**
 */
class DataModels_ClassFinder {

	/**
	 * @var DataModel_Class[]
	 */
	protected $classes = [];

	/**
	 * @var bool
	 */
	protected $_new_founded = false;

	/**
	 * @var array
	 */
	protected $dirs = [];

	/**
	 * @var string[]
	 */
	protected $parent_classes = [
		'Jet\DataModel',
		'Jet\DataModel_Related_1to1',
		'Jet\DataModel_Related_1toN',
		'Jet\DataModel_Related_MtoN',
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
	public function find()
	{
		do {
			$this->_new_founded = false;
			foreach( $this->dirs as $dir ) {
				$this->readDir($dir);
			}
		} while($this->_new_founded);

		ksort($this->classes);

		foreach( $this->classes as $class ) {
			try {
				/**
				 * @var DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN $definition
				 */
				$definition = DataModel::getDataModelDefinition( $class->getFullClassName() );
			} catch(DataModel_Exception $e) {
				$class->setError($e->getMessage());
				continue;
			}
			$class->setDefinition( $definition );
		}

	}
	/**
	 * @param string $dir
	 */
	protected function readDir( $dir  )
	{
		$dirs = IO_Dir::getList( $dir, '*', true, false );
		$files = IO_Dir::getList( $dir, '*.php', false, true );

		foreach( $files as $path=>$name ) {
			$file_data = IO_File::read($path);

			if(strpos($file_data, 'DataModel')===false) {
				continue;
			}

			$parser = new ClassParser($file_data);

			foreach($parser->classes as $class ) {
				$full_name = $parser->namespace->namespace.'\\'.$class->name;
				if(isset($this->classes[$full_name])) {
					continue;
				}

				$reflection = new ReflectionClass( $full_name );

				$parent_class = $reflection->getParentClass();

				if(!$parent_class) {
					continue;
				}

				$parent = $reflection->getParentClass()->getName();

				if(!in_array($parent, $this->parent_classes)) {
					continue;
				}

				$cl = new DataModel_Class(
					$path,
					$reflection,
					$parser->namespace->namespace,
					$class->name
				);

				$this->classes[$cl->getFullClassName()] = $cl;
				$this->parent_classes[] = $cl->getFullClassName();

				$this->_new_founded = true;

			}

		}

		foreach( $dirs as $path=>$name ) {
			$this->readDir( $path );
		}
	}

	/**
	 * @return DataModel_Class[]
	 */
	public function getClasses()
	{
		return $this->classes;
	}


}