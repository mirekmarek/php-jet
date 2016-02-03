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

trait DataModel_Trait_Helpers {
    /**
     * @param string $class
     *
     * @return string
     */
    public static function helper_getCreateCommand( $class ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!
        $class = Object_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        return $_this->getBackendInstance()->helper_getCreateCommand( $_this );
    }

    /**
     *
     * @param string $class
     * @param bool $including_history_backend (optional, default: true)
     * @param bool $including_cache_backend (optional, default: true)
     * @return bool
     */
    public static function helper_create( $class, $including_history_backend=true, $including_cache_backend=true ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!

        $class = Object_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        if( $including_history_backend ) {
            $h_backend = $_this->getHistoryBackendInstance();

            if($h_backend) {
                $h_backend->helper_create();
            }
        }

        if($including_cache_backend) {
            $c_backend = $_this->getCacheBackendInstance();

            if($c_backend) {
                $c_backend->helper_create();
            }

        }

        return $_this->getBackendInstance()->helper_create( $_this );
    }


    /**
     * Update (actualize) DB table or tables
     *
     * @param string $class
     *
     * @return string
     */
    public static function helper_getUpdateCommand( $class ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!
        $class = Object_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        return $_this->getBackendInstance()->helper_getUpdateCommand( $_this );
    }

    /**
     * Update (actualize) DB table or tables
     *
     * @param bool $including_history_backend (optional, default: true)
     * @param bool $including_cache_backend (optional, default: true)
     *
     * @param string $class
     */
    public static function helper_update( $class, $including_history_backend=true, $including_cache_backend=true  ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!
        $class = Object_Reflection::parseClassName($class);

        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        if( $including_history_backend ) {
            $h_backend = $_this->getHistoryBackendInstance();

            if($h_backend) {
                $h_backend->helper_create();
            }
        }

        if($including_cache_backend) {
            $c_backend = $_this->getCacheBackendInstance();

            if($c_backend) {
                $c_backend->helper_create();
            }

        }

        $_this->getBackendInstance()->helper_update( $_this );

        $cache = $_this->getCacheBackendInstance();
        if($cache) {
            $cache->truncate( $_this->getDataModelDefinition()->getModelName() );
        }
    }

    /**
     * Drop (only rename by default) DB table or tables
     *
     * @param string $class
     */
    public static function helper_drop( $class ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        $_this->getBackendInstance()->helper_drop( $_this );

        $cache = $_this->getCacheBackendInstance();
        if($cache) {
            $cache->truncate( $_this->getDataModelDefinition()->getModelName() );
        }

    }

}