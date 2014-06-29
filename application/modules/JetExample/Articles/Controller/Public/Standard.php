<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Articles
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;

class Controller_Public_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		'default' => false,
		'list' => false,
		'detail' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}


	public function default_Action() {
	}

	public function list_Action( Jet\Data_Paginator $paginator, $articles_list ) {

		$this->view->setVar('articles_list', $articles_list);
		$this->view->setVar('paginator', $paginator);

		$this->render('list');

	}

	public function detail_Action( Article $article ) {
		Jet\Mvc::getCurrentFrontController()->addBreadcrumbNavigationData($article->getTitle());

		$this->view->setVar('article', $article);

		$this->render('detail');
	}
}