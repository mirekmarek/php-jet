<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use Iterator as PHP_Iterator;
use Countable as PHP_Countable;

/**
 *
 */
interface BaseObject_IteratorCountable extends PHP_Iterator, PHP_Countable
{
}