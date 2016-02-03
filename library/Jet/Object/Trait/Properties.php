<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 */

namespace Jet;

trait Object_Trait_Properties {

    /**
     * @var string
     */
    protected $__object_identification_key;

    /**
     * @return string
     */
    public function getObjectIdentificationKey() {

        if(!$this->__object_identification_key) {
            $this->__object_identification_key = get_class($this).':'.spl_object_hash($this).':'.microtime(true);
        }

        return $this->__object_identification_key;
    }


    /**
     * @param $property_name
     *
     * @return bool
     */
    public function getHasProperty( $property_name ) {
        if(
            $property_name[0]=='_' ||
            !property_exists($this, $property_name)
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param $property_name
     *
     * @return string
     */
    public function getSetterMethodName( $property_name ) {
        $setter_method_name = 'set'.str_replace('_', '', $property_name);

        return $setter_method_name;
    }

    /**
     * @param $property_name
     *
     * @return string
     */
    public function getGetterMethodName( $property_name ) {
        $setter_method_name = 'get'.str_replace('_', '', $property_name);

        return $setter_method_name;
    }

}