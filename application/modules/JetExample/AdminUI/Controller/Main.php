<?php
/**
 *
 * Default admin UI module
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet\Mvc_Controller_Standard;
use Jet\Auth;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Http_Headers;

class Controller_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
        'logout' => false,
		'default' => false,
		'signpost' => false,
		'ria_default' => false,
		'classic_default' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}

    /**
     *
     */
    public function logout_Action() {
        Auth::logout();

        Http_Headers::movedTemporary( Mvc_Page::get('admin')->getURL() );
    }

    /**
     *
     */
    public function signpost_Action() {
		$this->render('signpost');
	}

    /**
     *
     */
    public function classic_default_Action() {
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -1 );

		$this->render('classic/default');

	}

}