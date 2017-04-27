<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class BaseObject_Trait_Properties
 * @package Jet
 */
trait BaseObject_Trait_Properties {

	/**
	 * @return string
	 */
	public function getObjectClassNamespace() {
		$class_name = get_class($this);

		if( ($pos = strrpos($class_name, '\\')) )  {
			return '\\'.substr($class_name, 0, $pos + 1);
		}

		return '\\';

	}

    /**
     * @param $property_name
     *
     * @return bool
     */
    public function getObjectClassHasProperty($property_name ) {
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