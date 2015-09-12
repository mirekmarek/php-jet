<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAdminUI
 * @subpackage JetApplicationModule_DefaultAdminUI_Controller
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;
use Jet\Mvc_Controller_AJAX;

class Controller_AJAX extends Mvc_Controller_AJAX {
    /**
     *
     * @var Main
     */
    protected $module_instance;

    /**
     * @var array
     */
    protected static $ACL_actions_check_map = array(
        'default' => false
    );

    /**
     *
     */
    public function initialize() {
    }

    /**
     *
     */
    public function default_Action() {
    }
}