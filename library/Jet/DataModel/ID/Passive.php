<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_ID
 */
namespace Jet;

class DataModel_ID_Passive extends DataModel_ID_Abstract {


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