<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

class Auth_User_Signal extends Application_Signals_Signal {

	/**
	 *
	 * @param Auth_User_Interface $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Auth_User_Interface $sender, $name, array $data= []) {
		parent::__construct($sender, $name, $data);
	}

	/**
	 *
	 * @return Auth_User_Interface
	 */
	public function getSender(){
		$sender = $this->sender;
		/**
		 * @var Auth_User_Interface $sender
		 */

		return $sender;
	}

	/**
	 * @return Auth_User_Interface
	 */
	public function getUser() {
		return $this->data['user'];
	}

}