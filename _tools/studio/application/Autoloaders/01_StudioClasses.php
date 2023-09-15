<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Autoloader_Loader;
use Jet\SysConf_Path;

/**
 *
 */
return new class extends Autoloader_Loader
{
	public const MAIN_ROOT_NAMESPACE = 'JetStudio\\';
	public const WIZARDS_ROOT_NAMESPACE = 'JetStudio\\ModuleWizard\\';
	
	/**
	 * @return string
	 */
	public function getAutoloaderName() : string
	{
		return 'JetStudio/Classes';
	}
	
	/**
	 *
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	public function getScriptPath( string $class_name ): bool|string
	{
		if(
			!str_starts_with( $class_name, static::MAIN_ROOT_NAMESPACE ) ||
			str_starts_with( $class_name, static::WIZARDS_ROOT_NAMESPACE )
		) {
			return false;
		}

		return SysConf_Path::getApplication() . 'Classes/' . $this->classNameToPath( substr($class_name, strlen(static::MAIN_ROOT_NAMESPACE)) );

	}
};