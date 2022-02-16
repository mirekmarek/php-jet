<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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