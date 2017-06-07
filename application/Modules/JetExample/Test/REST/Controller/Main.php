<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc;
use Jet\Mvc_Page;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'test_rest' => false,
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
		$this->content->setControllerAction( false );

		return false;
	}

	/**
	 *
	 */
	public function test_rest_Action()
	{


		$test_client = null;
		$cl = new Client();

		switch( Http_Request::GET()->getString( 'test' ) ) {
			case 'article_get_list':
				$cl->get('article');

				$test_client = $cl;
				break;
			case 'article_get_unknown':
				$cl->get('article/unknownunknownunknown');

				$test_client = $cl;
				break;
			case 'article_get_one':
				$cl->get('article');
				$id = $cl->responseData()['items'][0]['id'];

				$cl->get('article/'.$id);

				$test_client = $cl;
				break;
		}


		$this->view->setVar( 'test_client', $test_client );
		$this->render( 'test-orm' );
	}

}