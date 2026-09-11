<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;

/**
 *
 */
class ClassCreator_Class_ImplementsInterface extends BaseObject
{
	protected string $interface_name = '';
	
	public function __construct( string $interface_name )
	{
		$this->interface_name = $interface_name;
	}
	
	public function getInterfaceName(): string
	{
		return $this->interface_name;
	}
	
	public function setInterfaceName( string $interface_name ): void
	{
		$this->interface_name = $interface_name;
	}
	
	
	/**
	 * @return string
	 */
	public function toString(): string
	{
		$res = '';
		
		$ident = ClassCreator_Config::getIndentation();
		$nl = ClassCreator_Config::getNl();
		
		
		$res .= $this->interface_name;
		
		return $res;
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}
	
	
}