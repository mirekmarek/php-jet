<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Db_Config;
use Jet\DataModel_Config;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;
use Jet\DataModel_Backend;

/**
 *
 */
class Installer_Step_SelectDbType_Controller extends Installer_Step_Controller
{


	/**
	 * @var string
	 */
	protected string $label = 'Select Database type';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main(): void
	{

		$db_type_field = new Form_Field_Select( 'type', 'Please database type: ' );
		$db_type_field->setSelectOptions( DataModel_Backend::getBackendTypes( true ) );
		$db_type_field->setDefaultValue( static::getSelectedBackendType()['type'] );
		$db_type_field->setIsRequired( true );

		$db_type_field->setErrorMessages(
			[
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please database type',
				Form_Field_Select::ERROR_CODE_EMPTY         => 'Please database type',
			]
		);

		$select_db_type_form = new Form(
			'select_db_type_form', [$db_type_field]
		);


		if(
			$select_db_type_form->catchInput() &&
			$select_db_type_form->validate()
		) {
			static::setSelectedDbType( $db_type_field->getValue() );

			$driver = static::getSelectedBackendType()['driver'];

			$data_model_config = new DataModel_Config();
			$db_config = new Db_Config();

			require Installer::getBasePath() . 'Classes/DbDriverConfig.php';
			require Installer::getBasePath() . 'Classes/DbDriverConfig/' . $driver . '.php';

			$class_name = __NAMESPACE__ . '\\Installer_DbDriverConfig_' . $driver;

			/**
			 * @var Installer_DbDriverConfig $driver_config
			 */
			$driver_config = new $class_name();
			$driver_config->initialize( $db_config, $data_model_config );

			try {
				$db_config->saveConfigFile();
				$data_model_config->saveConfigFile();
			} catch( Exception $e ) {
				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Tr::COMMON_NAMESPACE ) );
				Http_Headers::reload();
			}


			Installer::goToNext();
		}

		$this->view->setVar( 'form', $select_db_type_form );


		$this->render( 'default' );

	}


	/**
	 * @return array
	 */
	public static function getSelectedBackendType(): array
	{
		$session = Installer::getSession();

		$types = DataModel_Backend::getBackendTypes();

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

		if( !isset( DataModel_Backend::getBackendTypes()[$type] ) ) {
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
		if( static::getSelectedBackendType()['driver']!=Db::DRIVER_SQLITE )
		{
			return [ 'ConfigureDb' ];
		}

		return false;
		*/
	}
}
