<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Backend_SQLite_Config extends DataModel_Backend_Config
{

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Backend_SQLite_Config', 'getDbConnectionsList']
	 * @JetConfig:form_field_label = 'Connection: '
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please select database connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select database connection']
	 *
	 * @var string
	 */
	protected $connection = '';

	/**
	 * @return array
	 */
	public static function getDbConnectionsList()
	{
		return Db_Config::getConnectionsList( Db::DRIVER_SQLITE );
	}

	/**
	 * @return string
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * @param string $connection
	 */
	public function setConnection( $connection )
	{
		$this->connection = $connection;
	}

}