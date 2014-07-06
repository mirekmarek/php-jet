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
use Jet\Mvc_MicroRouter;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		'get_article' => 'Get article(s) data',
		'add_article' => 'Add new article',
		'update_article' => 'Update article',
		'delete_article' => 'Delete article',
	);

	/**
	 * @var int
	 */
	protected $public_list_items_per_page = 10;

	/**
	 * @var Jet\Mvc_MicroRouter
	 */
	protected $_micro_router;


	/**
	 * Returns module views directory
	 *
	 * @return string
	 */
	public function getViewsDir() {
		$dir = parent::getViewsDir();

		if(Jet\Mvc::getIsAdminUIRequest()) {
			return $dir.'admin/';
		} else {
			return $dir.'public/';
		}
	}

	/**
	 * @param Jet\Mvc_Dispatcher_Abstract $dispatcher
	 * @param string $service_type
	 *
	 * @return string
	 */
	protected function getControllerClassName( Jet\Mvc_Dispatcher_Abstract $dispatcher, $service_type ) {

		if($service_type!=Jet\Mvc_Router::SERVICE_TYPE_REST) {
			if( $dispatcher->getRouter()->getIsAdminUI() ) {
				$controller_suffix = 'Controller_Admin_'.$service_type;

			} else {
				$controller_suffix = 'Controller_Public_'.$service_type;
			}
		} else {
			$controller_suffix = 'Controller_'.$service_type;
		}

		$controller_class_name = JET_APPLICATION_MODULE_NAMESPACE.'\\'.$this->module_manifest->getName().'\\'.$controller_suffix;

		return $controller_class_name;
	}

	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 *
	 * @return Jet\Mvc_MicroRouter
	 */
	public function getMicroRouter( Jet\Mvc_Router_Abstract $router=null ) {
		if($this->_micro_router) {
			return $this->_micro_router;
		}

		if(!$router) {
			$router = Jet\Mvc_Router::getCurrentRouterInstance();
		}

		$router = new Jet\Mvc_MicroRouter( $router, $this );

		$base_URI = Jet\Mvc::getCurrentURI();

		$validator = function( &$parameters ) {
			$article = Article::get( $parameters[0] );
			if(!$article) {
				return false;
			}

			$parameters[0] = $article;
			return true;

		};

		$router->addAction('add', '/^add$/', 'add_article', true)
			->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

		$router->addAction('edit', '/^edit:([\S]+)$/', 'update_article', true)
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($article->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$router->addAction('view', '/^view:([\S]+)$/', 'get_article', true)
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'view:'.rawurlencode($article->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$router->addAction('delete', '/^delete:([\S]+)$/', 'delete_article', true)
			->setCreateURICallback( function( Article $article ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($article->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->_micro_router = $router;

		return $router;
	}


	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 * @param Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest( Jet\Mvc_Router_Abstract $router, Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item=null ) {
		if(Jet\Mvc::getIsAdminUIRequest()) {
			$this->resolveRequest_Admin( $router, $dispatch_queue_item );
		} else {
			$this->resolveRequest_Public( $router, $dispatch_queue_item );
		}
	}

	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 * @param Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest_Admin( Jet\Mvc_Router_Abstract $router, Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item=null ) {

		$router = $this->getMicroRouter( $router );

		$router->resolve( $dispatch_queue_item );
	}

	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 * @param Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest_Public( Jet\Mvc_Router_Abstract $router, Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item=null ) {

		$article = new Article();
		$current_article = $article->resolveArticleByURL( $router );

		if($current_article) {

			$dispatch_queue_item->setControllerAction('detail');
			$dispatch_queue_item->setControllerActionParameters( [$current_article] );
		} else {

			$paginator = new Jet\Data_Paginator(
				$router->parsePathFragmentIntValue( 'page:%VAL%', 1 ),
				$this->public_list_items_per_page,
				$router->getPage()->getURI().'page:'.Jet\Data_Paginator::URL_PAGE_NO_KEY.'/'
			);

			$paginator->setDataSource( $article->getListForCurrentLocale() );

			$articles_list = $paginator->getData();

			$dispatch_queue_item->setControllerAction('list');
			$dispatch_queue_item->setControllerActionParameters( [$paginator, $articles_list] );

		}
	}
}