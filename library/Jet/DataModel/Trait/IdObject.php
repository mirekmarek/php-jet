<?php
/**
 *
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
 */
namespace Jet;

trait DataModel_Trait_IdObject {

	/**
	 * @var DataModel_ID_Abstract
	 */
	private $_ID_object;

    /**
     * Returns ID
     *
     * @return DataModel_ID_Abstract
     */
    public function getIdObject() {
        /**
         * @var DataModel $this
         */

        if(!$this->_ID_object) {
	        $this->_ID_object = $this->getEmptyIdObject();

	        $this->_ID_object->joinDataModel($this);
	        foreach($this->_ID_object as $property_name => $value) {
		        $this->_ID_object->joinObjectProperty( $property_name, $this->{$property_name});
	        }

        }


        return $this->_ID_object;
    }


    /**
     * @return DataModel_ID_Abstract
     */
    public static function getEmptyIdObject() {
        /** @noinspection PhpUndefinedMethodInspection */
        return static::getDataModelDefinition()->getEmptyIDInstance();
    }

}