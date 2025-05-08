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
	
	public static function getAddDocBlocksAlways(): bool
	{
		return static::$add_doc_blocks_always;
	}
	
	public static function setAddDocBlocksAlways( bool $add_doc_blocks_always ): void
	{
		static::$add_doc_blocks_always = $add_doc_blocks_always;
	}
	
	
}