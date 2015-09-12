<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 * @see Jet\Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_AdminUsers
 * @subpackage JetApplicationModule_AdminUsers_Controller
 */
namespace JetApplicationModule\JetExample\AdminUsers;
use Jet;
use JetApplicationModule\JetExample\UIElements;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

    /**
     * @var Jet\Mvc_MicroRouter
     */
    protected $micro_router;


	protected static $ACL_actions_check_map = array(
		'default' => 'get_user',
		'add' => 'add_user',
		'edit' => 'update_user',
		'view' => 'get_user',
		'delete' => 'delete_user',
	);


	/**
	 *
	 */
	public function initialize() {
		Jet\Mvc::checkCurrentContentIsDynamic();
        Jet\Mvc::getCurrentPage()->breadcrumbNavigationShift( -2 );
		$this->getMicroRouter();
		$this->view->setVar( 'router', $this->micro_router );
	}

    /**
     * @param Jet\Mvc_Page_Content_Abstract $page_content
     *
     * @return bool
     */
    public function parseRequestURL( Jet\Mvc_Page_Content_Abstract $page_content=null ) {
        $router = $this->getMicroRouter();

        return $router->resolve( $page_content );
    }

    /**
     *
     * @return Jet\Mvc_MicroRouter
     */
    public function getMicroRouter() {
        if($this->micro_router) {
            return $this->micro_router;
        }

        $router = Jet\Mvc::getCurrentRouter();

        $router = new Jet\Mvc_MicroRouter( $router, $this->module_instance );

        $base_URI = Jet\Mvc::getCurrentURI();

        $validator = function( &$parameters ) {

            $user = Jet\Auth_ControllerModule_Abstract::getUser( $parameters[0] );
            if(!$user) {
                return false;
            }

            $parameters[0] = $user;
            return true;

        };

        $router->addAction('add', '/^add$/', 'add_user', true)
            ->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_user', true)
            ->setCreateURICallback( function( Jet\Auth_User_Abstract $user ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_user', true)
            ->setCreateURICallback( function( Jet\Auth_User_Abstract $user ) use($base_URI) { return $base_URI.'view:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_user', true)
            ->setCreateURICallback( function( Jet\Auth_User_Abstract $user ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $this->micro_router = $router;

        return $router;
    }


	/**
	 *
	 */
	public function default_Action() {

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample\UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_users_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('login', Jet\Tr::_('Login') );
		$grid->addColumn('ID', Jet\Tr::_('ID') );

		$grid->setData( Jet\Auth::getUsersList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');

	}

	/**
	 *
	 */
	public function add_Action() {

		$user = Jet\Auth_Factory::getUserInstance();


		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->validateProperties();
			$user->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $user ) );
		}

		$this->view->setVar('bnt_label', 'ADD');

        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData('New user');

		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}

	/**
	 * @param Jet\Auth_User_Abstract $user
	 */
	public function edit_Action( Jet\Auth_User_Abstract $user ) {

		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->validateProperties();
			$user->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $user ) );
		}


		$this->view->setVar('bnt_label', 'SAVE' );

        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData( $user->getLogin() );


		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}

	/**
	 * @param Jet\Auth_User_Abstract $user
	 */
	public function view_Action( Jet\Auth_User_Abstract $user ) {

		$form = $user->getCommonForm();

		$this->view->setVar('bnt_label', 'SAVE' );

        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData( $user->getLogin() );


		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}



	/**
	 * @param Jet\Auth_User_Abstract $user
	 */
	public function delete_Action( Jet\Auth_User_Abstract $user ) {

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$user->delete();
			Jet\Http_Headers::movedTemporary( Jet\Mvc::getCurrentURI() );
		}


        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete user');

		$this->view->setVar( 'user', $user );

		$this->render('classic/delete-confirm');
	}


}