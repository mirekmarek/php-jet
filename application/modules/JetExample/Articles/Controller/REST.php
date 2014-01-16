<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Articles
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = array(
		'get_article' => 'get_article',
		'post_article' => 'add_article',
		'put_article' => 'update_article',
		'delete_article' => 'delete_article'
	);


	/**
	 * @param null|int $ID
	 */
	public function get_article_Action( $ID=null ) {
		if($ID) {
			$article = $this->_getArticle($ID);
			$this->responseData($article);
		} else {
			$this->responseDataModelsList( Article::getListAsData() );
		}
	}

	public function post_article_Action() {
		$article = Article::getNew();

		$form = $article->getCommonForm();

		if($article->catchForm( $form, $this->getRequestData(), true )) {
			$article->setTitle( $article->getTitle() );
			$article->validateProperties();
			$article->save();
			Jet\Mvc::truncateRouterCache();
			$this->responseData($article);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}

	}

	public function put_article_Action( $ID ) {
		$article = $this->_getArticle($ID);

		$form = $article->getCommonForm();

		if($article->catchForm( $form, $this->getRequestData(), true )) {
			$article->setTitle( $article->getTitle() );
			$article->validateProperties();
			$article->save();
			Jet\Mvc::truncateRouterCache();
			$this->responseData($article);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function delete_article_Action( $ID ) {
		$article = $this->_getArticle($ID);

		$article->delete();
		Jet\Mvc::truncateRouterCache();

		$this->responseOK();

	}

	/**
	 * @param $ID
	 * @return Article
	 */
	protected  function _getArticle($ID) {
		$article = Article::get($ID);

		if(!$article) {
			$this->responseUnknownItem($ID);
		}

		return $article;
	}

}