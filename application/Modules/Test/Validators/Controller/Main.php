<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Validators;

use Jet\Locale;
use Jet\MVC_Controller_Default;
use Jet\Tr;
use Jet\Validator_Color;
use Jet\Validator_Date;
use Jet\Validator_DateTime;
use Jet\Validator_Email;
use Jet\Validator_File;
use Jet\Validator_FileImage;
use Jet\Validator_Float;
use Jet\Validator_Int;
use Jet\Validator_Month;
use Jet\Validator_Option;
use Jet\Validator_Options;
use Jet\Validator_Password;
use Jet\Validator_RegExp;
use Jet\Validator_Tel;
use Jet\Validator_Time;
use Jet\Validator_Url;
use Jet\Validator_Week;

class Controller_Main extends MVC_Controller_Default
{
	
	/** @noinspection SpellCheckingInspection */
	public function test_validators_Action(): void
	{
		
		$tests = [];
		
		$validator = new Validator_Color();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'#FFC6C6',
				'#9FAAFF',
				'#9faaff'
			],
			invalid_values: [
				'',
				'aaaaaa',
				'#zzzzzz',
				'#123456789'
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Date();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'1980-08-13',
				'2012-12-31',
				'2025-10-13'
			],
			invalid_values: [
				'',
				'2025-02-30',
				'aaaaaa',
				'aaaa-01-19',
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_DateTime();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'1980-08-13T01:01:01',
				'2012-12-31T01:01:01',
				'2025-10-13T01:01:01',
				'1980-08-13 01:01:01',
				'2012-12-31 01:01:01',
				'2025-10-13 01:01:01',
				
				'1980-08-13T01:01',
				'2012-12-31T01:01',
				'2025-10-13T01:01',
				'1980-08-13 01:01',
				'2012-12-31 01:01',
				'2025-10-13 01:01',

			],
			invalid_values: [
				'',
				'2025-02-30T11:11:11',
				'2025-01-01T24:99:66',
				'aaaaaaT00:00:00',
				'aaaa-01-19T00:00:00',
				'2025-02-30 11:11:11',
				'2025-01-01 24:99:66',
				'aaaaaa 00:00:00',
				'aaaa-01-19 00:00:00',
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Time();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'01:01:01',
				'01:01',
			],
			invalid_values: [
				'',
				'aaaaa',
				'24:61:61',
				'24:61',
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Month();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'1980-08',
				'2012-12',
				'2025-10'
			],
			invalid_values: [
				'',
				'2025-13',
				'aaaaaa',
				'aaaa-01',
			]
		);
		$tests[] = $test;
		
		
		
		
		$validator = new Validator_Week();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'1980-W30',
				'2012-W52',
				'2025-W40'
			],
			invalid_values: [
				'',
				'2025-13',
				'aaaaaa',
				'aaaa-01',
			]
		);
		$tests[] = $test;
		
		
		
		
		$validator = new Validator_Email();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'mirek.marek@web-jet.cz',
			],
			invalid_values: [
				'',
				'aaaaa',
				'dude@fakedomain.mars',
			]
		);
		$tests[] = $test;
		

		
		$validator = new Validator_Float();
		$validator->setIsRequired( true );
		$validator->setMinValue( 2.2 );
		$validator->setMaxValue( 5.5 );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				3.14,
				2.2,
				5.5
			],
			invalid_values: [
				1.1,
				6.6
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Int();
		$validator->setIsRequired( true );
		$validator->setMinValue( 2 );
		$validator->setMaxValue( 5 );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				3,
				2,
				5
			],
			invalid_values: [
				1,
				6
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Url();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'https://www.php-jet.net/',
				'http://www.php-jet.net/',
			],
			invalid_values: [
				'',
				'aaaaaa',
				'http//aaaaa',
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Option();
		$validator->setIsRequired( true );
		$validator->setValidOptions([
			'https://www.php.net/',
			'https://www.php-jet.net/',
			'http://www.php-jet.net/',
			'https://www.jetbrains.com/',
		]);
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'https://www.php.net/',
				'https://www.php-jet.net/',
				'http://www.php-jet.net/',
				'https://www.jetbrains.com/'
			],
			invalid_values: [
				'',
				'https://nextjs.org/',
				'https://nodejs.org/',
			]
		);
		$tests[] = $test;
		
		
		
		$validator = new Validator_Options();
		$validator->setIsRequired( true );
		$validator->setValidOptions([
			'https://www.php.net/',
			'https://www.php-jet.net/',
			'http://www.php-jet.net/',
			'https://www.jetbrains.com/',
		]);
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				[
					'https://www.php.net/',
					'https://www.php-jet.net/',
				],
				[
					'http://www.php-jet.net/',
					'https://www.jetbrains.com/'
				]
			],
			invalid_values: [
				[],
				[
					'https://nextjs.org/',
					'https://nodejs.org/',
				]
			]
		);
		$tests[] = $test;
		
		
		$validator = new Validator_RegExp();
		$validator->setIsRequired( true );
		$validator->setValidationRegexp('/^[0-9]{3} [0-9]{2}$/');
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'124 45',
				'543 21'
			],
			invalid_values: [
				'',
				'aaaa',
				'123 aa',
				'bbb 11'
			]
		);
		$tests[] = $test;
		
		
		$locale = new Locale('cs_CZ');
		
		$validator = new Validator_Tel();
		$validator->setIsRequired( true );
		$validator->setLocale( $locale );
		$validator->setTelNumberWithPrefix( false );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'602602602',
				'777777777',
				'603603603'
			],
			invalid_values: [
				'',
				'123456789',
				'158',
				'999999999'
			]
		);
		$test->setName( $test->getName().' - '.Tr::_('With prefix') );
		$tests[] = $test;
		
		$validator = new Validator_Tel();
		$validator->setIsRequired( true );
		$validator->setLocale( $locale );
		$validator->setTelNumberWithPrefix( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'+420602602602',
				'+420777777777',
				'+420603603603'
			],
			invalid_values: [
				'',
				'602602602',
				'777777777',
				'603603603',
				'+420123456789',
				'+420158',
				'+420999999999'
			]
		);
		$test->setName( $test->getName().' - '.Tr::_('Without prefix') );
		$tests[] = $test;
		
		

		$validator = new Validator_Password();
		$validator->setIsRequired( true );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				'QxYni5!gOI6&231',
			],
			invalid_values: [
				'',
				'admin123',
				'SunSet2025',
				'aaaaaa',
				'yohn1980'
			]
		);
		$tests[] = $test;
		
		
		$test_files_dir = dirname( __DIR__ ).'/testFiles/';
		
		$validator = new Validator_File();
		$validator->setIsRequired( true );
		$validator->setAllowedMimeTypes( ['application/pdf'] );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				$test_files_dir.'test.pdf',
			],
			invalid_values: [
				$test_files_dir.'test.png',
			]
		);
		$test->setName( $test->getName().' - '.Tr::_('File mime type') );
		$tests[] = $test;
		
		
		
		$validator = new Validator_File();
		$validator->setIsRequired( true );
		$validator->setMaximalFileSize( 102400 );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				$test_files_dir.'test.pdf',
			],
			invalid_values: [
				$test_files_dir.'test.png',
			]
		);
		$test->setName( $test->getName().' - '.Tr::_('File size') );
		$tests[] = $test;
		
		
		
		
		
		$validator = new Validator_FileImage();
		$validator->setIsRequired( true );
		$validator->setMaximalWidth( 100 );
		$validator->setMaximalHeight( 100 );
		$test = new ValidatorTest(
			validator: $validator,
			valid_values: [
				$test_files_dir.'test_small.png',
			],
			invalid_values: [
				$test_files_dir.'test.png',
			]
		);
		$test->setName( $test->getName().' - '.Tr::_('Image size') );
		$tests[] = $test;
		

		$this->view->setVar( 'tests', $tests );
		$this->output( 'test-validators' );
	}

	public function test_validators_generated_Action(): void
	{

		$obj = new EntityTest_Entity();
		
		$this->view->setVar( 'test_object', $obj );
		
		$this->output( 'test-validators-generated' );
	}
}