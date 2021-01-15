<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;


/**
 *
 */
abstract class Mailing_Backend_Abstract
{

	/**
	 * @param Mailing_Email $email
	 * @param string $to
	 * @param array $headers
	 *
	 * @return bool
	 */
	abstract public function sendEmail( Mailing_Email $email, string $to, array $headers = [] ): bool;

}