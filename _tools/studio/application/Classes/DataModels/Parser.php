<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\IO_File;
use Jet\IO_Dir;

class DataModels_Parser {

	/**
	 * @var Project_Namespace[]
	 */
	protected $namespaces;

	/**
	 * @var ClassParser_Class[]
	 */
	protected $parsed_classes = [];

	/**
	 * @var array
	 */
	protected $class_extends = [];

	/**
	 * @var Project_Namespace[]
	 */
	protected $class_namespaces = [];


	/**
	 * @var DataModels_Parser_Class[]
	 */
	protected $classes = [];


	/**
	 * DataModels_Parser constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @return Project_Namespace[]
	 */
	public function getNamespaces()
	{
		if(!$this->namespaces) {
			$this->namespaces = [];

			foreach( Project::getNamespaces() as $ns ) {
				$this->namespaces[$ns->getNamespace()] = $ns;
			}
		}

		return $this->namespaces;
	}

	/**
	 * @param Project_Namespace[] $namespaces
	 */
	public function setNamespaces( $namespaces )
	{
		$this->namespaces = $namespaces;
	}


	/**
	 *
	 */
	public function parse()
	{
		foreach( $this->getNamespaces() as $namespace ) {
			if($namespace->getId()=='jet') {
				continue;
			}

			$this->readNamespace($namespace);
		}


		$data_model_classes = [];

		foreach( array_keys($this->parsed_classes) as $class_name ) {
			$data_model_classes[$class_name] = [];

			$ec = $class_name;
			do {
				if(empty( $this->class_extends[$ec])) {
					break;
				}
				$ec = $this->class_extends[$ec];

				$data_model_classes[$class_name][] = $ec;

			} while(true);
		}

		foreach( $data_model_classes as $class_name=>$extends ) {
			$is_dm = false;

			$i = 0;
			foreach( $extends as $ex  ) {
				$i++;
				if(strpos($ex, 'Jet\DataModel')===0) {
					$extends = array_slice($extends, 0, $i);
					$data_model_classes[$class_name] = $extends;
					$is_dm = true;
					break;
				}
			}

			if(!$is_dm) {
				unset($data_model_classes[$class_name]);
			}
		}

		foreach( $data_model_classes as $target_class_name=>$classes ) {
			$target_class = $this->parsed_classes[$target_class_name];
			$classes = array_reverse($classes);
			$classes[] = $target_class_name;

			$res = new DataModels_Parser_Class( $this->class_namespaces[$target_class_name], $target_class_name );

			$extends = '';
			foreach( $classes as $i=>$parse_class_name ) {
				if($i==0) {
					$res->setBaseClass( $parse_class_name );

					continue;
				}

				$res->setIsAbstract( $this->parsed_classes[$parse_class_name]->is_abstract );

				if($extends) {
					$res->setExtends( $extends );
				}

				if($this->parsed_classes[$parse_class_name]->implements) {
					$implements = [];

					foreach( $this->parsed_classes[$parse_class_name]->implements as $i ) {
						$implements[] = $this->parsed_classes[$parse_class_name]->parser->getFullClassName( $i );
					}

					$res->setImplements( $implements );
				}


				$res->parse( $this, $this->parsed_classes[$parse_class_name], $target_class );

				$extends = $parse_class_name;
			}

			$this->classes[$res->getFullClassName()] = $res;
		}

	}

	/**
	 * @return DataModels_Parser_Class[]
	 */
	public function getClasses()
	{
		return $this->classes;
	}




	/**
	 * @param string $full_class_name
	 */
	protected function readClass( $full_class_name )
	{

		if(isset( $this->parsed_classes[$full_class_name])) {
			return;
		}

		$p = explode('\\', $full_class_name);

		$class_name = array_pop($p);
		$namespace = implode('\\', $p);

		if(!isset($this->namespaces[$namespace])) {
			return;
		}

		$namespace = $this->namespaces[$namespace];


		$path = $namespace->getRootDirPath().str_replace('_', DIRECTORY_SEPARATOR, $class_name).'.php';

		$parser = new ClassParser( IO_File::read( $path ) );

		if(!isset($parser->classes[$class_name])) {
			return;
		}

		$class = $parser->classes[$class_name];

		$this->addClass( $namespace, $parser, $class );
	}

	/**
	 * @param Project_Namespace $namespace
	 *
	 * @param string $subdir
	 */
	protected function readNamespace( Project_Namespace $namespace, $subdir='' )
	{

		$dir = $namespace->getRootDirPath().$subdir;

		$dirs = IO_Dir::getList( $dir, '*', true, false );
		$files = IO_Dir::getList( $dir, '*.php', false, true );

		foreach( $files as $path=>$name ) {

			//echo $path.SysConf_Jet::EOL();

			$parser = new ClassParser( IO_File::read( $path ) );

			foreach( $parser->classes as $class ) {
				if($class->extends) {
					$this->addClass( $namespace, $parser, $class );
				}
			}
		}

		foreach( $dirs as $path=>$name ) {
			$this->readNamespace( $namespace, $subdir.$name.'/' );
		}

	}

	/**
	 * @param Project_Namespace $namespace
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	protected function addClass( Project_Namespace $namespace, ClassParser $parser, ClassParser_Class $class )
	{
		$class_name = $parser->getFullClassName($class->name);

		$this->parsed_classes[$class_name] = $class;
		$this->class_namespaces[$class_name] = $namespace;

		if($class->extends) {
			$this->class_extends[$class_name] = $parser->getFullClassName($class->extends);

			$this->readClass( $this->class_extends[$class_name] );
		}
	}

	/**
	 * @param string $class_name
	 *
	 * @return Project_Namespace
	 */
	public function getClassNamespace( $class_name )
	{
		return $this->class_namespaces[$class_name];
	}

	/**
	 * @param $class_name
	 * @param $constant_name
	 *
	 * @return string
	 */
	public function getConstantValue( $class_name, $constant_name )
	{

		if(!isset( $this->parsed_classes[$class_name])) {
			$this->readClass( $class_name );
		}

		$class = $this->parsed_classes[$class_name];

		if(!isset($class->constants[$constant_name])) {
			$parent = $class->parser->getFullClassName( $class->extends );

			return $this->getConstantValue( $parent, $constant_name );
		}

		return eval('return '.$class->constants[$constant_name]->value);
	}

}