<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface BaseObject_Serializable_JSON
 * @package Jet
 */
interface BaseObject_Serializable_JSON extends \JsonSerializable {
	/**
	 * @return string
	 */
	public function toJSON();
}