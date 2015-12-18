<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

class Auth_Role_Signal extends Application_Signals_Signal {

	/**
	 *
	 * @param Auth_Role_Abstract $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Auth_Role_Abstract $sender, $name, array $data= []) {
		parent::__construct($sender, $name, $data);
	}

	/**
	 *
	 * @return Auth_Role_Abstract
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 * @return Auth_Role_Abstract
	 */
	public function getRole() {
		return $this->data['role'];
	}

}