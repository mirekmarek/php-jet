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
 */
namespace JetApplicationModule\JetExample\AdminRoles;
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
		'default' => 'get_role',
		'add' => 'add_role',
		'edit' => 'update_role',
		'view' => 'get_role',
		'delete' => 'delete_role',
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
     *
     * @return Jet\Mvc_MicroRouter
     */
    public function getMicroRouter() {
        if($this->micro_router) {
            return $this->micro_router;
        }

        $router = Jet\Mvc::getCurrentRouter();

        $router = new Jet\Mvc_MicroRouter( $router, $this->module_instance );


        $validator = function( &$parameters ) {

            $role_i = Jet\Auth_Factory::getRoleInstance();

            $role = $role_i->load( $role_i->createID($parameters[0]) );
            if(!$role) {
                return false;
            }

            $parameters[0] = $role;
            return true;

        };

        $base_URI = Jet\Mvc::getCurrentURI();

        $router->addAction('add', '/^add$/', 'add_role', true)
            ->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_role', true)
            ->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_role', true)
            ->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'view:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_role', true)
            ->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($role->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $this->micro_router = $router;

        return $router;
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
	 */
	public function default_Action() {
		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample.UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_roles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('ID', Jet\Tr::_('ID'));
		$grid->addColumn('name', Jet\Tr::_('Name'));
		$grid->addColumn('description', Jet\Tr::_('Description'));

		$grid->setData( Jet\Auth::getRolesList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');
	}


	/**
	 *
	 */
	public function add_Action() {

		$role = Jet\Auth::getNewRole();

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

		Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData( Jet\Tr::_('New role') );


		$this->view->setVar('btn_label', Jet\Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}

	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function edit_Action( Jet\Auth_Role_Abstract $role ) {

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData( $role->getName() );

		$this->view->setVar('btn_label', Jet\Tr::_('SAVE') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}

	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function view_Action( Jet\Auth_Role_Abstract $role ) {

        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData( $role->getName() );

		$form = $role->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}


	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function delete_action( Jet\Auth_Role_Abstract $role ) {

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$role->delete();

			Jet\Http_Headers::movedTemporary( Jet\Mvc::getCurrentURI() );
		}


        Jet\Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete role');

		$this->view->setVar( 'role', $role );

		$this->render('classic/delete-confirm');
	}


}