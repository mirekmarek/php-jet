<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use ReflectionClass;

abstract class ClassMetaInfo
{
	
	protected string $script_path = '';
	protected ?ReflectionClass $reflection = null;
	protected string $namespace = '';
	protected string $class_name = '';
	protected array|null $_parents = null;
	protected string $error = '';
	protected bool $is_new = false;
	
	public function __construct( string $script_path, string $namespace, string $class_name, ?ReflectionClass $reflection = null )
	{
		$this->script_path = $script_path;
		$this->reflection = $reflection;
		$this->namespace = $namespace;
		$this->class_name = $class_name;
	}
	
	public function isIsNew(): bool
	{
		return $this->is_new;
	}
	
	public function setIsNew( bool $is_new ): void
	{
		$this->is_new = $is_new;
	}
	
	public function getScriptPath(): string
	{
		return $this->script_path;
	}
	
	public function getNamespace(): string
	{
		return $this->namespace;
	}
	
	public function getClassName(): string
	{
		return $this->class_name;
	}
	
	public function getReflection(): ReflectionClass
	{
		return $this->reflection;
	}
	
	public function getFullClassName(): string
	{
		return $this->namespace . '\\' . $this->class_name;
	}
	
	public function getError(): string
	{
		return $this->error;
	}
	
	public function setError( string $error ): void
	{
		$this->error = $error;
	}
	
	public function getImplements(): array
	{
		if( !$this->reflection ) {
			return [];
		}
		return $this->reflection->getInterfaceNames();
	}
	
	public function isAbstract(): bool
	{
		if( !$this->reflection ) {
			return false;
		}
		
		return $this->reflection->isAbstract();
	}
	
	public function getExtends(): string
	{
		if( !$this->reflection ) {
			return '';
		}
		
		return $this->reflection->getParentClass()->getName();
	}
	
	public function getParents(): array
	{
		if( $this->_parents === null ) {
			$this->_parents = [];
			
			$getParent = function( ClassMetaInfo $class ) use ( &$getParent ) {
				if( $class->getExtends() ) {
					$e_class = static::get( $class->getExtends() );
					if( $e_class ) {
						$this->_parents[] = $e_class->getFullClassName();
						$getParent( $e_class );
					}
				}
				
			};
			
			$getParent( $this );
		}
		
		
		return $this->_parents;
	}
	
	
	public function isDescendantOf( ClassMetaInfo $class ): bool
	{
		$parents = $this->getParents();
		
		return in_array( $class->getFullClassName(), $parents );
	}
	

	public function getPropertyDeclaringClass( string $property_name ): bool|string
	{
		$parents = $this->getParents();
		
		foreach( $parents as $class_name ) {
			$class = static::get( $class_name );
			
			if( !$class ) {
				continue;
			}
			
			if( $class->getReflection()->hasProperty( $property_name ) ) {
				return $class_name;
			}
		}
		
		return false;
	}
	
	abstract public static function get( string $class_name ) : ?static;
}
