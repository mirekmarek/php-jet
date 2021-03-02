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
interface DataModel_Related_1toN_Interface extends DataModel_Related_Interface
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_1toN
	 */
	public static function dataModelDefinitionFactory( string $data_model_class_name ): DataModel_Definition_Model_Related_1toN;

	/**
	 * @return null|string|int
	 */
	public function getArrayKeyValue(): null|string|int;
}