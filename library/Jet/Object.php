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

// We do not have multiple inheritance in PHP :-(
class Object implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicSleep;
	use Object_Trait_MagicGet;
	use Object_Trait_MagicSet;

	/**
	 * @var string|null
	 */
	protected static $__factory_class_name = null;

	/**
	 * @var string|null
	 */
	protected static $__factory_class_method_name = null;

	/**
	 * @var string|null
	 */
	protected static $__factory_must_be_instance_of_class_name = null;

	/**
	 * Signals list
	 *
	 * array(
	 *      "/my_signal_group/signal1",
	 *      "/my_signal_group/signal2"
	 * )
	 *
	 *
	 * @var array
	 */
	protected static $__signals = array();

	/**
	 * @var string
	 */
	protected $__signals_signal_object_class_name = Application_Signals::DEFAULT_SIGNAL_OBJECT_CLASS_NAME;

}