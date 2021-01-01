<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Definition;

/**
 *
 */
#[DataModel_Definition(database_table_name: 'events_rest')]
class Application_Logger_REST_Event extends Application_Logger_Event
{
}