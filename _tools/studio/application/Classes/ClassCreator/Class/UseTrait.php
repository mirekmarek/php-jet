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
class ClassCreator_Class_UseTrait extends BaseObject
{
	protected string $trait_class_name = '';
	protected string $conflict_resolution = '';
	
	public function __construct( string $trait_class_name, string $conflict_resolution='' )
	{
		$this->trait_class_name = $trait_class_name;
		$this->conflict_resolution = $conflict_resolution;
	}
	
	public function getTraitClassName(): string
	{
		return $this->trait_class_name;
	}
	
	public function setTraitClassName( string $trait_class_name ): void
	{
		$this->trait_class_name = $trait_class_name;
	}
	
	public function getConflictResolution(): string
	{
		return $this->conflict_resolution;
	}
	
	public function setConflictResolution( string $conflict_resolution ): void
	{
		$this->conflict_resolution = $conflict_resolution;
	}
	
	

	
	/**
	 * @return string
	 */
	public function toString(): string
	{
		$res = '';
		
		$ident = ClassCreator_Config::getIndentation();
		$nl = ClassCreator_Config::getNl();
		
		
		$res .= $nl.$ident . 'use ' . $this->trait_class_name. ($this->conflict_resolution?:';') .$nl;
		
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