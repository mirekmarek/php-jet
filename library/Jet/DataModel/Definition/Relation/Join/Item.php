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
class DataModel_Definition_Relation_Join_Item extends BaseObject
{

	/**
	 * @var string
	 */
	protected $this_class_name = '';

	/**
	 * @var string
	 */
	protected $this_property_name = '';

	/**
	 * @var string
	 */
	protected $related_class_name = '';

	/**
	 * @var string
	 */
	protected $related_property_name = '';

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
	 * @param string $this_model_class_name
	 * @param string $this_property_name
	 * @param string $related_to_class_name
	 * @param string $related_to_property_name
	 *
	 */
	public function __construct(
				$this_model_class_name = '',
				$this_property_name = '',
				$related_to_class_name = '',
				$related_to_property_name = ''
	)
	{
		if( !$this_model_class_name ) {
			return;
		}

		$this->related_class_name = $related_to_class_name;
		$this->related_property_name = $related_to_property_name;

		$this->this_class_name = $this_model_class_name;
		$this->this_property_name = $this_property_name;


	}

	/**
	 * @return string
	 */
	public function getThisClassName()
	{
		return $this->this_class_name;
	}

	/**
	 * @return string
	 */
	public function getThisPropertyName()
	{
		return $this->this_property_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedClassName()
	{
		return $this->related_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedPropertyName()
	{
		return $this->related_property_name;
	}



	/**
	 *
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getThisProperty()
	{
		return DataModel_Definition::get( $this->this_class_name )->getProperty( $this->this_property_name );
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
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->this_class_name.'.'.$this->this_property_name.'<->'.$this->related_class_name.'.'.$this->related_property_name;
	}

}