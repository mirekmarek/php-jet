<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Forms;

use Jet\Form_Field_File_UploadedFile;
use Jet\Locale;
use Jet\MVC_Controller_Default;

use Jet\Http_Request;
use Jet\SysConf_Path;
use Jet\Tr;

use Jet\Form;

use Jet\Form_Field;

use Jet\Form_Field_Input;

use Jet\Form_Field_Int;
use Jet\Form_Field_Float;
use Jet\Form_Field_Range;

use Jet\Form_Field_Date;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Time;
use Jet\Form_Field_Week;
use Jet\Form_Field_Month;

use Jet\Form_Field_Email;
use Jet\Form_Field_Tel;

use Jet\Form_Field_Url;
use Jet\Form_Field_Search;

use Jet\Form_Field_Color;

use Jet\Form_Field_Select;
use Jet\Form_Field_MultiSelect;

use Jet\Form_Field_Checkbox;
use Jet\Form_Field_RadioButton;

use Jet\Form_Field_Textarea;
use Jet\Form_Field_WYSIWYG;

use Jet\Form_Field_Password;

use Jet\Form_Field_File;
use Jet\Form_Field_FileImage;

use Jet\IO_Dir;
use Jet\IO_File;

use Jet\UI_messages;
use Jet\AJAX;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function test_forms_Action(): void
	{
		$input_field = new Form_Field_Input( 'input', 'Input' );
		$input_field->setPlaceholder( 'Input field without validation' );

		$validated_input_field = new Form_Field_Input( 'validated_input', 'Validated input' );
		$validated_input_field->setIsRequired( true );
		$validated_input_field->setPlaceholder( 'Enter ZIP code (NNN NN)' );
		$validated_input_field->setHelpText( 'Format: NNN NN' );
		$validated_input_field->setValidationRegexp( '/^[0-9]{3} [0-9]{2}$/' );
		$validated_input_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter ZIP code',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);

		$int_field = new Form_Field_Int( 'int', 'Int' );
		$int_field->setIsRequired( true );
		$int_field->setMinValue( 10 );
		$int_field->setMaxValue( 100 );
		$int_field->setStep( 10 );
		$int_field->setHelpText('Minimal value: %min%, maximal value: %max%');
		$int_field->setHelpData([
			'min' => Locale::int($int_field->getMinValue()),
			'max' => Locale::int($int_field->getMaxValue()),
		]);
		
		$int_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY        => 'Field is required',
				Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Out of range',
			]
		);


		$float_field = new Form_Field_Float( 'float', 'Float' );
		$float_field->setIsRequired( true );
		$float_field->setMinValue( -0.10 );
		$float_field->setMaxValue( 3.50 );
		$float_field->setStep( 0.1 );
		$float_field->setHelpText('Minimal value: %min%, maximal value: %max%');
		$float_field->setHelpData([
			'min' => Locale::float($float_field->getMinValue()),
			'max' => Locale::float($float_field->getMaxValue()),
		]);
		
		
		$float_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY        => 'Field is required',
				Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Out of range',
			]
		);


		$range_field = new Form_Field_Range( 'range', 'Range' );
		$range_field->setIsRequired( true );
		$range_field->setMinValue( 10 );
		$range_field->setMaxValue( 100 );
		$range_field->setStep( 10 );
		$range_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY        => 'Field is required',
				Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Out of range',
			]
		);


		$date_field = new Form_Field_Date( 'date', 'Date' );
		$date_field->setIsRequired( true );
		$date_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter date',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter date',
			]
		);
		$date_field->setPlaceholder( 'Date' );


		$date_time_field = new Form_Field_DateTime( 'date_time', 'Date and time' );
		$date_time_field->setIsRequired( true );
		$date_time_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter date and time',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter date and time',
			]
		);
		$date_time_field->setPlaceholder( 'Date and time' );


		$time_field = new Form_Field_Time( 'time', 'Time' );
		$time_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter time',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);

		$week_field = new Form_Field_Week( 'week', 'Week' );
		$week_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter week',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);

		$month_field = new Form_Field_Month( 'month', 'Month' );
		$month_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter month',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);


		$email_field = new Form_Field_Email( 'email', 'Email' );
		$email_field->setIsRequired( true );
		$email_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter e-mail address',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter e-mail address',
			]
		);
		$date_time_field->setPlaceholder( 'E-mail address' );


		$url_field = new Form_Field_Url( 'url', 'URL' );
		$url_field->setIsRequired( true );
		$url_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter URL address',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter URL address',
			]
		);
		$url_field->setPlaceholder( 'URL address' );


		$tel_field = new Form_Field_Tel( 'tel', 'Telephone number' );
		$tel_field->setIsRequired( true );
		$tel_field->setValidationRegexp( '/^[0-9]{9,12}$/' );
		$tel_field->setPlaceholder( 'Telephone number' );
		$tel_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter telephone number',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter telephone number',
			]
		);


		$search_field = new Form_Field_Search( 'search', 'Search' );


		$color_field = new Form_Field_Color( 'color', 'Color' );
		$color_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY          => 'Please select color',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);


		$select_field = new Form_Field_Select( 'select', 'Select' );
		$select_field->setSelectOptions(
			[
				'o1' => 'Option 1',
				'o2' => 'Option 2',
				'o3' => 'Option 3',
				'o4' => 'Option 4',
			]
		);
		
		$select_options = $select_field->getSelectOptions();
		$select_options['o1']->setSelectOptionCssStyle('background-color:#f9f9f9');
		$select_options['o2']->setSelectOptionCssStyle('background-color:#e9e9e9');
		$select_options['o3']->setSelectOptionCssStyle('background-color:#d9d9d9');
		$select_options['o4']->setSelectOptionCssStyle('background-color:#c9c9c9');
		
		$select_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value',
			]
		);


		$multi_select_field = new Form_Field_MultiSelect( 'multi_select', 'Multi Select' );
		$multi_select_field->setSelectOptions(
			[
				'o1' => 'Option 1',
				'o2' => 'Option 2',
				'o3' => 'Option 3',
				'o4' => 'Option 4',
			]
		);
		
		$select_options = $multi_select_field->getSelectOptions();
		$select_options['o1']->setSelectOptionCssStyle('color:#000000');
		$select_options['o2']->setSelectOptionCssStyle('color:#a0a0a0');
		$select_options['o3']->setSelectOptionCssStyle('color:#b0b0b0');
		$select_options['o4']->setSelectOptionCssStyle('color:#c0c0c0');
		
		$multi_select_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value',
			]
		);

		$checkbox_field = new Form_Field_Checkbox( 'checkbox', 'Checkbox' );

		$radio_field = new Form_Field_RadioButton( 'radio', 'Radio buttons' );
		$radio_field->setSelectOptions(
			[
				'o1' => 'Option 1',
				'o2' => 'Option 2',
				'o3' => 'Option 3',
				'o4' => 'Option 4',
			]
		);
		
		$select_options = $radio_field->getSelectOptions();
		$select_options['o1']->setSelectOptionCssStyle('background-color:#f9f9f9');
		$select_options['o2']->setSelectOptionCssStyle('background-color:#e9e9e9');
		$select_options['o3']->setSelectOptionCssStyle('background-color:#d9d9d9');
		$select_options['o4']->setSelectOptionCssStyle('background-color:#c9c9c9');
		
		$radio_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value',
			]
		);

		$textarea_field = new Form_Field_Textarea( 'textarea', 'Text area' );
		$wysiwyg_field = new Form_Field_WYSIWYG( 'wysiwyg', 'WYSIWYG' );



		$password_field = new Form_Field_Password( 'password_field', 'Password' );
		$password_field->setIsRequired( false );


		$upload_image_field = new Form_Field_FileImage( 'upload_image', 'Upload image' );
		
		$image_catcher = function( array $files ) {
			/**
			 * @var Form_Field_File_UploadedFile[] $files
			 */
			
			$target_dir = SysConf_Path::getImages() . 'test_uploads/';
			
			IO_Dir::create( $target_dir );
			
			foreach($files as $file) {
				
				IO_File::copy(
					$file->getTmpFilePath(),
					$target_dir . $file->getFileName()
				);
			}
		};
		
		$upload_image_field->setAllowMultipleUpload( true );
		$upload_image_field->setIsRequired( true );
		$upload_image_field->setMaximalSize( 200, 150 );
		$upload_image_field->setMaximalFileSize( 2 * 1024 * 1024 );
		$upload_image_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY                => 'Please select image',
				Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => '%file_name%: Unsupported file type',
				Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => '%file_name%: File is too large (%file_size%). Maximal file size is %max_file_size%',
			]
		);
		
		$upload_image_field->setFieldValueCatcher( $image_catcher );

		
		
		
		
		$upload_file_field = new Form_Field_File( 'upload_file', 'Upload file' );
		
		$upload_file_field->setAllowMultipleUpload( true );
		$upload_file_field->setIsRequired( true );
		$upload_file_field->setMaximalFileSize( 2 * 1024 * 1024 );
		$upload_file_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY                => 'Please select file',
				Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => '%file_name%: Unsupported file type',
				Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => '%file_name%: File is too large (%file_size%). Maximal file size is %max_file_size%',
			]
		);
		$upload_file_field->setFieldValueCatcher(
			function( array $files ) {
				/**
				 * @var Form_Field_File_UploadedFile[] $files
				 */

				/*
				$target_dir = SysConf_Path::getTmp().'test_uploads/';

				IO_Dir::create($target_dir);

				foreach( $files as $file ) {
					
					IO_File::copy(
						$file->getTmpFilePath(),
						$target_dir.$file->getFileName()
					);
				}
				*/
				
			}
		);


		$forms = [];
		
		$common_form = new Form(
			'common_form', [
				$input_field,
				$validated_input_field,
			]
		);
		$common_form->enableCSRFProtection();

		$forms['common_form'] = [
			'title' => Tr::_( 'Common form' ),
			'form'  => $common_form,
		];
		

		$forms['numbers_form'] = [
			'title' => Tr::_( 'Number form' ),
			'form'  => new Form(
				'numbers_form', [
					$int_field,
					$float_field,
					$range_field,
				]
			),
		];

		$forms['date_form'] = [
			'title' => Tr::_( 'Time and date form' ),
			'form'  => new Form(
				'date_form', [
					$date_field,
					$date_time_field,
					$time_field,
					$week_field,
					$month_field,
				]
			),
		];

		$forms['contact_form'] = [
			'title' => Tr::_( 'Contact form' ),
			'form'  => new Form(
				'contact_form', [
					$email_field,
					$tel_field,
				]
			),
		];

		$forms['select_form'] = [
			'title' => Tr::_( 'Select form' ),
			'form'  => new Form(
				'select_form', [
					$select_field,
					$multi_select_field,
					$checkbox_field,
					$radio_field,

				]
			),
		];

		$forms['text_form'] = [
			'title' => Tr::_( 'Text form' ),
			'do_now_rewrite_form_snippet' => true,
			'form'  => new Form(
				'text_form', [
					$textarea_field,
					$wysiwyg_field,
				]
			),
		];

		$forms['special_form'] = [
			'title' => Tr::_( 'Special fields form' ),
			'form'  => new Form(
				'special_form', [
					$password_field,
					$url_field,
					$search_field,
					$color_field,
				]
			),
		];


		$forms['upload_file_form'] = [
			'title' => Tr::_( 'File upload form' ),
			'form'  => new Form(
				'upload_file_form', [
					$upload_file_field,
				]
			),
		];


		$forms['upload_image_form'] = [
			'title' => Tr::_( 'Image upload form' ),
			'form'  => new Form(
				'upload_image_form', [
					$upload_image_field,
				]
			),
		];


		foreach( $forms as $form_name => $d ) {
			/**
			 * @var Form $form
			 */
			$form = $d['form'];

			$form->setAction( '#' . $form->getId() );

			if( $form->catchInput() ) {
				$form->validate();
				if( $form->getIsValid() ) {
					$form->catchFieldValues();
					$form->setCommonMessage( UI_messages::createSuccess( Tr::_( 'Form sent and is valid' ) ) );
				} else {
					$form->setCommonMessage( UI_messages::createDanger( Tr::_( 'Form sent, but is not valid' ) ) );
				}

				if( Http_Request::POST()->exists( 'ajax' ) ) {
					$this->view->setVar( 'form', $form );

					$snippets = [];

					if(empty($d['do_now_rewrite_form_snippet'])) {
						$snippets['form_area_' . $form->getId()] = $this->view->render( 'test-forms/form' );
					}

					$snippets['form_message_area_' . $form->getId()] = $this->view->render( 'test-forms/message' );
					$snippets['form_sent_area_' . $form->getId()] = $this->view->render( 'test-forms/sent' );

					AJAX::operationResponse(
						$form->getIsValid(),
						$snippets
					);
				}
			}


			$this->view->setVar( $form_name, $form );
		}


		$this->view->setVar( 'forms', $forms );
		$this->output( 'test-forms' );
	}

	/**
	 *
	 */
	public function test_forms_generated_Action(): void
	{

		$obj = new DefinitionTest_FormGenerator();
		
		//$only_fields = ['*','/sub_entities/*/title'];
		//$exclude_fields = [];
		
		$only_fields = [];
		$exclude_fields = [];
		
		$form = $obj->createForm('generated_form', $only_fields, $exclude_fields);

		if( $form->catchInput() ) {

			$form->validate();
			if( $form->getIsValid() ) {
				$form->catchFieldValues();
				$form->setCommonMessage( UI_messages::createSuccess( Tr::_( 'Form sent and is valid' ) ) );

				$this->view->setVar( 'test_object', $obj );
			} else {
				$form->setCommonMessage( UI_messages::createDanger( Tr::_( 'Form sent, but is not valid' ) ) );
			}

		}
		$this->view->setVar( 'form', $form );
		
		$this->view->setVar( 'main_getters', [
			'getLongText',
			'getText',
			'getDate',
			'getDateTime',
			'getHTML',
			'getCheckbox',
			'getFloat',
			'getInt',
			'getSelect',
			'getMultiSelect',
			'getRadioButton',
			'getPassword',
		] );
		
		$this->view->setVar( 'sub_entities_getters', [
			'getTitle',
			'getText'
		]);
		
		$this->view->setVar( 'sub_entity_getters', [
			'getSomeText',
		]);
		
		$this->output( 'test-forms-generated' );
	}
}