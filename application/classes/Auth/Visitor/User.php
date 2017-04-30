<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;

/**
 *
 * @JetDataModel:database_table_name = 'users_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_User extends Auth_User{
	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
	 * @JetDataModel:is_id = true
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Visitor_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Auth_Visitor_Role', 'getList']
	 *
	 * @var Auth_Visitor_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Visitor_Role[]
	 */
	protected $roles;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public static function generatePassword() {
		srand();
		$password = '';
		$length = rand(5, 9);

		$chars = [
			0,1,2,3,4,5,6,7,8,9,
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
		];

		for($l = 0; $l < $length; $l++) {
			$password .= $chars[rand(1, count($chars))-1];
		}

		return $password;
	}

	/**
	 *
	 */
	public function resetPassword() {

		$password = static::generatePassword();

		$this->setPassword($password);
		$this->setPasswordIsValid(false);
		$this->save();

		Mailing::sendTemplate(
			$this->getEmail(),
			'reset_password_visitor',
			[
				'LOGIN' => $this->getLogin(),
				'PASSWORD' => $password,
				'NAME' => $this->getName(),
				'SURNAME' => $this->getSurname(),
				'EMAIL' => $this->getEmail()
			],
			$this->getLocale()
		);

	}

	/**
	 * @param string $password
	 */
	public function sendWelcomeEmail( $password ) {
		Mailing::sendTemplate(
			$this->getEmail(),
			'welcome_user_visitor',
			[
				'LOGIN' => $this->getLogin(),
				'PASSWORD' => $password,
				'NAME' => $this->getName(),
				'SURNAME' => $this->getSurname(),
				'EMAIL' => $this->getEmail()
			],
			$this->getLocale()
		);
	}

}