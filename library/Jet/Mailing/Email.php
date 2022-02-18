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
class Mailing_Email extends BaseObject
{

	/**
	 * @var string|array
	 */
	protected string|array $to;

	/**
	 * @var string|array
	 */
	protected string|array $to_copy = '';

	/**
	 * @var string|array
	 */
	protected string|array $to_hidden_copy = '';

	/**
	 * @var string
	 */
	protected string $sender_name = '';

	/**
	 * @var string
	 */
	protected string $sender_email;

	/**
	 * @var array
	 */
	protected array $attachments = [];

	/**
	 * @var array
	 */
	protected array $images = [];


	/**
	 * @var string
	 */
	protected string $subject = '';

	/**
	 * @var string
	 */
	protected string $body_html = '';

	/**
	 * @var string
	 */
	protected string $body_txt = '';

	/**
	 * @var array
	 */
	protected array $custom_headers = [];

	/**
	 * @return string
	 */
	public function getSenderName(): string
	{
		return $this->sender_name;
	}

	/**
	 * @param string $sender_name
	 */
	public function setSenderName( string $sender_name ): void
	{
		$this->sender_name = $sender_name;
	}

	/**
	 * @return string
	 */
	public function getSenderEmail(): string
	{
		return $this->sender_email;
	}

	/**
	 * @param string $sender_email
	 */
	public function setSenderEmail( string $sender_email ): void
	{
		$this->sender_email = $sender_email;
	}

	/**
	 * @return array|string
	 */
	public function getTo(): array|string
	{
		return $this->to;
	}

	/**
	 * @param array|string $to
	 */
	public function setTo( array|string $to ): void
	{
		$this->to = $to;
	}

	/**
	 * @return array|string
	 */
	public function getToCopy(): array|string
	{
		return $this->to_copy;
	}

	/**
	 * @param array|string $to_copy
	 */
	public function setToCopy( array|string $to_copy ): void
	{
		$this->to_copy = $to_copy;
	}

	/**
	 * @return array|string
	 */
	public function getToHiddenCopy(): array|string
	{
		return $this->to_hidden_copy;
	}

	/**
	 * @param array|string $to_hidden_copy
	 */
	public function setToHiddenCopy( array|string $to_hidden_copy ): void
	{
		$this->to_hidden_copy = $to_hidden_copy;
	}




