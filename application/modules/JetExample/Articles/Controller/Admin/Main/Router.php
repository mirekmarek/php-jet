<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\Mvc_Controller_Router;

use JetExampleApp\Mvc_Page;

/**
 *
 */
class Controller_Admin_Main_Router extends Mvc_Controller_Router {


	/**
	 *
	 * @param Main $module_instance
	 */
	public function __construct( Main $module_instance )
	{

		parent::__construct( $module_instance );

		$base_URI = Mvc_Page::get(Main::ADMIN_MAIN_PAGE)->getURI();


		$validator = function( &$parameters ) {
			$article = Article::get( $parameters[0] );
			if(!$article) {
				return false;
			}

			$parameters['article'] = $article;
			return true;

		};

		$this->addAction('add', '/^add$/', Main::ACTION_ADD_ARTICLE )
			->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

		$this->addAction('edit', '/^edit:([\S]+)$/', Main::ACTION_UPDATE_ARTICLE )
			->setCreateURICallback( function( $id ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($id).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('view', '/^view:([\S]+)$/', Main::ACTION_GET_ARTICLE )
			->setCreateURICallback( function( $id ) use($base_URI) { return $base_URI.'view:'.rawurlencode($id).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('delete', '/^delete:([\S]+)$/', Main::ACTION_DELETE_ARTICLE )
			->setCreateURICallback( function( $id ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($id).'/'; } )
			->setParametersValidatorCallback( $validator );
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI() {
		return $this->getActionURI('add');
	}

	/**
	 * @param string $id
	 * @return bool|string
	 */
	public function getEditURI( $id ) {
		return $this->getActionURI('edit', $id);
	}

	/**
	 * @param string $id
	 * @return bool|string
	 */
	public function getEditOrViewURI( $id ) {
		if( !($uri=$this->getEditURI($id)) ) {
			$uri = $this->getViewURI($id);
		}

		return $uri;
	}

	/**
	 * @param string $id
	 * @return bool|string
	 */
	public function getViewURI( $id ) {
		return $this->getActionURI('view', $id );
	}

	/**
	 * @param string $id
	 * @return bool|string
	 */
	public function getDeleteURI( $id ) {
		return $this->getActionURI('delete', $id );
	}

}