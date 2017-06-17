<?php
namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;
use Jet\Form;
use Jet\Locale;

/**
 *
 * @JetDataModel:database_table_name = 'users_administrators'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Administrator_User extends Auth_User
{

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
	 * @JetDataModel:data_model_class = 'Auth_Administrator_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Auth_Administrator_Role', 'getList']
	 *
	 * @var Auth_Administrator_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Administrator_Role[]
	 */
	protected $roles;


	/**
	 * @var Form
	 */
	protected $_form_add;
	/**
	 * @var Form
	 */
	protected $_form_edit;

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
	public function resetPassword()
	{

		$password = static::generatePassword();

		$this->setPassword( $password );
		$this->setPasswordIsValid( false );
		$this->save();

		Mailing::sendTemplate(
			$this->getEmail(), 'reset_password_administrator', [
			'USERNAME' => $this->getUsername(), 'PASSWORD' => $password, 'NAME' => $this->getName(),
			'SURNAME'  => $this->getSurname(), 'EMAIL' => $this->getEmail(),
		], $this->getLocale()
		);

	}

	/**
	 * @return string
	 */
	public static function generatePassword()
	{
		srand();
		$password = '';
		$length = rand( 8, 12 );

		$chars = [
			0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
			'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
			'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '@', '#', '$', '%', '&',
			'*',
		];

		for( $l = 0; $l<$length; $l++ ) {
			$password .= $chars[rand( 1, count( $chars ) )-1];
		}

		return $password;
	}

	/**
	 * @param string $password
	 */
	public function sendWelcomeEmail( $password )
	{
		Mailing::sendTemplate(
			$this->getEmail(), 'welcome_user_administrator', [
			'USERNAME' => $this->getUsername(), 'PASSWORD' => $password, 'NAME' => $this->getName(),
			'SURNAME'  => $this->getSurname(), 'EMAIL' => $this->getEmail(),
		], $this->getLocale()
		);
	}


	/**
	 *
	 * @return Form
	 */
	public function getRegistrationForm()
	{
		$form = parent::getForm('register_user');

		foreach( $form->getFields() as $field ) {
			if( !in_array( $field->getName(), [ 'username', 'locale', 'password', 'email' ] ) ) {
				$form->removeField( $field->getName() );
			}
		}

		$form->getField( 'locale' )->setDefaultValue( Locale::getCurrentLocale() );

		return $form;
	}


	/**
	 * @param string $password
	 *
	 * @return bool
	 */
	public function verifyPasswordStrength( $password )
	{
		if( strlen( $password )<5 ) {
			return false;
		}

		return true;
	}


	/**
	 *
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$form = parent::getForm('edit_user');

			if( $form->fieldExists( 'password' ) ) {
				$form->removeField( 'password' );
			}

			$this->_form_edit = $form;
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {

			$form = parent::getForm('add_user');
			if( $form->fieldExists( 'password' ) ) {
				$form->removeField( 'password' );
			}

			$this->_form_add = $form;


		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}

}