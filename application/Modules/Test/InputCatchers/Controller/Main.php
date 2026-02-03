<?php /** @noinspection SpellCheckingInspection */

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\InputCatchers;

use Jet\Data_DateTime;
use Jet\InputCatcher_Bool;
use Jet\InputCatcher_Date;
use Jet\InputCatcher_Float;
use Jet\InputCatcher_Floats;
use Jet\InputCatcher_Int;
use Jet\InputCatcher_Ints;
use Jet\InputCatcher_String;
use Jet\InputCatcher_StringRaw;
use Jet\InputCatcher_Strings;
use Jet\MVC_Controller_Default;

class Controller_Main extends MVC_Controller_Default
{
	
	public function test_input_catchers_Action(): void
	{
		
		$tests = [];
		
		$catcher = new InputCatcher_String( 'string', 'default value' );
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['string' => 'String'],
			['string' => "String with HTML <script>alert('Test');</script> - possible XSS"]
		] );
		$tests[] = $catcher_test;
		
		$catcher = new InputCatcher_Strings( 'strings', ['default value'] );
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['strings' => [
				'String',
				"String with HTML <script>alert('Test');</script> - possible XSS"
			]],
			['strings' => "String with HTML <script>alert('Test');</script> - possible XSS"]
		] );
		$tests[] = $catcher_test;
		
		
		
		$catcher = new InputCatcher_StringRaw( 'string', 'default <i>value</i>' );
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['string' => 'String'],
			['string' => "String with HTML <b>Test</b> - possible XSS again!"]
		] );
		$tests[] = $catcher_test;
		
		
		
		$catcher = new InputCatcher_Int('int', 123456789);
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['int' => '654321'],
			['int' => '123aaaa'],
			['int' => 'bbbbbb'],
			['int' => 'b123456'],
		] );
		$tests[] = $catcher_test;

		
		
		$catcher = new InputCatcher_Ints('ints', [123456789, 987654321]);
		$catcher->setIgnoreZeros( true );
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['ints' => [
				'654321',
				'123aaaa',
				'bbbbbb',
				'b123456'
			]],
		] );
		$tests[] = $catcher_test;
		
		
		$catcher = new InputCatcher_Float('float', 123.456);
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['float' => '654.321'],
			['float' => '654,321'],
			['float' => '12.3aaaa'],
			['float' => '12,3aaaa'],
			['float' => 'bbbbbb'],
			['float' => 'b12.3456'],
		] );
		$tests[] = $catcher_test;
		
		$catcher = new InputCatcher_Floats('float', [123.456, 654.321]);
		$catcher->setIgnoreZeros( true );
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => null],
			['float' => [
				'654.321',
				'654,321',
				'12.3aaaa',
				'12,3aaaa',
				'bbbbbb',
				'b12.3456'
			]]
		] );
		$tests[] = $catcher_test;
		
		
		
		
		$catcher = new InputCatcher_Bool('bool', false);
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['bool' => '1'],
			['bool' => '0'],
			['bool' => ''],
			['bool' => 'y'],
		] );
		$tests[] = $catcher_test;
		
		
		
		
		$catcher = new InputCatcher_Date('date', Data_DateTime::now());
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['date' => '1980-08-03'],
			['date' => '2012-12-31'],
			['date' => '2199-13-45'],
			['date' => '2021-02-29'],
			['date' => '2024-02-29'],
			['date' => '2024-02-31'],
		] );
		$tests[] = $catcher_test;
		
		$catcher = new InputCatcher_Date('date_time', Data_DateTime::now());
		$catcher_test = new InputCatcherTest( $catcher, [
			['' => ''],
			['date_time' => '1980-08-03 01:01:01'],
			['date_time' => '2012-12-31 06:02:00'],
			['date_time' => '2199-13-45 24:99:99'],
			['date_time' => '2021-02-29 12:00:00'],
			['date_time' => '2024-02-29 12:00:00'],
			['date_time' => '2024-02-31 12:00:00'],
		] );
		$tests[] = $catcher_test;
		
		
		$this->view->setVar( 'tests', $tests );
		$this->output( 'test-input-catchers' );
	}
	
	public function test_input_catchers_generated_Action(): void
	{
		$input = [
			'int_value' => '123456',
			'sub_entity' => [
				'int_value' => '123456',
			],
			'sub_entities' => [
				'1' => [
					'int_value' => '123456',
				],
				'2' => [
					'int_value' => '223456',
				],
				'3' => [
					'int_value' => '323456',
				],
				'4' => [
					'int_value' => '423456',
				],
				'5' => [
					'int_value' => '523456',
				],
			]
		];
		
		$obj = new EntityTest_Entity();
		
		$obj->catchInput( $input );
		
		$this->view->setVar( 'input', $input );
		$this->view->setVar( 'test_object', $obj );
		
		$this->output( 'test-input-catchers-generated' );
	}
}