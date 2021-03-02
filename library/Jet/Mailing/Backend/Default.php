<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
class Mailing_Backend_Default extends Mailing_Backend_Abstract
{

	/**
	 * @param Mailing_Email $email
	 * @param string $to
	 *
	 * @return bool
	 */
	public function sendEmail( Mailing_Email $email, string $to ): bool
	{

		$subject = $email->getSubject();

		static::prepareMessage( $email, $message, $header );

		return mail( $to, $subject, $message, $header );

	}

}