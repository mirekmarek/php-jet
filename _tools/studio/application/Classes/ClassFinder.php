<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\IO_Dir;
use Jet\IO_File;
use ReflectionClass;

abstract class ClassFinder {
	/**
	 * @var ClassMetaInfo[]
	 */
	protected array $classes = [];
	protected bool $_new_founded = false;
	protected array $dirs = [];
	
	protected array $parent_classes = [];
	protected array $interfaces = [];
	
	public function __construct( array $dirs )
	{
		$this->dirs = $dirs;
	}
	

	public function find(): void
	{
		do {
			$this->_new_founded = false;
			foreach( $this->dirs as $dir ) {
				$this->readDir( $dir );
			}
		} while( $this->_new_founded );
		
		ksort( $this->classes );
	}
	
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
				
				
				$is_relevant = false;
				
				foreach($this->parent_classes as $dm_class) {
					if($reflection->isSubclassOf($dm_class)) {
						$is_relevant = true;
						break;
					}
				}
				foreach($this->interfaces as $interface) {
					if($reflection->implementsInterface($interface)) {
						$is_relevant = true;
						break;
					}
				}
				
				if(!$this->parent_classes && !$this->interfaces) {
					$is_relevant = true;
				}
				
				if(!$is_relevant) {
					continue;
				}
				
				$cl = $this->classMetaInfoFactory(
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
	
	public function getClasses(): array
	{
		return $this->classes;
	}
	
	abstract protected function classMetaInfoFactory(
		string $path,
		string $namespace,
		string $class_name,
		ReflectionClass $reflection
	) : ClassMetaInfo;
	
}