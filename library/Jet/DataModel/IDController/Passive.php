<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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