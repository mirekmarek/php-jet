<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Auth_Role_Signal
 * @package Jet
 */
class Auth_Role_Signal extends Application_Signals_Signal {

	/**
	 *
	 * @param Auth_Role_Interface $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Auth_Role_Interface $sender, $name, array $data= []) {
		parent::__construct($sender, $name, $data);
	}

	/**
	 *
	 * @return Auth_Role_Interface
	 */
	public function getSender(){
		/**
		 * @var Auth_Role_Interface $sender
		 */
		$sender = $this->sender;
		return $sender;
	}

	/**
	 * @return Auth_Role_Interface
	 */
	public function getRole() {
		return $this->data['role'];
	}

}