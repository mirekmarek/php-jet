<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license EUPL 1.2  https://eupl.eu/1.2/en/
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

use ReflectionClass;
use Attribute;

/** @phpstan-consistent-constructor */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Application_Service_MetaInfo
{
	protected string $interface_class_name;
	protected string $group = '';
	protected bool $multiple_mode = false;
	protected string $module_name_prefix;
	protected bool $is_mandatory;
	protected string $name;
	protected string $description;
	
	
	public function __construct( string $group='', bool $is_mandatory=false, bool $multiple_mode=false, string $name = '', string $description = '', string $module_name_prefix=''  )
	{
	}
	
	/**
	 * @param ReflectionClass<BaseObject> $class
	 * @param array<string,string|bool> $attributes
	 * @return static
	 */
	public static function create( ReflectionClass $class, array $attributes ) : static
	{
		$definition = new static();
		$definition->setInterfaceClassName( $class->getName() );
		
		$definition->setGroup( $attributes['group'] );
		$definition->setIsMandatory( (bool)($attributes['is_mandatory']??false) );
		$definition->setName( $attributes['name'] );
		$definition->setDescription( $attributes['description']??'' );
		$definition->setModuleNamePrefix( $attributes['module_name_prefix']??'' );
		$definition->setMultipleMode( (bool)($attributes['multiple_mode']??false) );
		
		
		return $definition;
	}
	
	
	
	public function getGroup(): string
	{
		return $this->group;
	}
	
	public function setGroup( string $group ): void
	{
		$this->group = $group;
	}
	
	public function setInterfaceClassName( string $interface_class_name ): void
	{
		$this->interface_class_name = $interface_class_name;
	}
	
	public function getInterfaceClassName(): string
	{
		return $this->interface_class_name;
	}
	
	
	public function getModuleNamePrefix(): string
	{
		return $this->module_name_prefix;
	}
	
	public function setModuleNamePrefix( string $module_name_prefix ): void
	{
		$this->module_name_prefix = $module_name_prefix;
	}
	
	public function setIsMandatory( bool $is_mandatory ): void
	{
		$this->is_mandatory = $is_mandatory;
	}
	
	public function isMandatory(): bool
	{
		return $this->is_mandatory;
	}
	
	public function isMultipleMode(): bool
	{
		return $this->multiple_mode;
	}
	
	public function setMultipleMode( bool $multiple_mode ): void
	{
		$this->multiple_mode = $multiple_mode;
	}
	
	
	
	public function setName( string $name ): void
	{
		$this->name = $name;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setDescription( string $description ): void
	{
		$this->description = $description;
	}
	
	public function getDescription(): string
	{
		return $this->description;
	}
	
	
	
}