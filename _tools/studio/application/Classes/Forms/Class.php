<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use ReflectionClass;
use Jet\Form_Definition_Interface;

class Forms_Class
{
	/**
	 * @var string
	 */
	protected string $script_path = '';
	
	/**
	 * @var ?ReflectionClass
	 */
	protected ?ReflectionClass $reflection = null;
	
	/**
	 * @var string
	 */
	protected string $namespace = '';
	
	/**
	 * @var string
	 */
	protected string $class_name = '';
	
	/**
	 * @var array|null
	 */
	protected array|null $_parents = null;
	
	/**
	 * @var string
	 */
	protected string $error = '';
	
	/**
	 * @var bool
	 */
	protected bool $is_new = false;
	
	/**
	 * @param string $script_path
	 * @param string $namespace
	 * @param string $class_name
	 * @param ReflectionClass|null $reflection
	 */
	public function __construct( string $script_path, string $namespace, string $class_name, ReflectionClass $reflection = null )
	{
		$this->script_path = $script_path;
		$this->reflection = $reflection;
		$this->namespace = $namespace;
		$this->class_name = $class_name;
	}
	
	/**
	 * @return bool
	 */
	public function isIsNew(): bool
	{
		return $this->is_new;
	}
	
	/**
	 * @param bool $is_new
	 */
	public function setIsNew( bool $is_new ): void
	{
		$this->is_new = $is_new;
	}
	
	
	/**
	 * @return string
	 */
	public function getScriptPath(): string
	{
		return $this->script_path;
	}
	
	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}
	
	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->class_name;
	}
	
	/**
	 * @return ReflectionClass
	 */
	public function getReflection(): ReflectionClass
	{
		return $this->reflection;
	}
	
	
	/**
	 * @return string
	 */
	public function getFullClassName(): string
	{
		return $this->namespace . '\\' . $this->class_name;
	}

	
	/**
	 * @return string
	 */
	public function getError(): string
	{
		return $this->error;
	}
	
	/**
	 * @param string $error
	 */
	public function setError( string $error ): void
	{
		$this->error = $error;
	}
	
	/**
	 * @return array
	 */
	public function getImplements(): array
	{
		if( !$this->reflection ) {
			return [];
		}
		return $this->reflection->getInterfaceNames();
	}
	
	/**
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		if( !$this->reflection ) {
			return false;
		}
		
		return $this->reflection->isAbstract();
	}
	
	/**
	 * @return string
	 */
	public function getExtends(): string
	{
		if( !$this->reflection ) {
			return '';
		}
		
		return $this->reflection->getParentClass()->getName();
	}
	
	/**
	 * @return array
	 */
	public function getParents(): array
	{
		if( $this->_parents === null ) {
			$this->_parents = [];
			
			$getParent = function( Forms_Class $class ) use ( &$getParent ) {
				if( $class->getExtends() ) {
					$e_class = Forms::getClass( $class->getExtends() );
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
	
	
	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function isDescendantOf( DataModel_Class $class ): bool
	{
		$parents = $this->getParents();
		
		return in_array( $class->getFullClassName(), $parents );
	}
	
	/**
	 * @param string $property_name
	 *
	 * @return bool|string
	 */
	public function getPropertyDeclaringClass( string $property_name ): bool|string
	{
		$parents = $this->getParents();
		
		foreach( $parents as $class_name ) {
			$class = DataModels::getClass( $class_name );
			
			if( !$class ) {
				continue;
			}
			
			if( $class->getReflection()->hasProperty( $property_name ) ) {
				return $class_name;
			}
		}
		
		return false;
	}
	
	/**
	 * @return Forms_Class_Property[]
	 */
	public function getProperties() : array
	{
		$properties = [];
		
		/**
		 * @var Form_Definition_Interface $i
		 */
		$class = $this->getFullClassName();
		
		$i = new $class();
		
		$form_definition = $i->getFormFieldsDefinition();
		
		foreach($this->reflection->getProperties() as $property) {
			if($property->getName()[0]=='_') {
				continue;
			}
			
			if($property->isStatic()) {
				continue;
			}
			
			$p = new Forms_Class_Property( $this, $property->getName(), $property, $form_definition[$property->getName()]??null );
			
			$properties[$p->getName()] = $p;
		}
		
		return $properties;
	}
	
	public function generateViewScript( string $form_variable_name='form', string $form_view_property_name='form', bool $subform_is_localized=true ) : string
	{
		$view = Application::getView('forms');
		$view->setVar('class', $this);
		$view->setVar('form_variable_name', $form_variable_name);
		$view->setVar('form_view_property_name', $form_view_property_name);
		$view->setVar('subform_is_localized', $subform_is_localized);
		
		return $view->render('view_script_generator');
	}
}