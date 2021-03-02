<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


use \ReflectionClass;

class Attributes
{

	public static function getPropertiesDefinition( ReflectionClass $reflection, string $attribute_name ): array
	{

		$classes = [$reflection->getName() => $reflection];

		if( ($parent = $reflection->getParentClass()) ) {
			do {
				$classes[$parent->getName()] = $parent;
			} while( ($parent = $parent->getParentClass()) );
		}

		$classes = array_reverse( $classes );

		$properties_definition_data = [];

		foreach( $classes as $class ) {
			/**
			 * @var ReflectionClass $class
			 */
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

				$_name = $property->getName();

				if( !isset( $properties_definition_data[$_name] ) ) {
					$properties_definition_data[$_name] = $attrs;
				} else {
					foreach( $attrs as $k => $v ) {
						$properties_definition_data[$_name][$k] = $v;
					}
				}
			}
		}

		return $properties_definition_data;
	}

	public static function getClassArguments( ReflectionClass $reflection, string $attribute_name ): array
	{

		$classes = [$reflection->getName() => $reflection];

		if( ($parent = $reflection->getParentClass()) ) {
			do {
				$classes[$parent->getName()] = $parent;
			} while( ($parent = $parent->getParentClass()) );
		}

		$classes = array_reverse( $classes );

		$class_arguments = [];

		foreach( $classes as $class ) {
			/**
			 * @var ReflectionClass $class
			 */
			foreach( $class->getAttributes( $attribute_name ) as $attribute ) {

				foreach( $attribute->getArguments() as $k => $v ) {
					if( $k == 'relation' ) {
						if( !isset( $class_arguments['relations'] ) ) {
							$class_arguments['relations'] = [];
						}
						$class_arguments['relations'][] = $v;
						continue;
					}

					if( $k == 'key' ) {
						if( !isset( $class_arguments['keys'] ) ) {
							$class_arguments['keys'] = [];
						}
						$class_arguments['keys'][] = $v;
						continue;
					}

					$class_arguments[$k] = $v;
				}
			}

		}

		return $class_arguments;
	}
}