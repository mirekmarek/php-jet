<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Visitors\Users;

use Jet\Mvc_Controller;
use Jet\Mvc_Controller_Router;
use Jet\Mvc_Controller_Router_Action;
use Jet\Mvc_Page;
use JetApplication\Auth_Visitor_User as User;

/**
 *
 */
class Controller_Main_Router extends Mvc_Controller_Router
{

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $add_action;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $edit_action;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $view_action;

	/**
	 * @var Mvc_Controller_Router_Action
	 */
	protected $delete_action;



	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{

		parent::__construct( $controller );

		$validator = function( $parameters, Mvc_Controller_Router_Action $action ) {

			if( ($user = User::get( $parameters[0] )) ) {
				$action->controller()->getContent()->setParameter('user', $user);
				return true;
			}

			return false;
		};



		$this->add_action = $this->addAction( 'add', '/^add$/' );
		$this->add_action->setURICreator(
			function() {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['add' ]);
			}
		);



		$this->edit_action = $this->addAction( 'edit', '/^edit:([a-z\-0-9\_]+)$/' );
		$this->edit_action->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['edit:'.$id ]);
			}
		);
		$this->edit_action->setValidator( $validator );




		$this->view_action = $this->addAction( 'view', '/^view:([a-z\-0-9\_]+)$/' );
		$this->view_action->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['view:'.$id ]);
			}
		);
		$this->view_action->setValidator( $validator );



		$this->delete_action = $this->addAction( 'delete', '/^delete:([a-z\-0-9\_]+)$/' );
		$this->delete_action->setURICreator(
			function( $id ) {
				return Mvc_Page::get( Main::ADMIN_MAIN_PAGE )->getURI(['delete:'.$id ]);
			}
		);
		$this->delete_action->setValidator( $validator );


	}


	/**
	 * @return bool|string
	 */
	public function getAddURI()
	{
		return $this->add_action->URI();
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getEditURI( $id )
	{
		return $this->edit_action->URI( $id );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getViewURI( $id )
	{
		return $this->view_action->URI( $id );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getDeleteURI( $id )
	{
		return $this->delete_action->URI( $id );
	}


	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getEditOrViewURI( $id )
	{
		if( !( $uri = $this->getEditURI( $id ) ) ) {
			$uri = $this->getViewURI( $id );
		}

		return $uri;
	}

}