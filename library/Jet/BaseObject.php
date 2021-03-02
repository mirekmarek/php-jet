<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'BaseObject/Interface.php';
require_once 'BaseObject/Trait.php';

/**
 *
 */
abstract class BaseObject implements BaseObject_Interface
{

	use BaseObject_Trait;
}