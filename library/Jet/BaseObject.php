<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class BaseObject implements BaseObject_Interface {

	use BaseObject_Trait;
	use BaseObject_Trait_MagicSleep;
	use BaseObject_Trait_MagicGet;
	use BaseObject_Trait_MagicSet;
	use BaseObject_Trait_MagicClone;
	use BaseObject_Trait_MagicDebug;

}