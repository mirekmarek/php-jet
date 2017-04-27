<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Available annotation:
 *
 *      Signals:
 *          Signal definition:
 *              @JetApplication_Signals:signal = '/signal/name'
 *              @JetApplication_Signals:signal = '/next/signal/name'
 *
 *          Signal object class name:
 *              @JetApplication_Signals:signal_object_class_name = 'Some\Custom_Class_Name'
 *
 */


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