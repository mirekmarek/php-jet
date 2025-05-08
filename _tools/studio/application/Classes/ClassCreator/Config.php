<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

class ClassCreator_Config
{
	protected static bool $add_doc_blocks_always = false;
	protected static bool $prefer_property_hooks = false;
	
	public static function getAddDocBlocksAlways(): bool
	{
		return static::$add_doc_blocks_always;
	}
	
	public static function setAddDocBlocksAlways( bool $add_doc_blocks_always ): void
	{
		static::$add_doc_blocks_always = $add_doc_blocks_always;
	}
	
	public static function getPreferPropertyHooks(): bool
	{
		if( PHP_VERSION_ID < 80400 ) {
			return false;
		}
		
		return static::$prefer_property_hooks;
	}
	
	public static function setPreferPropertyHooks( bool $prefer_property_hooks ): void
	{
		static::$prefer_property_hooks = $prefer_property_hooks;
	}
	
	
	
}