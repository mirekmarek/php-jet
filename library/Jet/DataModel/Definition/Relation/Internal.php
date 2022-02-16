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
class DataModel_Definition_Relation_Internal extends DataModel_Definition_Relation
{


	/**
	 * @param string $this_data_model_class_name
	 * @param string $related_data_model_class_name
	 * @param array $join_by
	 * @param array $required_relations
	 */
	public function __construct( string $this_data_model_class_name, string $related_data_model_class_name, array $join_by, array $required_relations = [] )
	{

		$this->this_data_model_class_name = $this_data_model_class_name;
		$this->related_data_model_class_name = $related_data_model_class_name;

		$this->required_relations = $required_relations;

		$this->setJoinBy( $join_by );
	}


}