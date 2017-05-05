<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Application_Config
 *
 */
class Application_Config extends Config
{

	/**
	 * Create main config instance
	 *
	 * @param bool $soft_mode (optional, default: false)
	 */
	public function __construct( $soft_mode = false )
	{
		parent::__construct( Application::getConfigFilePath(), $soft_mode );
	}

}