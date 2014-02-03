<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Auth_Role_ID_Default extends Auth_Role_ID_Abstract {

	/**
	 *
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 */
	public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null ) {

		if(!$this->values['ID']) {
			/**
			 * @var Auth_Role_Abstract $data_model_instance
			 */
			$this->generateNameID( $data_model_instance, 'ID', $data_model_instance->getName() );
		}

	}

}