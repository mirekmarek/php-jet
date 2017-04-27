<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_MagicMethods
 * @package Jet
 */
trait DataModel_Trait_MagicMethods {

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
	 * @return array
	 */
	public function __debugInfo() {
		/** @noinspection PhpUndefinedClassInspection */
		$r = parent::__debugInfo();

		$r['_data_model_saved'] = $this->getIsSaved();

		if($this->getLoadFilter()) {
			$r['_load_filter'] = $this->getLoadFilter();
		}

		return $r;
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

        $this->setIsNew();
    }

}