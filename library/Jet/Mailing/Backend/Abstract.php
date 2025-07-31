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
	
	/**
	 * @param Mailing_Email $email
	 * @param-out string $message
	 * @param-out string $header
	 * @noinspection PhpDocSignatureIsNotCompleteInspection
	 */
	public function prepareMessage( Mailing_Email $email, ?string &$message, ?string &$header ) : void
	{
		
		$mixed_part = new Mailing_MIME_Part();
		$mixed_part->setContentType('multipart/mixed');
		$mixed_part->setBoundary(uniqid('mixed_boundary'));
		
		$related_part = new Mailing_MIME_Part();
		$related_part->setContentType('multipart/related');
		$related_part->setBoundary(uniqid('related_boundary'));
		
		$alternative_part = new Mailing_MIME_Part();
		$alternative_part->setContentType('multipart/alternative');
		$alternative_part->setBoundary(uniqid('alternative_boundary'));
		
		$txt_part = new Mailing_MIME_Part();
		$txt_part->setContentType('text/plain');
		$txt_part->setCharset('utf-8');
		$txt_part->setEncoding('base64');
		$txt_part->setBody( chunk_split( base64_encode( $email->getBodyTxt() ) ) );
		
		$html_part = new Mailing_MIME_Part();
		$html_part->setContentType('text/html');
		$html_part->setCharset('utf-8');
		$html_part->setEncoding('base64');
		$html_part->setBody( chunk_split( base64_encode( $email->getBodyHtml() ) ) );
		
		
		$image_parts = [];
		foreach( $email->getImages() as $image ) {
			
			$mime_type = $image->getFileMiteType();
			
			$filename = $image->getFileName();
			
			$image_part = new Mailing_MIME_Part();
			
			$image_part->setContentType( $mime_type );
			$image_part->setEncoding('base64');
			$image_part->setDisposition('inline');
			
			$image_part->setId( $image->getId() );
			$image_part->setFilename( mb_encode_mimeheader( $filename ) );
			$image_part->setBody( chunk_split( base64_encode( $image->getData() ) ) );
			
			$image_parts[] = $image_part;
		}
		
		$attachments_parts = [];
		foreach( $email->getAttachments() as $file ) {
			
			$attachments_part = new Mailing_MIME_Part();
			
			$attachments_part->setContentType('application/octet-stream');
			$attachments_part->setEncoding('base64');
			$attachments_part->setDisposition('attachment');
			
			$attachments_part->setName( mb_encode_mimeheader( $file->getFileName() ) );
			$attachments_part->setFilename( mb_encode_mimeheader( $file->getFileName() ) );
			$attachments_part->setBody( chunk_split( base64_encode( $file->getData() ) ) );
			
			$attachments_parts[] = $attachments_part;
		}
		
		$mixed_part->addPart( $alternative_part );
			$alternative_part->addPart($txt_part);
			$alternative_part->addPart($html_part);
			if($image_parts) {
				$mixed_part->addPart($related_part);
				
				foreach($image_parts as $image_part) {
					$related_part->addPart( $image_part );
				}
			}
		
		foreach($attachments_parts as $attachments_part) {
			$mixed_part->addPart( $attachments_part );
		}
		
		$root_part = $mixed_part;
		$root_part->setIsRoot(true);
		
		
		
		
		
		$subject = $email->getSubject();
		
		$eol = Mailing_MIME_Part::$eol;
		
		if($email->getSenderName()) {
			$headers['From'] = mb_encode_mimeheader($email->getSenderName() . '<' . $email->getSenderEmail() . '>' );
		} else {
			$headers['From'] = mb_encode_mimeheader($email->getSenderEmail());
		}
		//$headers['Subject'] = mb_encode_mimeheader($subject);
		$headers['Reply-To'] = mb_encode_mimeheader($email->getSenderEmail());
		$headers['MIME-Version'] = '1.0';
		
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
		
		$headers['Content-Type'] = $root_part->getContentType().'; boundary="' . $root_part->getBoundary() . '";';
		
		
		$header = '';
		foreach( $headers as $h => $v ) {
			$header .= $h . ': ' . $v . $eol;
		}
		
		
		$message = $mixed_part->toString();
	}
	
}