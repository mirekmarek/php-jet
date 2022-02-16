<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition]
class Mailing_Config_Sender extends Config_Section
{

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_EMAIL,
		label: 'E-mail:',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter valid email address',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter valid email address'
		]
	)]
	protected string $email = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Name:',
		is_required: false,
	)]
	protected string $name = '';

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( string $email ): void
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}


}