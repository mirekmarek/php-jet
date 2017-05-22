<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'BaseObject/Interface.php';
require_once 'BaseObject/Trait.php';
require_once 'BaseObject/Trait/MagicSleep.php';
require_once 'BaseObject/Trait/MagicGet.php';
require_once 'BaseObject/Trait/MagicSet.php';
require_once 'BaseObject/Trait/MagicClone.php';
require_once 'BaseObject/Trait/MagicDebug.php';


/**
 *
 */
class BaseObject implements BaseObject_Interface
{

	use BaseObject_Trait;
	use BaseObject_Trait_MagicSleep;
	use BaseObject_Trait_MagicGet;
	use BaseObject_Trait_MagicSet;
	use BaseObject_Trait_MagicClone;
	use BaseObject_Trait_MagicDebug;

}