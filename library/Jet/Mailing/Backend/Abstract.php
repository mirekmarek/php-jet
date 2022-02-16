<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
abstract class Mailing_Backend_Abstract
{
	/**
	 * @param Mailing_Email $email
	 *
	 * @return bool
	 */
	abstract public function sendEmail( Mailing_Email $email ): bool;
}