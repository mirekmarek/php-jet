<?php
/**
 *
 *
 *
 * DefaultAdminUI admin mode REST controller
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;
use Jet\Mvc_Controller_REST;

class Controller_REST extends Mvc_Controller_REST {
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