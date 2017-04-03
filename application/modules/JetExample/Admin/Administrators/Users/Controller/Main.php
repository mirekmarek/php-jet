<?php
/**
 *
 *
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
namespace JetApplicationModule\JetExample\Admin\Administrators\Users;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Auth_Administrator_User as User;
use JetExampleApp\Mvc_Controller_AdminStandard;

use JetUI\UI;
use JetUI\dataGrid;
use JetUI\breadcrumbNavigation;
use JetUI\messages;


use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_Controller_Router;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Form;

class Controller_Main extends Mvc_Controller_AdminStandard {
	const DEFAULT_ACTION = 'default';

	const ADD_ACTION = 'add';
	const ADD_ACTION_URI = 'add';

	const EDIT_ACTION = 'edit';
	const EDIT_ACTION_URI = 'edit';

	const VIEW_ACTION = 'view';
	const VIEW_ACTION_URI = 'view';

	const DELETE_ACTION = 'delete';
	const DELETE_ACTION_URI = 'delete';

	/**
	 * @var array
	 */
	protected static $action_URI = [
		self::ADD_ACTION => self::ADD_ACTION_URI,
		self::EDIT_ACTION => self::EDIT_ACTION_URI,
		self::VIEW_ACTION => self::VIEW_ACTION_URI,
		self::DELETE_ACTION => self::DELETE_ACTION_URI,
	];

	/**
	 * @var array
	 */
	protected static $action_regexp = [
		self::ADD_ACTION => '/^'.self::ADD_ACTION_URI.'$/',
		self::EDIT_ACTION => '/^'.self::EDIT_ACTION_URI.':([0-9]+)$/',
		self::VIEW_ACTION => '/^'.self::VIEW_ACTION_URI.':([0-9]+)$/',
		self::DELETE_ACTION => '/^'.self::DELETE_ACTION_URI.':([0-9]+)$/',
	];


	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		self::DEFAULT_ACTION => Main::ACTION_GET_USER,
		self::ADD_ACTION => Main::ACTION_ADD_USER,
		self::EDIT_ACTION => Main::ACTION_UPDATE_USER,
		self::VIEW_ACTION => Main::ACTION_GET_USER,
		self::DELETE_ACTION => Main::ACTION_DELETE_USER,
	];

	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var Mvc_Controller_Router
	 */
	protected static $controller_router;


	/**
	 *
	 */
	public function initialize() {
		Mvc::checkCurrentContentIsDynamic();
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public static function getActionURI( $action )
	{
		return self::$action_URI[$action];
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public static function getActionRegexp( $action )
	{
		return self::$action_regexp[$action];
	}

	/**
	 *
	 * @return Mvc_Controller_Router
	 */
	public static function getControllerRouter() {
		if(static::$controller_router) {
			return static::$controller_router;
		}

		$router = Mvc::getCurrentRouter();

		$router = new Mvc_Controller_Router( $router, Application_Modules::getModuleInstance(Main::MODULE_NAME) );


		$validator = function( &$parameters ) {

			$user = User::get($parameters[0]);

			if(!$user) {
				return false;
			}

			$parameters['user'] = $user;
			return true;

		};

		$base_URI = Mvc_Page::get(Main::PAGE_USERS)->getURI();

		$URI_creator = function( $action, $id=0 ) use ($router, $base_URI) {
			if(!$router->getActionAllowed($action)) {
				return false;
			}

			$action_uri = Controller_Main::getActionURI( $action );

			if(!$id) {
				return $action_uri.'/';
			}

			return $base_URI.$action_uri.':'.((int)$id).'/';
		};


		$router->addAction(static::ADD_ACTION, static::getActionRegexp(static::ADD_ACTION), static::getModuleAction( static::ADD_ACTION ), true)
			->setCreateURICallback( function() use($URI_creator) { return $URI_creator(static::ADD_ACTION); } );

		$router->addAction(static::EDIT_ACTION, static::getActionRegexp(static::EDIT_ACTION), static::getModuleAction( static::EDIT_ACTION ), true)
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator(static::EDIT_ACTION, $id); } )
			->setParametersValidatorCallback( $validator );

		$router->addAction(static::VIEW_ACTION, static::getActionRegexp(static::VIEW_ACTION), static::getModuleAction( static::VIEW_ACTION ), true)
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator(static::VIEW_ACTION, $id); } )
			->setParametersValidatorCallback( $validator );

		$router->addAction(static::DELETE_ACTION, static::getActionRegexp(static::DELETE_ACTION), static::getModuleAction( static::DELETE_ACTION ), true)
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator(static::DELETE_ACTION, $id); } )
			->setParametersValidatorCallback( $validator );

		static::$controller_router = $router;

		return $router;
	}


	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation($current_label='' ) {
		$menu_item = $this->getModuleManifest()->getMenuItems()['system/administrator_users'];

		breadcrumbNavigation::addItem(
			UI::icon($menu_item->getIcon()).'&nbsp;&nbsp;'. $menu_item->getLabel(),
			Mvc_Page::get(Main::PAGE_USERS)->getURL()
		);

		if($current_label) {
			breadcrumbNavigation::addItem( $current_label );

		}
	}

	/**
	 *
	 */
	public function default_Action() {
		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm('user');
		$this->view->setVar('search_form', $search_form);

		$grid = new dataGrid();

		$grid->setDefaultSort('login');
		$grid->setIsPersistent('admin_users_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('id', Tr::_('ID') );
		$grid->addColumn('login', Tr::_('Login') );
		$grid->addColumn('first_name', Tr::_('First name') );
		$grid->addColumn('surname', Tr::_('Surname') );

		$grid->setData( User::getList(null, $search_form->getValue()) );

		$this->view->setVar('grid', $grid);

		$this->render('default');

	}

	/**
	 *
	 */
	public function add_Action() {
		$this->_setBreadcrumbNavigation( Tr::_('Create a new User') );

		$user = new User();


		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->save();
			$this->logAllowedAction( $user );
			messages::success( Tr::_('User <b>%LOGIN%</b> has been created', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( $user->getEditURI() );
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}

	/**
	 *
	 */
	public function edit_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$this->_setBreadcrumbNavigation( Tr::_('Edit user account <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		/**
		 * @var Form $form
		 */
		$form = $user->getCommonForm();
		$form->removeField('password');

		if( $user->catchForm( $form ) ) {

			$user->save();
			$this->logAllowedAction( $user );
			messages::success( Tr::_('User <b>%LOGIN%</b> has been updated', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( $user->getEditURI() );
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}

	/**
	 *
	 */
	public function view_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$this->_setBreadcrumbNavigation( Tr::_('User account detail <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		$form = $user->getCommonForm();

		$form->setIsReadonly();

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}



	/**
	 *
	 */
	public function delete_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$this->_setBreadcrumbNavigation( Tr::_('Delete user account <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$user->delete();
			$this->logAllowedAction( $user );
			messages::info( Tr::_('User <b>%LOGIN%</b> has been deleted', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( Mvc::getCurrentPageURI() );
		}


		$this->view->setVar( 'user', $user );

		$this->render('delete-confirm');
	}


}