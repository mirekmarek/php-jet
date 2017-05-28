<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Definition_Relation_JoinByItem extends BaseObject
{

	/**
	 * @var string
	 */
	protected $related_class_name = '';

	/**
	 * @var string
	 */
	protected $related_property_name = '';

	/**
	 * @var mixed|DataModel_Definition_Property
	 */
	protected $this_property_or_value;

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( array $data )
	{
		$i = new static();

		foreach( $data as $key => $val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

	/**
	 *
	 *
	 * @param DataModel_Definition_Model           $this_model_definition
	 * @param string|DataModel_Definition_Property $this_model_property_definition
	 * @param string                               $related_to_class_name
	 * @param string                               $related_to_property_name
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Definition_Model $this_model_definition = null, $this_model_property_definition = '', $related_to_class_name = '', $related_to_property_name = '' )
	{
		if( !$this_model_definition ) {
			return;
		}

		$this->related_class_name = $related_to_class_name;
		$this->related_property_name = $related_to_property_name;

		$this->this_property_or_value = $this_model_property_definition;


		if( !( $this->this_property_or_value instanceof DataModel_Definition_Property ) ) {
			$properties = $this_model_definition->getProperties();

			if( strpos( $this->this_property_or_value, '.' )===false ) {
				$item = $this->this_property_or_value;
				$prefix = 'this';
			} else {
				list( $prefix, $item ) = explode( '.', $this->this_property_or_value );
			}

			switch( $prefix ) {
				case 'this':
					if( !isset( $properties[$item] ) ) {
						throw new DataModel_Query_Exception(
							'Unknown property: '.$this_model_definition->getClassName().'::'.$item,
							DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
						);
					}
					$this->this_property_or_value = $properties[$item];
					break;
				default:
					throw new DataModel_Query_Exception(
						'Invalid property name: \''.$this->this_property_or_value.'\'. Valid examples: property_name or this.property_name',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
					break;
			}
		}
	}

	/**
	 *
	 *
	 * @return mixed|DataModel_Definition_Property
	 */
	public function getThisPropertyOrValue()
	{
		return $this->this_property_or_value;
	}

	/**
	 * @return DataModel_Definition_Property
	 */
	public function getRelatedProperty()
	{
		return DataModel_Definition::get( $this->related_class_name )->getProperty(
			$this->related_property_name
		);
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->this_property_or_value.'<->'.$this->related_class_name.'.'.$this->related_property_name;
	}

}