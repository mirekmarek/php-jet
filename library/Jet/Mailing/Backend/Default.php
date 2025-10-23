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

		
		
		
		$this->prepareMessage( $email, $message, $header );

		$to = $email->getTo();

		if(is_array($to)) {
			$to = implode(', ', $to);
		}
		
		$subject = $email->getSubject();
		
		$i_encoding = mb_internal_encoding();
		mb_internal_encoding('UTF-8');
		$encoded_subject = mb_encode_mimeheader("Subject: $subject", 'UTF-8');
		$encoded_subject = substr($encoded_subject, strlen('Subject: '));
		mb_internal_encoding($i_encoding);
		
		return mail( $to, $encoded_subject, $message, $header, '-f '.$email->getSenderEmail() );

	}
}