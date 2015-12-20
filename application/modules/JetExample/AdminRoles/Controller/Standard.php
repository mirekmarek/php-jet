<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminRoles;

use Jet;
use Jet\Application_Modules;
use Jet\Auth;
use Jet\Auth_Factory;
use Jet\Auth_Role_Abstract;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_MicroRouter;
use Jet\Mvc_Page_Content_Abstract;
use Jet\Tr;
use JetApplicationModule\JetExample\UIElements;

class Controller_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var Mvc_MicroRouter
	 */
	protected $micro_router;

	protected static $ACL_actions_check_map = [
		'default' => 'get_role',
		'add' => 'add_role',
		'edit' => 'update_role',
		'view' => 'get_role',
		'delete' => 'delete_role',
	];

	/**
	 *
	 */
	public function initialize() {
		Mvc::checkCurrentContentIsDynamic();
		Mvc::getCurrentPage()->breadcrumbNavigationShift( -2 );
		$this->getMicroRouter();
		$this->view->setVar( 'router', $this->micro_router );
	}


    /**
     *
     * @return Mvc_MicroRouter
     */
    public function getMicroRouter() {
        if($this->micro_router) {
            return $this->micro_router;
        }

        $router = Mvc::getCurrentRouter();

        $router = new Mvc_MicroRouter( $router, $this->module_instance );


        $validator = function( &$parameters ) {

            $role_i = Auth_Factory::getRoleInstance();

            $role = $role_i->load( $role_i->createID($parameters[0]) );
            if(!$role) {
                return false;
            }

            $parameters[0] = $role;
            return true;

        };

        $base_URI = Mvc::getCurrentURI();

        $router->addAction('add', '/^add$/', 'add_role', true)
            ->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_role', true)
            ->setCreateURICallback( function( Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_role', true)
            ->setCreateURICallback( function( Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'view:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_role', true)
            ->setCreateURICallback( function( Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $this->micro_router = $router;

        return $router;
    }


    /**
     * @param Mvc_Page_Content_Abstract $page_content
     *
     * @return bool
     */
    public function parseRequestURL( Mvc_Page_Content_Abstract $page_content=null ) {
        $router = $this->getMicroRouter();

        return $router->resolve( $page_content );
    }



	/**
	 *
	 */
	public function default_Action() {
		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Application_Modules::getModuleInstance('JetExample.UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_roles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('ID', Tr::_('ID'));
		$grid->addColumn('name', Tr::_('Name'));
		$grid->addColumn('description', Tr::_('Description'));

		$grid->setData( Auth::getRolesList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');
	}


	/**
	 *
	 */
	public function add_Action() {

		$role = Auth::getNewRole();

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

		Mvc::getCurrentPage()->addBreadcrumbNavigationData( Tr::_('New role') );


		$this->view->setVar('btn_label', Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('available_privileges_list', Auth::getAvailablePrivilegesList() );

		$this->render('classic/edit');
	}

	/**
	 * @param Auth_Role_Abstract $role
	 */
	public function edit_Action( Auth_Role_Abstract $role ) {

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $role->getName() );

		$this->view->setVar('btn_label', Tr::_('SAVE') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Auth::getAvailablePrivilegesList() );

		$this->render('classic/edit');
	}

	/**
	 * @param Auth_Role_Abstract $role
	 */
	public function view_Action( Auth_Role_Abstract $role ) {

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $role->getName() );

		$form = $role->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Auth::getAvailablePrivilegesList() );

		$this->render('classic/edit');
	}


	/**
	 * @param Auth_Role_Abstract $role
	 */
	public function delete_action( Auth_Role_Abstract $role ) {

		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$role->delete();

			Http_Headers::movedTemporary( Mvc::getCurrentURI() );
		}


        Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete role');

		$this->view->setVar( 'role', $role );

		$this->render('classic/delete-confirm');
	}


}