<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;
use JetExampleApp\Mailing;

/**
 *
 * @JetDataModel:database_table_name = 'users_administrators'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Administrator_User extends Auth_User{

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
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Administrator_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['JetExampleApp\Auth_Administrator_Role', 'getList']
	 *
	 * @var Auth_Administrator_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Administrator_Role[]
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
	 *
	 */
	public function resetPassword() {

		$password = '';
		$length = rand(8, 12);

		for($l = 0; $l < $length; $l++) {
			$password .= chr(rand(32, 126));
		}

		$this->setPassword($password);
		$this->setPasswordIsValid(false);
		$this->save();

		Mailing::sendTemplate(
			$this->getEmail(),
			'reset_password_user_administrator',
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
	 *
	 */
	public function sendWelcomeEmail() {
		Mailing::sendTemplate(
			$this->getEmail(),
			'welcome_user_administrator',
			[
				'LOGIN' => $this->getLogin(),
				'NAME' => $this->getName(),
				'SURNAME' => $this->getSurname(),
				'EMAIL' => $this->getEmail()
			],
			$this->getLocale()
		);
	}

}