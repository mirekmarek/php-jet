<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_IdObject
 * @package Jet
 */
trait DataModel_Trait_IdObject {

	/**
	 * @var DataModel_Id_Abstract
	 */
	private $_id_object;

    /**
     * Returns ID
     *
     * @return DataModel_Id_Abstract
     */
    public function getIdObject() {
        /**
         * @var DataModel $this
         */

        if(!$this->_id_object) {
	        $this->_id_object = static::getEmptyIdObject();

	        $this->_id_object->joinDataModel($this);
	        foreach($this->_id_object as $property_name => $value) {
		        $this->_id_object->joinObjectProperty( $property_name, $this->{$property_name});
	        }

        }


        return $this->_id_object;
    }


    /**
     * @return DataModel_Id_Abstract
     */
    public static function getEmptyIdObject() {
        /** @noinspection PhpUndefinedMethodInspection */
        return static::getDataModelDefinition()->getEmptyIdInstance();
    }

}