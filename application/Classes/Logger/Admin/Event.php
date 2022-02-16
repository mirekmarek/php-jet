<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\DataModel_Definition;

/**
 *
 */
#[DataModel_Definition(
	database_table_name: 'events_administration'
)]
class Logger_Admin_Event extends Logger_Event
{
}