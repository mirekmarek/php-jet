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
        $class = BaseObject_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        return $_this->getBackendInstance()->helper_getCreateCommand( $_this );
    }

    /**
     *
     * @param string $class
     *
     * @return bool
     */
    public static function helper_create( $class ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!

        $class = BaseObject_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

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
        $class = BaseObject_Reflection::parseClassName($class);
        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        return $_this->getBackendInstance()->helper_getUpdateCommand( $_this );
    }

    /**
     * Update (actualize) DB table or tables
     *
     * @param string $class
     */
    public static function helper_update( $class ) {
        //DO NOT CHANGE CLASS NAME BY FACTORY HERE!
        $class = BaseObject_Reflection::parseClassName($class);

        /**
         * @var DataModel $_this
         */
        $_this = new $class();

        $_this->getBackendInstance()->helper_update( $_this );

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
    }

}