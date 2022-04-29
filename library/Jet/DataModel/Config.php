<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Default backend type: ',
		select_options_creator: [
			DataModel_Backend::class,
			'getAvlBackendTypes'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select backend type',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select backend type'
		]
		
	)]
	protected string $backend_type = DataModel_Backend::TYPE_MYSQL;


	/**
	 *
	 * @var DataModel_Backend_Config
	 */
	#[Config_Definition(
		type: Config::TYPE_SECTION,
		is_required: true,
		section_creator_method_name: 'createBackendConfigInstance'
	)]
	protected DataModel_Backend_Config $backend_config;

	
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
		return Factory_DataModel::getBackendConfigInstance(
			$this->getBackendType(),
			$data
		);
	}


}