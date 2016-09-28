<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

/**
 * Available annotation:
 *
 * @JetDataModel:default_order_by = ['property_name','-next_property_name', '+some_property_name']
 */

/**
 * Class DataModel_Related_1toN
 */
abstract class DataModel_Related_1toN extends BaseObject implements DataModel_Related_1toN_Interface {

    use DataModel_Related_1toN_Trait;

    /**
     *
     */
    public function afterLoad() {

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