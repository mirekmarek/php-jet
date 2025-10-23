<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Auth_AvailablePrivilegeProvider
{
	
	protected string $privilege;
	
	protected string $label;
	
	/**
	 * @var callable
	 */
	protected $options_getter;
	
	public function __construct( string $privilege, string $label, callable $options_getter )
	{
		$this->privilege = $privilege;
		$this->label = $label;
		$this->options_getter = $options_getter;
	}
	
	public function getPrivilege(): string
	{
		return $this->privilege;
	}
	
	public function getLabel(): string
	{
		return $this->label;
	}
	
	public function getOptionsGetter(): callable
	{
		return $this->options_getter;
	}
	
	public function getOptions() : mixed
	{
		$getter = $this->options_getter;
		return $getter();
	}
	
}