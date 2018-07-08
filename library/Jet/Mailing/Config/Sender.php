<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * @JetConfig:name = 'mailing'
 *
 */
class Mailing_Config_Sender extends Config_Section
{

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 *
	 * @JetConfig:form_field_label = 'E-mail:'
	 * @JetConfig:form_field_type = Form::TYPE_EMAIL
	 * @JetConfig:form_field_error_messages = [Form_Field_Email::ERROR_CODE_EMPTY => 'Please enter valid email address',Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please enter valid email address']
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * @JetConfig:form_field_label = 'Name:'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email )
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}


}