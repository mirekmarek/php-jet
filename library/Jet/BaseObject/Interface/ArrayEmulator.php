<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ArrayAccess;
use Iterator;
use Countable;

/** @phpstan-ignore missingType.generics, missingType.generics */
interface BaseObject_Interface_ArrayEmulator extends
	ArrayAccess,
	Iterator,
	Countable
{
}