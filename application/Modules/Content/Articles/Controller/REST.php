<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\Mvc_Controller_REST;
use Jet\REST;

/**
 *
 */
class Controller_REST extends Mvc_Controller_REST
{
	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'get'    => Main::ACTION_GET_ARTICLE,
		'list'   => Main::ACTION_GET_ARTICLE,
		'add'    => Main::ACTION_ADD_ARTICLE,
		'update' => Main::ACTION_UPDATE_ARTICLE,
		'delete' => Main::ACTION_DELETE_ARTICLE,
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{
		$article = null;
		if( ($id = $path) ) {
			$article = Article::get( $id );
			if(!$article) {
				$this->responseUnknownItem($id);
				return false;
			}
		}


		switch( $this->getRequestMethod() ) {
			case REST::REQUEST_METHOD_GET:
				$controller_action = $article ? 'get' : 'list';
				break;
			case REST::REQUEST_METHOD_POST:
				if($article) {
					return false;
				}
				$controller_action = 'add';
				break;
			case REST::REQUEST_METHOD_PUT:
				if(!$article) {
					return false;
				}
				$controller_action = 'update';
				break;
			case REST::REQUEST_METHOD_DELETE:
				if(!$article) {
					return false;
				}
				$controller_action = 'delete';
				break;
			default:
				return false;
		}


		$this->getContent()->setControllerAction( $controller_action );
		$this->getContent()->setParameter( 'article', $article );


		return true;

	}


	/**
	 *
	 */
	public function get_Action( )
	{
		/**
		 * @var Article $article
		 */
		$article = $this->getParameter('article');
		$this->responseData( $article );

	}

	/**
	 *
	 */
	public function list_Action( )
	{

		$this->responseData(
			$this->handleDataPagination(
				$this->handleOrderBy(
					Article::getList(),
					[
						'title' => 'article_localized.title',
					    'date_time' => 'article.date_time'
					]
				)
			)
		);
	}

	/**
	 *
	 */
	public function add_Action()
	{
		$article = new Article();

		$form = $article->getEditForm();

		$data = $this->getRequestData();

		$form->catchInput($data, true);

		if($form->validate()) {
			$form->catchData();

			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );
			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}

	}

	/**
	 *
	 */
	public function update_Action()
	{
		/**
		 * @var Article $article
		 */
		$article = $this->getParameter('article');

		$form = $article->getEditForm();

		$form->catchInput($this->getRequestData(), true);

		if($form->validate()) {

			$form->catchData();

			$article->save();

			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );
			$this->responseData( $article );
		} else {
			$this->responseValidationError( $form->getAllErrors() );
		}

	}

	/**
	 *
	 */
	public function delete_Action()
	{
		/**
		 * @var Article $article
		 */
		$article = $this->getParameter('article');

		$article->delete();
		$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

		$this->responseOK();

	}

}