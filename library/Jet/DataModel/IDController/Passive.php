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
class DataModel_IDController_Passive extends DataModel_IDController
{


	/**
	 *
	 *
	 */
	public function beforeSave(): void
	{
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	public function afterSave( mixed $backend_save_result ): void
	{
	}

}