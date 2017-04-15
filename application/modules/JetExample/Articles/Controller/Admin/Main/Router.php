<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_Controller_Router;

class Controller_Admin_Main_Router extends Mvc_Controller_Router {


	/**
	 *
	 */
	public function __construct()
	{

		parent::__construct( Application_Modules::getModuleInstance(Main::MODULE_NAME));

		$base_URI = Mvc::getCurrentPageURI();

		$validator = function( &$parameters ) {
			$article = Article::get( $parameters[0] );
			if(!$article) {
				return false;
			}

			$parameters['article'] = $article;
			return true;

		};

		$this->addAction('add', '/^add$/', 'add_article' )
			->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

		$this->addAction('edit', '/^edit:([\S]+)$/', 'update_article' )
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($article->getIdObject()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('view', '/^view:([\S]+)$/', 'get_article' )
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'view:'.rawurlencode($article->getIdObject()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('delete', '/^delete:([\S]+)$/', 'delete_article' )
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($article->getIdObject()).'/'; } )
			->setParametersValidatorCallback( $validator );
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI() {
		return $this->getActionURI('add');
	}

	/**
	 * @param Article $article
	 * @return bool|string
	 */
	public function getEditURI( Article $article ) {
		return $this->getActionURI('edit', $article);
	}

	/**
	 * @param Article $article
	 * @return bool|string
	 */
	public function getEditOrViewURI( Article $article ) {
		if( !($uri=$this->getEditURI($article)) ) {
			$uri = $this->getViewURI($article);
		}

		return $uri;
	}

	/**
	 * @param Article $article
	 * @return bool|string
	 */
	public function getViewURI( Article $article ) {
		return $this->getActionURI('view', $article);
	}

	/**
	 * @param Article $article
	 * @return bool|string
	 */
	public function getDeleteURI( Article $article ) {
		return $this->getActionURI('delete', $article);
	}

}