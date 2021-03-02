<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Controller_Default;
use Jet\Tr;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 *
	 */
	public function test_rest_Action(): void
	{
		$session = Main::getSession();

		if(
			!$session->getValueExists( 'username' ) ||
			!$session->getValueExists( 'password' ) ||
			!$session->getValue( 'valid_login' )
		) {
			$this->login();
		} else {
			if( Http_Request::GET()->exists( 'logout' ) ) {
				$session = Main::getSession();
				$session->unsetValue( 'username' );
				$session->unsetValue( 'password' );
				$session->unsetValue( 'valid_login' );

				Http_Headers::reload( [], ['logout'] );

			}
			$this->tests();
		}
	}

	/**
	 *
	 */
	public function login(): void
	{

		$username_field = new Form_Field_Input( 'username', 'Username:' );
		$password_field = new Form_Field_Password( 'password', 'Password:' );
		$form = new Form( 'login_form', [
			$username_field,
			$password_field
		] );

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$username = $username_field->getValue();
			$password = $password_field->getValue();


			$client = new Client( $username, $password );

			$client->get( '' );

			if( $client->responseStatus() == 200 ) {
				$session = Main::getSession();
				$session->setValue( 'username', $username );
				$session->setValue( 'password', $password );
				$session->setValue( 'valid_login', true );

				Http_Headers::reload();
			}

			$this->view->setVar( 'client', $client );


		}

		$this->view->setVar( 'form', $form );

		$this->output( 'login' );
	}

	/**
	 *
	 */
	public function tests(): void
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

		$init_data = function() use ( &$data, $client ) {
			if( $client->get( 'article' ) ) {
				$data['articles'] = $client->responseData()['items'];
			}

			if( $client->get( 'gallery' ) ) {
				$data['galleries'] = $client->responseData()['items'];

				if( $data['galleries'] ) {
					$gallery = $data['galleries'][0];

					if( $client->get( 'gallery/' . $gallery['id'] . '/image' ) ) {
						$data['images'] = $client->responseData()['items'];
					}
				}
			}
		};

		$init_data();


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

		foreach( $tests as $i => $tests_data ) {
			$tests[$i]['title'] = Tr::_( $tests_data['title'] );

			$test_instances = [];

			foreach( $tests_data['tests'] as $test ) {
				$class_name = __NAMESPACE__ . '\\Test_' . $test;
				$test_instances[$test] = new $class_name( $test, $data );
				$all_tests[$test] = $test_instances[$test];

			}

			$tests[$i]['tests'] = $test_instances;
		}


		$selected_test_id = Http_Request::GET()->getString( 'test', '', array_keys( $all_tests ) );

		$this->view->setVar( 'tests', $tests );
		if( $selected_test_id ) {
			$all_tests[$selected_test_id]->setIsSelected( true );
			$this->view->setVar( 'selected_test', $all_tests[$selected_test_id] );


			$all_tests[$selected_test_id]->test();

			//$init_data();
		}

		$this->output( 'tests' );

	}
}