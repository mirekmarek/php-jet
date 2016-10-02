<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_MtoN_Interface extends DataModel_Related_Interface, DataModel_Interface {

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_MtoN
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name );

    /**
     * @return null
     */
    public function getArrayKeyValue();

	/**
	 * @param DataModel_Interface $M_instance
	 */
	public function setMInstance(DataModel_Interface $M_instance );

    /**
     * @param DataModel_Interface $N_instance
     */
    public function setNInstance(DataModel_Interface $N_instance );

	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_related_properties
	 *
	 * @return DataModel|null
	 */
	public function getNInstance( DataModel_Load_OnlyProperties $load_only_related_properties=null );

    /**
     * @return DataModel_ID_Abstract
     */
    public function getNID();


}