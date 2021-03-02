<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface DataModel_Related_MtoN_Interface extends DataModel_Related_Interface
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_MtoN
	 */
	public static function dataModelDefinitionFactory( string $data_model_class_name ): DataModel_Definition_Model_Related_MtoN;

	/**
	 * @return null|string|int
	 */
	public function getArrayKeyValue(): null|string|int;


	/**
	 * @param DataModel_Interface $N_instance
	 */
	public function setNInstance( DataModel_Interface $N_instance ): void;

	/**
	 * @param ?DataModel_PropertyFilter $load_filter
	 *
	 * @return DataModel|null
	 */
	public function getNInstance( ?DataModel_PropertyFilter $load_filter = null ): DataModel|null;

	/**
	 * @return DataModel_IDController
	 */
	public function getNId(): DataModel_IDController;


}