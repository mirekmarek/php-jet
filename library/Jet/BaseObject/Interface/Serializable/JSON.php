<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

use JsonSerializable;

/**
 *
 */
interface BaseObject_Interface_Serializable_JSON extends JsonSerializable
{
	/**
	 * @return string
	 */
	public function toJSON(): string;
}