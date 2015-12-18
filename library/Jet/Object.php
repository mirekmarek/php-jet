<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
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
 *      Factory: @see Factory
 *          @JetFactory:class = 'Some\Factory_Class'
 *                     - optional
 *          @JetFactory:method = 'someMethodName'
 *                     - optional
 *          @JetFactory:mandatory_parent_class = 'Some_Mandatory_Abstract_Class'
 */


/**
 * Class Object
 *
 */
class Object implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicSleep;
	use Object_Trait_MagicGet;
	use Object_Trait_MagicSet;
	use Object_Trait_MagicClone;

}