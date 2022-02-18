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
class Mailing_Backend_Default extends Mailing_Backend_Abstract
{

	/**
	 * @param Mailing_Email $email
	 *
	 * @return bool
	 */
	public function sendEmail( Mailing_Email $email ): bool
	{

		$subject = $email->getSubject();
		
		$email->prepareMessage( $message, $header );

		$to = $email->getTo();

		if(is_array($to)) {
			$to = implode(', ', $to);
		}

		return mail( $to, $subject, $message, $header );

	}
}