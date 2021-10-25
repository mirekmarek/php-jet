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
	 *
	 * @return bool
	 */
	public function sendEmail( Mailing_Email $email): bool
	{

		$subject = $email->getSubject();

		static::prepareMessage( $email, $message, $header );

		$to = $email->getTo();

		if(is_array($to)) {
			$to = implode(', ', $to);
		}

		return mail( $to, $subject, $message, $header );

	}

	/**
	 * @param Mailing_Email $email
	 * @param string|null $message
	 * @param string|null $header
	 *
	 * @throws IO_File_Exception
	 */
	public static function prepareMessage( Mailing_Email $email, ?string &$message, ?string &$header ) : void
	{

		$subject = $email->getSubject();

		$boundary_1 = uniqid( 'MP' );
		$boundary_2 = $boundary_1 . 'SP1';
		$boundary_3 = $boundary_1 . 'SP2';

		$eol = PHP_EOL;

		if($email->getSenderName()) {
			$headers['From'] = mb_encode_mimeheader($email->getSenderName() . "<" . $email->getSenderEmail() . ">");
		} else {
			$headers['From'] = mb_encode_mimeheader($email->getSenderEmail());
		}
		$headers['Subject'] = mb_encode_mimeheader($subject);
		$headers['Reply-To'] = mb_encode_mimeheader($email->getSenderEmail());
		$headers['MIME-Version'] = '1.0';
		$headers['Content-Type'] = 'multipart/mixed; boundary=' . $boundary_1 . ';';

		foreach($email->getCustomHeaders() as $h=>$v ) {
			$headers[$h] = $v;
		}

		if($email->getToCopy()) {
			$cc = $email->getToCopy();

			if(is_array($cc)) {
				$cc = implode(', ', $cc);
			}

			$headers['Cc'] = $cc;
		}

		if($email->getToCopy()) {
			$bcc = $email->getToHiddenCopy();

			if(is_array($bcc)) {
				$bcc = implode(', ', $bcc);
			}

			$headers['Bcc'] = $bcc;
		}

		$header = '';
		foreach( $headers as $h => $v ) {
			$header .= $h . ': ' . $v . $eol;
		}




		$message = "This is a MIME encoded message." . $eol;
		$message .= $eol . "--$boundary_1" . $eol;
		$message .= "Content-Type: multipart/related; boundary=" . $boundary_2 . ";" . $eol;
		$message .= $eol . "--$boundary_2" . $eol;
		$message .= "Content-Type: multipart/alternative; boundary=" . $boundary_3 . ";" . $eol;
		$message .= $eol . "--$boundary_3" . $eol;

		$message .= "Content-type: text/plain;charset=utf-8" . $eol;
		$message .= $eol;
		$message .= $email->getBodyTxt() . $eol;


		$message .= $eol . "--$boundary_3" . $eol;


		$message .= "Content-type: text/html;charset=utf-8" . $eol;
		$message .= $eol;
		$message .= $email->getBodyHtml() . $eol;

		$message .= $eol . "--$boundary_3--" . $eol;

		foreach( $email->getImages() as $image_id => $image_path ) {
			$image_info = @getimagesize( $image_path );
			if( !$image_info ) {
				continue;
			}

			$filename = basename( $image_path );

			$message .= $eol . "--$boundary_2" . $eol;
			$message .= 'Content-type: ' . $image_info['mime'] . $eol;
			$message .= 'Content-ID: <' . $image_id . ">" . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol;
			$message .= 'Content-Disposition: inline; filename="' . mb_encode_mimeheader( $filename ) . '""' . $eol;
			$message .= $eol;
			$message .= chunk_split( base64_encode( IO_File::read( $image_path ) ) );

		}

		$message .= "--$boundary_2--" . $eol;

		foreach( $email->getAttachments() as $file_path => $filename ) {
			$message .= $eol . "--$boundary_1" . $eol;
			$message .= 'Content-Type: application/octet-stream; name="' . mb_encode_mimeheader( $filename ) . '"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol;
			$message .= 'Content-Disposition: attachment' . $eol;
			$message .= chunk_split( base64_encode( IO_File::read( $file_path ) ) );
		}


		$message .= "--$boundary_1--" . $eol;
	}

}