<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Helper  {

    /**
     * @param string $class
     *
     * @return string
     */
    public static function getCreateCommand( $class ) {
        /**
         * @var DataModel_Interface $class
         */

        return $class::getBackendInstance()->helper_getCreateCommand( $class::getDataModelDefinition() );
    }

    /**
     *
     * @param string $class
     *
     * @return bool
     */
    public static function create( $class ) {
        /**
         * @var DataModel_Interface $class
         */

        return $class::getBackendInstance()->helper_create( $class::getDataModelDefinition() );
    }


    /**
     * Update (actualize) DB table or tables
     *
     * @param string $class
     *
     * @return string
     */
    public static function getUpdateCommand( $class ) {
        /**
         * @var DataModel_Interface $class
         */

        return $class::getBackendInstance()->helper_getUpdateCommand( $class::getDataModelDefinition() );
    }

    /**
     * Update (actualize) DB table or tables
     *
     * @param string $class
     */
    public static function update( $class ) {
        /**
         * @var DataModel_Interface $class
         */

        $class::getBackendInstance()->helper_update( $class::getDataModelDefinition() );

    }

    /**
     * Drop (only rename by default) DB table or tables
     *
     * @param string $class
     */
    public static function drop( $class ) {
        /**
         * @var DataModel_Interface $class
         */

        $class::getBackendInstance()->helper_drop( $class::getDataModelDefinition() );
    }


}
