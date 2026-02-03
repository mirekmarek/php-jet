<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
class Entity_InputCatcher_Definition_SubEntity_InputCatchers extends Entity_InputCatcher_Definition
{
	protected false|string $type = 'sub_input_catchers';
	protected bool $is_sub_input_catchers = true;
	
	protected string $factory_method_name;
	
	/**
	 * @param object $context_object
	 * @param string $property_name
	 * @param array<string,mixed> $definition_data
	 */
	public function __construct( object $context_object, string $property_name, array $definition_data )
	{
		$this->init( $context_object, $property_name, $definition_data );
		$this->factory_method_name = $definition_data['factory_method_name'];
	}
	
	public function setType( string|false $type ): void
	{
	}
	
	public function getFactoryMethodName(): string
	{
		return $this->factory_method_name;
	}
	
}