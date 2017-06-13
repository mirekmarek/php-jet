<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;

use Jet\Http_Request;
use Jet\Mvc_Controller_Default;
use Jet\Tr;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
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
		/**
		 * @var Test_Abstract[] $all_tests
		 */
		$all_tests = [];

		$client = new Client();

		$data = [
			'articles'  => [],
		    'galleries' => [],
		    'images'    => []
		];

		if($client->get('article')) {
			$data['articles'] = $client->responseData()['items'];
		}

		if($client->get('gallery')) {
			$data['galleries'] = $client->responseData()['items'];

			if($data['galleries']) {
				$gallery = $data['galleries'][0];

				if($client->get('gallery/'.$gallery['id'].'/image')) {
					$data['images'] = $client->responseData()['items'];
				}
			}
		}



		$tests = [
			[
				'title' => 'Articles',
			    'tests' => [
			    	'Article_GetList',
				    'Article_GetListSortAndPagination',

				    'Article_GetOne',
			        'Article_GetUnknown',

				    'Article_Post',
				    'Article_PostInvalid',

				    'Article_Put',
				    'Article_PutInvalid',

				    'Article_Delete',
				    'Article_DeleteInvalid',

			    ]
			],
		    [
			    'title' => 'Images',
			    'tests' => [
				    'Gallery_GetList',
				    'Gallery_GetTree',
			        'Gallery_GetListSortAndPagination',

			        'Gallery_GetOne',
				    'Gallery_GetUnknown',

			    	'Gallery_Post',
				    'Gallery_PostInvalid',

				    'Gallery_Put',
				    'Gallery_PutInvalid',

				    'Gallery_Delete',
				    'Gallery_DeleteInvalid',

				    'Gallery_GetImages',
				    'Gallery_GetImagesUnknown',

				    'Gallery_ImagePost',
			        'Gallery_ImagePostInvalidType',

				    'Gallery_GetImage',
				    'Gallery_GetImageUnknown',
				    'Gallery_GetImageThb',

				    'Gallery_DeleteImage',
				    'Gallery_DeleteImageInvalid'

				]
		    ]
		];

		foreach( $tests as $i=>$tests_data ) {
			$tests[$i]['title'] = Tr::_($tests_data['title']);

			$test_instances = [];

			foreach( $tests_data['tests'] as $test ) {
				$class_name = __NAMESPACE__.'\\Test_'.$test;
				$test_instances[$test] = new $class_name( $test, $data );
				$all_tests[$test] = $test_instances[$test];

			}

			$tests[$i]['tests'] = $test_instances;
		}


		$selected_test_id = Http_Request::GET()->getString( 'test', '', array_keys($all_tests) );

		$this->view->setVar('tests', $tests);
		if($selected_test_id) {
			$all_tests[$selected_test_id]->setIsSelected(true);
			$this->view->setVar( 'selected_test', $all_tests[$selected_test_id] );


			$all_tests[$selected_test_id]->test();
		}

		$this->render( 'test-orm' );
	}

}