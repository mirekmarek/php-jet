<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

interface JetStudio_Module_Service_Menus
{
	public function editMenuItem( string $set_id, string $menu_id, string $item_id ): string;
}