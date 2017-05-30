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
class DataModel_Definition_Relation_Join_Condition extends BaseObject
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
	 * @var string
	 */
	protected $operator;

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( $data )
	{
		$i = new static();
		foreach( $data as $k=>$v ) {
			$i->{$k} = $v;
		}

		return $i;
	}

	/**
	 *
	 *
	 * @param string $related_to_class_name
	 * @param string $related_to_property_name
	 * @param string $operator
	 * @param mixed  $value
	 *
	 */
	public function __construct(
				$related_to_class_name = '',
				$related_to_property_name = '',
				$operator = '',
				$value = ''
	)
	{
		if( !$related_to_class_name ) {
			return;
		}

		$this->related_class_name = $related_to_class_name;
		$this->related_property_name = $related_to_property_name;

		$this->operator = $operator;
		$this->value = $value;


	}


	/**
	 * @return DataModel_Definition_Property
	 */
	public function getRelatedProperty()
	{
		return DataModel_Definition::get( $this->related_class_name )->getProperty( $this->related_property_name );
	}

	/**
	 * @return string
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
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
		return $this->related_class_name.'.'.$this->related_property_name.' '.$this->operator.' '.$this->value;
	}

}