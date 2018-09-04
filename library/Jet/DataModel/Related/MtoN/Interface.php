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
interface DataModel_Related_MtoN_Interface extends DataModel_Related_Interface
{

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_MtoN
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name );

	/**
	 * @return null
	 */
	public function getArrayKeyValue();


	/**
	 * @param DataModel_Interface $N_instance
	 */
	public function setNInstance( DataModel_Interface $N_instance );

	/**
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel|null
	 */
	public function getNInstance( DataModel_PropertyFilter $load_filter = null );

	/**
	 * @return DataModel_Id
	 */
	public function getNId();


}