<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class Mailing_Backend_Default extends Mailing_Backend_Abstract{

	/**
	 * @param Mailing_Email $email
	 * @param string $to
	 * @param array $headers
	 *
	 * @return bool
	 */
	public function sendEmail( Mailing_Email $email, string $to, array $headers=[] ) : bool
	{

		$sender = $email->getSender();
		$subject = $email->getSubject();

		$boundary_1 = uniqid( 'MP' );
		$boundary_2 = $boundary_1.'SP1';
		$boundary_3 = $boundary_1.'SP2';

		$eol = PHP_EOL;

		$headers['From'] = $sender->getName()."<".$sender->getEmail().">";
		$headers['Subject'] = $subject;
		$headers['Reply-To'] = $sender->getEmail();


		$header = "";
		foreach( $headers as $h=> $v ) {
			$header .= $h.": ".mb_encode_mimeheader($v).$eol;
		}

		$header .= "MIME-Version: 1.0".$eol;
		$header .= "Content-Type: multipart/mixed; boundary=".$boundary_1.";".$eol;


		$message = "This is a MIME encoded message.".$eol;
		$message .= $eol."--$boundary_1".$eol;
			$message .= "Content-Type: multipart/related; boundary=".$boundary_2.";".$eol;
			$message .= $eol."--$boundary_2".$eol;
				$message .= "Content-Type: multipart/alternative; boundary=".$boundary_3.";".$eol;
				$message .= $eol."--$boundary_3".$eol;

					$message .= "Content-type: text/plain;charset=utf-8".$eol;
					$message .= $eol;
					$message .= $email->getBodyTxt().$eol;


				$message .= $eol."--$boundary_3".$eol;


					$message .= "Content-type: text/html;charset=utf-8".$eol;
					$message .= $eol;
					$message .= $email->getBodyHtml().$eol;

				$message .= $eol."--$boundary_3--".$eol;

					foreach( $email->getImages() as $image_id=> $image_path ) {
						/** @noinspection PhpUsageOfSilenceOperatorInspection */
						$image_info = @getimagesize( $image_path );
						if(!$image_info) {
							continue;
						}

						$filename = basename($image_path);

						$message .= $eol."--$boundary_2".$eol;
						$message .= 'Content-type: '.$image_info['mime'].$eol;
						$message .= 'Content-ID: <'.$image_id.">".$eol;
						$message .= 'Content-Transfer-Encoding: base64'.$eol;
						$message .= 'Content-Disposition: inline; filename="'.mb_encode_mimeheader($filename).'""'.$eol;
						$message .= $eol;
						$message .= chunk_split( base64_encode( IO_File::read($image_path) ) );

					}

			$message .= "--$boundary_2--".$eol;

			foreach( $email->getAttachments() as $file_path=> $filename ) {
				$message .= $eol."--$boundary_1".$eol;
				$message .= 'Content-Type: application/octet-stream; name="' . mb_encode_mimeheader($filename). '"' . $eol;
				$message .= 'Content-Transfer-Encoding: base64' . $eol;
				$message .= 'Content-Disposition: attachment' . $eol;
				$message .= chunk_split( base64_encode( IO_File::read($file_path) ) );
			}


		$message .= "--$boundary_1--".$eol;

		return mail( $to, $subject, $message, $header );

	}

}