	/**
	 * @return string
	 */
	public function getSubject(): string
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject( string $subject ): void
	{
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getBodyTxt(): string
	{
		return $this->body_txt;
	}

	/**
	 * @param string $body_txt
	 */
	public function setBodyTxt( string $body_txt ): void
	{
		$this->body_txt = $body_txt;
	}

	/**
	 * @return string
	 */
	public function getBodyHtml(): string
	{
		return $this->body_html;
	}

	/**
	 * @param string $body_html
	 * @param bool $parse_images
	 */
	public function setBodyHtml( string $body_html, bool $parse_images = true ): void
	{
		$this->body_html = $body_html;

		$this->images = [];

		if($parse_images) {
			$this->parseImages();
		}
	}

	public function parseImages() : void
	{
		$public_url = str_replace( '/', '\\/', SysConf_URI::getImages() );

		if( preg_match_all( '/src=["]' . $public_url . '(.*)["]/Ui', $this->body_html, $matches, PREG_SET_ORDER ) ) {

			foreach( $matches as $m ) {
				$orig = $m[0];
				$image = $m[1];

				$id = 'i_' . uniqid();

				$this->addImage( $id, SysConf_Path::getImages() . $image );

				$this->body_html = str_replace( $orig, 'src="cid:' . $id . '"', $this->body_html );
			}

		}
	}


	/**
	 * @param string $file_path
	 * @param string $file_name
	 */
	public function addAttachments( string $file_path, string $file_name = '' ): void
	{

		if( !$file_name ) {
			$file_name = basename( $file_path );
		}

		$this->attachments[$file_name] = $file_path;
	}

	/**
	 * @return array
	 */
	public function getAttachments(): array
	{
		return $this->attachments;
	}


	/**
	 * @param string $cid
	 * @param string $path
	 */
	public function addImage( string $cid, string $path ): void
	{
		$this->images[$cid] = $path;
	}

	/**
	 * @return array
	 */
	public function getImages(): array
	{
		return $this->images;
	}

	/**
	 * @param string $header
	 * @param string $value
	 */
	public function setCustomHeader( string $header, string $value ) : void
	{
		$this->custom_headers[$header] = $value;
	}

	/**
	 * @return array
	 */
	public function getCustomHeaders(): array
	{
		return $this->custom_headers;
	}


	/**
	 *
	 * @return bool
	 */
	public function send(): bool
	{
		return Mailing::sendEmail( $this );
	}
	
	
	/**
	 * @param string|null $message
	 * @param string|null $header
	 *
	 * @throws IO_File_Exception
	 */
	public function prepareMessage( ?string &$message, ?string &$header ) : void
	{
		
		$subject = $this->getSubject();
		
		$boundary_1 = uniqid( 'MP' );
		$boundary_2 = $boundary_1 . 'SP1';
		$boundary_3 = $boundary_1 . 'SP2';
		
		$eol = PHP_EOL;
		
		if($this->getSenderName()) {
			$headers['From'] = mb_encode_mimeheader($this->getSenderName() . '<' . $this->getSenderEmail() . '>' );
		} else {
			$headers['From'] = mb_encode_mimeheader($this->getSenderEmail());
		}
		$headers['Subject'] = mb_encode_mimeheader($subject);
		$headers['Reply-To'] = mb_encode_mimeheader($this->getSenderEmail());
		$headers['MIME-Version'] = '1.0';
		$headers['Content-Type'] = 'multipart/mixed; boundary=' . $boundary_1 . ';';
		
		foreach($this->getCustomHeaders() as $h=>$v ) {
			$headers[$h] = $v;
		}
		
		if($this->getToCopy()) {
			$cc = $this->getToCopy();
			
			if(is_array($cc)) {
				$cc = implode(', ', $cc);
			}
			
			$headers['Cc'] = $cc;
		}
		
		if($this->getToCopy()) {
			$bcc = $this->getToHiddenCopy();
			
			if(is_array($bcc)) {
				$bcc = implode(', ', $bcc);
			}
			
			$headers['Bcc'] = $bcc;
		}
		
		$header = '';
		foreach( $headers as $h => $v ) {
			$header .= $h . ': ' . $v . $eol;
		}
		
		
		
		
		$message = 'This is a MIME encoded message.' . $eol;
		$message .= $eol . "--$boundary_1" . $eol;
		$message .= 'Content-Type: multipart/related; boundary=' . $boundary_2 . ';' . $eol;
		$message .= $eol . "--$boundary_2" . $eol;
		$message .= 'Content-Type: multipart/alternative; boundary=' . $boundary_3 . ';' . $eol;
		$message .= $eol . "--$boundary_3" . $eol;
		
		$message .= 'Content-type: text/plain;charset=utf-8' . $eol;
		$message .= $eol;
		$message .= $this->getBodyTxt() . $eol;
		
		
		$message .= $eol . "--$boundary_3" . $eol;
		
		
		$message .= 'Content-type: text/html;charset=utf-8' . $eol;
		$message .= $eol;
		$message .= $this->getBodyHtml() . $eol;
		
		$message .= $eol . "--$boundary_3--" . $eol;
		
		foreach( $this->getImages() as $image_id => $image_path ) {
			$image_info = Debug_ErrorHandler::doItSilent(function() use ($image_path) {
				return getimagesize( $image_path );
			});
			
			if( !$image_info ) {
				continue;
			}
			
			$filename = basename( $image_path );
			
			$message .= $eol . "--$boundary_2" . $eol;
			$message .= 'Content-type: ' . $image_info['mime'] . $eol;
			$message .= 'Content-ID: <' . $image_id . '>' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol;
			$message .= 'Content-Disposition: inline; filename="' . mb_encode_mimeheader( $filename ) . '""' . $eol;
			$message .= $eol;
			$message .= chunk_split( base64_encode( IO_File::read( $image_path ) ) );
			
		}
		
		$message .= "--$boundary_2--" . $eol;
		
		foreach( $this->getAttachments() as $file_path => $filename ) {
			$message .= $eol . "--$boundary_1" . $eol;
			$message .= 'Content-Type: application/octet-stream; name="' . mb_encode_mimeheader( $filename ) . '"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol;
			$message .= 'Content-Disposition: attachment' . $eol;
			$message .= chunk_split( base64_encode( IO_File::read( $file_path ) ) );
		}
		
		
		$message .= "--$boundary_1--" . $eol;
	}
	

}