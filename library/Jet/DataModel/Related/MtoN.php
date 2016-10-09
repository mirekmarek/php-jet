<?php
/**
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
 * @subpackage DataModel_Related
 */
namespace Jet;


/**
 * Available annotation:
 *
 * @JetDataModel:default_order_by = ['property_name','-next_property_name', '+some_property_name']
 */

/**
 *
 * @JetDataModel:ID_class_name = 'DataModel_ID_Passive'
 */
abstract class DataModel_Related_MtoN extends BaseObject implements DataModel_Related_MtoN_Interface {
    use DataModel_Related_MtoN_Trait;

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