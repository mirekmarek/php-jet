<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet\Mvc_Controller_REST;

class Controller_REST extends Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'get_article' => 'get_article',
		'post_article' => 'add_article',
		'put_article' => 'update_article',
		'delete_article' => 'delete_article'
	];

	/**
	 *
	 */
	public function initialize() {
	}


	/**
	 * @param null|string $id
	 */
	public function get_article_Action( $id=null ) {
		if($id) {
			$article = $this->_getArticle($id);
			$this->responseData($article);
		} else {
			$this->responseDataModelsList( Article::getList() );
		}
	}

	/**
	 *
	 */
	public function post_article_Action() {
		$article = Article::getNew();

		$form = $article->getCommonForm();

		if($article->catchForm( $form, $this->getRequestData(), true )) {
			$article->save();
			$this->responseData($article);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	/**
	 * @param string $id
	 */
	public function put_article_Action( $id ) {
		$article = $this->_getArticle($id);

		$form = $article->getCommonForm();

		if($article->catchForm( $form, $this->getRequestData(), true )) {
			$article->save();

			$this->responseData($article);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	/**
	 * @param string $id
	 */
	public function delete_article_Action( $id ) {
		$article = $this->_getArticle($id);

		$article->delete();

		$this->responseOK();

	}

	/**
	 * @param $id
	 * @return Article
	 */
	protected  function _getArticle($id) {
		$article = Article::get($id);

		if(!$article) {
			$this->responseUnknownItem($id);
		}

		return $article;
	}

}