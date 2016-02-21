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
     * Returns ID
     *
     * @return DataModel_ID_Abstract
     */
    public function getIdObject() {
        /**
         * @var DataModel $this
         */

        $ID = &DataModel_ObjectState::getVar($this, 'ID');

        if(!$ID) {
            $ID = $this->getEmptyIdObject();
        }

        foreach($ID as $property_name => $value) {
            $ID[$property_name] = $this->{$property_name};
        }

        return $ID;
    }


    /**
     * @return DataModel_ID_Abstract
     */
    public static function getEmptyIdObject() {
        /** @noinspection PhpUndefinedMethodInspection */
        return static::getDataModelDefinition()->getEmptyIDInstance();
    }

    /**
     * @param string $ID
     *
     * @return DataModel_ID_Abstract
     */
    public static function createIdObject(
        /** @noinspection PhpUnusedParameterInspection */
        $ID
    ) {
        $arguments = func_get_args();

        return call_user_func_array( [static::getEmptyIdObject(),'createID'], $arguments );
    }


    /**
     * @return DataModel_ID_Abstract
     */
    public function resetIdObject() {
        $ID = $this->getIdObject();

        $ID->reset();

        foreach( $ID as $property_name=>$value ) {
            $this->{$property_name} = $value;
        }

        return $ID;

    }



    /**
     * Generate unique ID
     *
     * @param bool $called_after_save (optional, default = false)
     * @param mixed $backend_save_result  (optional, default = null)
     *
     * @throws DataModel_Exception
     */
    public function generateIdObject(  $called_after_save = false, $backend_save_result = null  ) {
        /**
         * @var DataModel $this
         */

        $ID = $this->getIdObject();

        $ID->generate( $this, $called_after_save, $backend_save_result );

        foreach( $ID as $property_name=>$value ) {
            $this->{$property_name} = $value;
        }

    }


}