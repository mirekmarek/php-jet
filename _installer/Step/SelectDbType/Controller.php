<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Factory_DataModel;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Db_Config;
use Jet\DataModel_Config;
use Jet\Http_Headers;
use Jet\Translator;
use Jet\UI_messages;
use Jet\Tr;
use Jet\DataModel_Backend;

/**
 *
 */
class Installer_Step_SelectDbType_Controller extends Installer_Step_Controller
{

	protected string $icon = 'database';

	/**
	 * @var string
	 */
	protected string $label = 'Select Database type';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return !Installer_Step_CreateBases_Controller::basesCreated();
	}

	/**
	 *
	 */
	public function main(): void
	{

		$db_type_field = new Form_Field_Select( 'type', 'Database type:' );
		$db_type_field->setSelectOptions( DataModel_Backend::getAvlBackendTypes() );
		$db_type_field->setDefaultValue( static::getSelectedBackendType() );
		$db_type_field->setIsRequired( true );

		$db_type_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select database type',
				Form_Field::ERROR_CODE_EMPTY         => 'Please select database type',
			]
		);

		$select_db_type_form = new Form(
			'select_db_type_form', [$db_type_field]
		);


		if(
			$select_db_type_form->catchInput() &&
			$select_db_type_form->validate()
		) {
			$backend = Factory_DataModel::getBackendInstance( $db_type_field->getValue() );
			
			static::setSelectedDbType( $backend->getType() );
			
			
			$data_model_config = new DataModel_Config();
			$data_model_config->setBackendType( $backend->getType() );
			
			$db_config = new Db_Config();
			$db_connection_config = $backend->prepareDefaultDbConnectionConfig();
			if($db_connection_config) {
				$db_config->addConnection( $db_connection_config );
			}


			try {
				$db_config->saveConfigFile();
				$data_model_config->saveConfigFile();
			} catch( Exception $e ) {
				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Translator::COMMON_DICTIONARY ) );
				Http_Headers::reload();
			}


			Installer::goToNext();
		}

		$this->view->setVar( 'form', $select_db_type_form );


		$this->render( 'default' );

	}


	/**
	 * @return string
	 */
	public static function getSelectedBackendType(): string
	{
		$session = Installer::getSession();

		$types = DataModel_Backend::getAvlBackendTypes();

		if( !$session->getValueExists( 'backend_type' ) ) {
			[$default] = array_keys( $types );

			$session->setValue( 'backend_type', $default );
		}

		return $types[$session->getValue( 'backend_type' )];
	}

	/**
	 * @param string $type
	 */
	public static function setSelectedDbType( string $type ): void
	{

		if( !isset( DataModel_Backend::getAvlBackendTypes()[$type] ) ) {
			return;
		}

		Installer::getSession()->setValue( 'backend_type', $type );

	}

	/**
	 * @return array|bool
	 */
	public function getStepsAfter(): array|bool
	{
		return ['ConfigureDb'];

/*
		if( static::getSelectedBackendType()!=DataModel_Backend::TYPE_SQLITE )
		{
			return [ 'ConfigureDb' ];
		}

		return false;
*/
	}
}
