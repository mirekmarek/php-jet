<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Definition_Relation_Internal extends DataModel_Definition_Relation
{

	/**
	 * @var DataModel_Definition_Property[]
	 */
	protected $join_by_properties = [];


	/**
	 * @param DataModel_Definition_Model                 $related_data_model_definition
	 * @param DataModel_Definition_Relation_JoinByItem[] $join_by
	 * @param string                                     $join_type
	 */
	public function __construct( DataModel_Definition_Model $related_data_model_definition, array $join_by, $join_type = DataModel_Query::JOIN_TYPE_LEFT_JOIN )
	{

		$this->related_data_model_class_name = $related_data_model_definition->getClassName();
		$this->related_data_model_definition = $related_data_model_definition;
		$this->join_type = $join_type;

		$this->setJoinBy( $join_by );
	}


}