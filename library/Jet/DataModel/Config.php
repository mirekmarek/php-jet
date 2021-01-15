<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition(name: 'data_model')]
class DataModel_Config extends Config
{

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(default_value: 'MySQL')]
	#[Config_Definition(form_field_type: Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback: [
		'DataModel_Config',
		'getBackendTypesList'
	])]
	#[Config_Definition(form_field_label: 'Default backend type: ')]
	#[Config_Definition(form_field_error_messages: [
		Form_Field::ERROR_CODE_EMPTY => 'Please select backend type',
		Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select backend type'
	])]
	protected string $backend_type;


	/**
	 *
	 * @var DataModel_Backend_Config
	 */
	#[Config_Definition(type: Config::TYPE_SECTION)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(section_creator_method_name: 'createBackendConfigInstance')]
	protected DataModel_Backend_Config $backend_config;


	/**
	 * @return array
	 */
	public static function getBackendTypesList(): array
	{
		return static::getAvailableHandlersList( __DIR__ . '/Backend/' );
	}

	/**
	 *
	 * @param string $base_directory
	 *
	 * @return array
	 */
	public static function getAvailableHandlersList( string $base_directory ): array
	{
		$res = IO_Dir::getSubdirectoriesList( $base_directory );
		foreach( $res as $path => $dir ) {
			if( $dir == 'Config' ) {
				unset( $res[$path] );
			}
		}

		return array_combine( $res, $res );
	}


	/**
	 * @return string
	 */
	public function getBackendType(): string
	{
		return $this->backend_type;
	}

	/**
	 * @param string $backend_type
	 */
	public function setBackendType( string $backend_type ): void
	{
		$this->backend_type = $backend_type;
		$this->backend_config = $this->createBackendConfigInstance( [] );
	}

	/**
	 * @return DataModel_Backend_Config
	 */
	public function getBackendConfig(): DataModel_Backend_Config
	{
		return $this->backend_config;
	}


	/**
	 * @param array $data
	 *
	 * @return DataModel_Backend_Config
	 */
	public function createBackendConfigInstance( array $data ): DataModel_Backend_Config
	{
		return DataModel_Factory::getBackendConfigInstance(
			$this->getBackendType(),
			$data
		);
	}


}