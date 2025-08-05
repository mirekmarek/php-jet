<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\MVC_Controller_Default;
use Jet\Tr;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
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
		$username_field->setIsRequired(true);
		$username_field->setErrorMessages([
			Form_Field::ERROR_CODE_EMPTY => 'Please enter username'
		]);
		$password_field = new Form_Field_Password( 'password', 'Password:' );
		$password_field->setIsRequired(true);
		$password_field->setErrorMessages([
			Form_Field::ERROR_CODE_EMPTY => 'Please enter password'
		]);
		
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
					Test_Article_GetList::class,
					Test_Article_GetListSortAndPagination::class,

					Test_Article_GetOne::class,
					Test_Article_GetUnknown::class,

					Test_Article_Post::class,
					Test_Article_PostInvalid::class,

					Test_Article_Put::class,
					Test_Article_PutInvalid::class,

					Test_Article_Delete::class,
					Test_Article_DeleteInvalid::class,

				]
			],
			[
				'title' => 'Images',
				'tests' => [
					Test_Gallery_GetList::class,
					Test_Gallery_GetTree::class,
					Test_Gallery_GetListSortAndPagination::class,

					Test_Gallery_GetOne::class,
					Test_Gallery_GetUnknown::class,

					Test_Gallery_Post::class,
					Test_Gallery_PostInvalid::class,

					Test_Gallery_Put::class,
					Test_Gallery_PutInvalid::class,

					Test_Gallery_Delete::class,
					Test_Gallery_DeleteInvalid::class,

					Test_Gallery_GetImages::class,
					Test_Gallery_GetImagesUnknown::class,

					Test_Gallery_ImagePost::class,
					Test_Gallery_ImagePostInvalidType::class,

					Test_Gallery_GetImage::class,
					Test_Gallery_GetImageUnknown::class,
					Test_Gallery_GetImageThb::class,

					Test_Gallery_DeleteImage::class,
					Test_Gallery_DeleteImageInvalid::class,

				]
			]
		];

		foreach( $tests as $i => $tests_data ) {
			$tests[$i]['title'] = Tr::_( $tests_data['title'] );

			$test_instances = [];

			foreach( $tests_data['tests'] as $class_name ) {
				/**
				 * @var Test_Abstract $test
				 * @phpstan-ignore varTag.nativeType
				 */
				$test = new $class_name( $data );

				$test_instances[$test->getId()] = $test;
				$all_tests[$test->getId()] = $test_instances[$test->getId()];

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