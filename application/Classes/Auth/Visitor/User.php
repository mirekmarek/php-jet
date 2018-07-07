<?php
namespace JetApplication;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\Form;
use Jet\Mailing_Email;

/**
 *
 * @JetDataModel:database_table_name = 'users_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_User extends Auth_User
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
	 * @JetDataModel:data_model_class = 'Auth_Visitor_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Auth_Visitor_Role', 'getList']
	 *
	 * @var Auth_Visitor_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Visitor_Role[]
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

		$email = new Mailing_Email(
			'user_password_reset',
			$this->getLocale(),
			Application_Web::getSiteId()
		);

		$email->setVar('user', $this);
		$email->setVar('password', $password);

		$email->send( $this->getEmail() );

	}

	/**
	 * @return string
	 */
	public static function generatePassword()
	{
		srand();
		$password = '';
		$length = rand( 5, 9 );

		$chars = [
			0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
			'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
			'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
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
		$email = new Mailing_Email(
			'user_welcome',
			$this->getLocale(),
			Application_Web::getSiteId()
		);

		$email->setVar('user', $this);
		$email->setVar('password', $password);

		$email->send( $this->getEmail() );
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

			$form->getField('locale')->setSelectOptions( $this->_getLocales() );

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
			$this->_form_add = parent::getForm('add_user');

			$this->_form_add->getField('locale')->setSelectOptions( $this->_getLocales() );

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


	/**
	 * @return array
	 */
	protected function _getLocales()
	{
		$locales = [];

		foreach( Application_Web::getSite()->getLocales() as $locale_str=>$locale ) {
			$locales[$locale_str] = $locale->getName();
		}

		return $locales;
	}

}