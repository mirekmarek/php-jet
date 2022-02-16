<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


use ReflectionClass;

class Attributes
{
	/**
	 * @param ReflectionClass $reflection
	 *
	 * @return ReflectionClass[]
	 */
	protected static function getClasses( ReflectionClass $reflection ) : array
	{
		$classes = [$reflection->getName() => $reflection];

		if( ($parent = $reflection->getParentClass()) ) {
			do {
				$classes[$parent->getName()] = $parent;
			} while( ($parent = $parent->getParentClass()) );
		}

		return array_reverse( $classes );
	}

	public static function getClassPropertyDefinition( ReflectionClass $class, string $attribute_name ): array
	{

		$classes = static::getClasses( $class );

		$properties_definition_data = [];

		foreach( $classes as $class ) {
			foreach( $class->getProperties() as $property ) {

				$attributes = $property->getAttributes( $attribute_name );

				if( !$attributes ) {
					continue;
				}

				$attrs = [];

				foreach( $attributes as $attr ) {
					foreach( $attr->getArguments() as $k => $v ) {
						$attrs[$k] = $v;
					}
				}

				$property_name = $property->getName();

				if( !isset( $properties_definition_data[$property_name] ) ) {
					$properties_definition_data[$property_name] = $attrs;
				} else {
					foreach( $attrs as $k => $v ) {
						$properties_definition_data[$property_name][$k] = $v;
					}
				}
			}
		}

		return $properties_definition_data;
	}

	public static function getClassDefinition( ReflectionClass $class, string $attribute_name, array $aliases=[] ): array
	{
		$classes = static::getClasses( $class );

		$class_arguments = [];

		foreach( $classes as $class ) {
			foreach( $class->getAttributes( $attribute_name ) as $attribute ) {

				foreach( $attribute->getArguments() as $k => $v ) {
					if(isset($aliases[$k])) {
						$alias_to = $aliases[$k];

						if( !isset( $class_arguments[$alias_to] ) ) {
							$class_arguments[$alias_to] = [];
						}
						$class_arguments[$alias_to][] = $v;

						continue;
					}

					$class_arguments[$k] = $v;
				}
			}

		}

		return $class_arguments;
	}
}