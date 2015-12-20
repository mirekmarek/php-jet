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
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;
use Jet\Mvc_Controller_JetJS;

class Controller_JetJS extends Mvc_Controller_JetJS {
    /**
     *
     * @var Main
     */
    protected $module_instance;

    /**
     * @var array
     */
    protected static $ACL_actions_check_map = [
        'default' => false
    ];

    public function default_Action() {
    }

    /**
     *
     */
    public function initialize() {
    }
}