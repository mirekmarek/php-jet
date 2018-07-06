<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Config;
use Jet\Form;
use Jet\Config_Section;

/**
 * @JetConfig:name = 'mailing'
 *
 */
class Mailing_Config_Sender extends Config_Section
{

	/**
	 * @JetConfig:form_field_label = 'E-mail:'
	 * @JetConfig:form_field_type = Form::TYPE_EMAIL
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
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