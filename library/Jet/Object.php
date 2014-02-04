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
 * //TODO: update comment
 *
 * Signals list
 *
 * array(
 *      '/my_signal_group/signal1',
 *      '/my_signal_group/signal2'
 * )
 *
 *
 * @var array
 */

/**
 * //TODO: update comment signals_signal_object_class_name
 *
 * @var string
 */


// We do not have multiple inheritance in PHP :-(
class Object implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicSleep;
	use Object_Trait_MagicGet;
	use Object_Trait_MagicSet;

}