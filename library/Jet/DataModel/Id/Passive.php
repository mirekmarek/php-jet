<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Id_Passive
 * @package Jet
 */
class DataModel_Id_Passive extends DataModel_Id_Abstract {


	/**
	 *
	 *
	 * @throws DataModel_Exception
	 */
	public function generate() {
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	public function afterSave($backend_save_result)
	{
	}

}