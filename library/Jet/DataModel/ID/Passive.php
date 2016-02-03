<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @param DataModel_Interface $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 * @throws DataModel_Exception
	 */
	public function generate( DataModel_Interface $data_model_instance, $called_after_save = false, $backend_save_result = null ) {
	}

	/**
	 *
	 */
	public function reset() {
	}

}