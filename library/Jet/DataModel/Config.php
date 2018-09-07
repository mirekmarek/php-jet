<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetConfig:name = 'data_model'
 */
class DataModel_Config extends Config
{

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Config', 'getBackendTypesList']
	 * @JetConfig:form_field_label = 'Default backend type: '
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please select backend type', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select backend type']
	 *
	 * @var string
	 */
	protected $backend_type;


	/**
	 * @JetConfig:type = Config::TYPE_SECTION
	 * @JetConfig:is_required = true
	 * @JetConfig:section_creator_method_name = 'createBackendConfigInstance'
	 *
	 * @var DataModel_Backend_Config
	 */
	protected $backend_config;



	/**
	 * @return array
	 */
	public static function getBackendTypesList()
	{
		return static::getAvailableHandlersList( JET_PATH_LIBRARY.'Jet/DataModel/Backend/' );
	}

	/**
	 *
	 * @param string $base_directory
	 *
	 * @return array
	 */
	public static function getAvailableHandlersList( $base_directory )
	{
		$res = IO_Dir::getSubdirectoriesList( $base_directory, '*' );
		foreach( $res as $path => $dir ) {
			if( $dir=='Config' ) {
				unset( $res[$path] );
			}
		}

		return array_combine( $res, $res );
	}


	/**
	 * @return string
	 */
	public function getBackendType()
	{
		return $this->backend_type;
	}

	/**
	 * @param string $backend_type
	 */
	public function setBackendType( $backend_type )
	{
		$this->backend_type = $backend_type;
		$this->backend_config = $this->createBackendConfigInstance([]);
	}

	/**
	 * @return DataModel_Backend_Config
	 */
	public function getBackendConfig()
	{
		return $this->backend_config;
	}


	/**
	 * @param array $data
	 *
	 * @return DataModel_Backend_Config
	 */
	public function createBackendConfigInstance( array $data )
	{
		return DataModel_Factory::getBackendConfigInstance(
			$this->getBackendType(),
			$data
		);
	}


}