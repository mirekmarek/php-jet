<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected $expression = '';

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected $properties = [];


	/**
	 * @var string
	 */
	protected $value = '';


	/**
	 * @param array  $properties
	 * @param string $expression
	 *
	 */
	public function __construct( $expression, array $properties=[] )
	{
		$this->properties = $properties;
		$this->expression = $expression;
	}

	/**
	 * @param DataModel_Definition_Property[] $properties
	 */
	public function setProperties( array $properties )
	{
		$this->properties = $properties;
	}

	/**
	 * @return DataModel_Definition_Property[]
	 */
	public function getProperties()
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
	public function getExpression()
	{
		return $this->expression;
	}

	/**
	 *
	 * @param callable|null $property_name_to_backend_column_name_callback
	 *
	 * @return string
	 */
	public function toString( callable $property_name_to_backend_column_name_callback = null )
	{
		if( !$property_name_to_backend_column_name_callback ) {
			$property_name_to_backend_column_name_callback = function( DataModel_Definition_Property $property ) {
				return $property->getDataModelDefinition()->getModelName().'::'.$property->getName();
			};
		}

		$expression = $this->expression;

		foreach( $this->properties as $key=>$property ) {

			/**
			 * @var DataModel_Definition_Property $property
			 */
			$column_name = $property_name_to_backend_column_name_callback( $property );

			$expression = str_replace( '%'.$key.'%', $column_name, $expression );

		}

		return $expression;
	}
}