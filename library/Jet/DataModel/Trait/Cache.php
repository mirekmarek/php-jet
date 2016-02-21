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

trait DataModel_Trait_Cache {

    /**
     *
     * @return bool
     */
    public static function getCacheEnabled() {
        return static::getDataModelDefinition()->getCacheEnabled();
    }

    /**
     * Returns cache backend instance
     *
     * @return DataModel_Cache_Backend_Abstract
     */
    public static function getCacheBackendInstance() {
        return static::getDataModelDefinition()->getCacheBackendInstance();
    }

    /**
     *
     * @param string $operation
     */
    public function updateDataModelCache( $operation ) {
        /**
         * @var DataModel $this
         */
        $cache = $this->getCacheBackendInstance();
        if($cache) {
            $cache->{$operation}($this->getDataModelDefinition(), $this->getIdObject(), $this);
        }
    }

    /**
     *
     */
    public function deleteDataModelCache() {
        /**
         * @var DataModel $this
         */

        $cache = $this->getCacheBackendInstance();
        if($cache) {
            $cache->delete($this->getDataModelDefinition(), $this->getIdObject() );
        }

    }


    /**
     * @param DataModel_ID_Abstract[] $IDs
     */
    protected function deleteDataModelCacheIDs( $IDs ) {
        /**
         * @var DataModel $this
         */
        $cache = $this->getCacheBackendInstance();
        if(!$cache) {
            return;
        }

        foreach($IDs as $ID) {
            $cache->delete( $this->getDataModelDefinition(), $ID );
        }

    }

}