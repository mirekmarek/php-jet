<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_sqlite {
	
	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Path:',
		is_required: false
	)]
	protected string $path = '';
	
	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}
	
	/**
	 * @param string $path
	 */
	public function setPath( string $path ): void
	{
		$this->path = $path;
	}

	
	protected function sqlite_getDnsEntries() : array
	{
		return [$this->path];
	}
	
	protected function sqlite_getEntriesSchema() : array
	{
		return ['path'=>SysConf_Path::getData() . uniqid().'.sq3'];
	}
	
}