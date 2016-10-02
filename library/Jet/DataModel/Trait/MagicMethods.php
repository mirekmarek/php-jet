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

trait DataModel_Trait_MagicMethods {

    /**
     *
     */
    public function __destruct() {
    }

    /**
     *
     */
    public function __wakeup() {
        /**
         * @var DataModel $this
         */
        $this->setIsSaved();
    }

    /**
     *
     */
    public function __clone() {
        /**
         * @var DataModel $this
         */
        /** @noinspection PhpUndefinedClassInspection */
        parent::__clone();

        $this->resetIdObject();
        $this->setIsNew();
    }

}