<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_another
{
	
	protected function another_getEntriesSchema(): array
	{
		return [
			'username'    => '',
			'password'    => '',
			'dsn'        => '',
		];
	}
	
}