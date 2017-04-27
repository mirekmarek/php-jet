<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Related_1to1
 * @package Jet
 */
abstract class DataModel_Related_1to1 extends BaseObject implements DataModel_Related_1to1_Interface {
    use DataModel_Related_1to1_Trait;

    /**
     *
     */
    public function afterLoad() {

    }

	/**
	 *
	 */
	public function beforeSave() {

	}

    /**
     *
     */
    public function afterAdd() {

    }

    /**
     *
     */
    public function afterUpdate() {

    }

    /**
     *
     */
    public function afterDelete() {

    }


}