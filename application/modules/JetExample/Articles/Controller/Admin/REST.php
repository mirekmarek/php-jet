<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\Mvc_Controller_REST;

/**
 *
 */
class Controller_Admin_REST extends Mvc_Controller_REST
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default'        => Main::ACTION_GET_ARTICLE, 'get_article' => Main::ACTION_GET_ARTICLE,
		'post_article'   => Main::ACTION_ADD_ARTICLE, 'put_article' => Main::ACTION_UPDATE_ARTICLE,
		'delete_article' => Main::ACTION_DELETE_ARTICLE,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 *
	 */
	public function default_Action()
	{

	}

	/**
	 * @param null|string $id
	 */
	public function get_article_Action( $id = null )
	{
		if( $id ) {
			$article = $this->_getArticle( $id );
			$this->responseData( $article );
		} else {
			$this->responseDataModelsList( Article::getList() );
		}
	}

	/**
	 * @param $id
	 *
	 * @return Article
	 */
	protected function _getArticle( $id )
	{
		$article = Article::get( $id );

		if( !$article ) {
			$this->responseUnknownItem( $id );
		}

		return $article;
	}

	/**
	 *
	 */
	public function post_article_Action()
	{
		$article = Article::getNew();

		$form = $article->getCommonForm();

		if( $article->catchForm( $form, $this->getRequestData(), true ) ) {
			$article->save();
			$this->logAllowedAction( 'Article created', $article->getId(), $article->getTitle(), $article );
			$this->responseData( $article );
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	/**
	 * @param string $id
	 */
	public function put_article_Action( $id )
	{
		$article = $this->_getArticle( $id );

		$form = $article->getCommonForm();

		if( $article->catchForm( $form, $this->getRequestData(), true ) ) {
			$article->save();
			$this->logAllowedAction( 'Article updated', $article->getId(), $article->getTitle(), $article );

			$this->responseData( $article );
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	/**
	 * @param string $id
	 */
	public function delete_article_Action( $id )
	{
		$article = $this->_getArticle( $id );

		$article->delete();
		$this->logAllowedAction( 'Article deleted', $article->getId(), $article->getTitle(), $article );

		$this->responseOK();

	}

}