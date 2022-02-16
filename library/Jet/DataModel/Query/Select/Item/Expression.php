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
class DataModel_Query_Select_Item_Expression extends BaseObject
{
	/**
	 * Example:
	 *
	 * count(%property%)
	 *
	 * @var string
	 */
	protected string $expression = '';

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected array $properties = [];


	/**
	 * @var string
	 */
	protected string $value = '';


	/**
	 * @param string $expression
	 * @param array $properties
	 *
	 */
	public function __construct( string $expression, array $properties = [] )
	{
		$this->properties = $properties;
		$this->expression = $expression;
	}

	/**
	 * @param DataModel_Definition_Property[] $properties
	 */
	public function setProperties( array $properties ): void
	{
		$this->properties = $properties;
	}

	/**
	 * @return DataModel_Definition_Property[]
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 * Example:
	 *
	 * count(%PROPERTY%)
	 *
	 * @return string
	 */
	public function getExpression(): string
	{
		return $this->expression;
	}

	/**
	 *
	 * @param callable|null $property_name_to_backend_column_name_callback
	 *
	 * @return string
	 */
	public function toString( callable $property_name_to_backend_column_name_callback = null ): string
	{
		if( !$property_name_to_backend_column_name_callback ) {
			$property_name_to_backend_column_name_callback = function( DataModel_Definition_Property $property ) {
				return $property->getDataModelDefinition()->getModelName() . '::' . $property->getName();
			};
		}

		$expression = $this->expression;

		foreach( $this->properties as $key => $property ) {

			/**
			 * @var DataModel_Definition_Property $property
			 */
			$column_name = $property_name_to_backend_column_name_callback( $property );

			$expression = str_replace( '%' . $key . '%', $column_name, $expression );

		}

		return $expression;
	}
}