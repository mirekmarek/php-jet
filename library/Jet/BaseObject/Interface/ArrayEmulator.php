<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ArrayAccess;
use Iterator;
use Countable;

/**
 *
 */
interface BaseObject_Interface_ArrayEmulator extends ArrayAccess, Iterator, Countable
{
}