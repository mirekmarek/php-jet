<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_sqlite {
	
	protected function sqlite_getDnsEntries() : array
	{
		return [$this->path];
	}
	
	protected function sqlite_getEntriesSchema() : array
	{
		return ['path'=>SysConf_Path::getData() . 'database.sq3'];
	}
	
}