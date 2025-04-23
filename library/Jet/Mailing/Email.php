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
	 * @var Mailing_Email_File[]
	 */
	protected array $attachments = [];

	/**
	 * @var Mailing_Email_File[]
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

		if( preg_match_all( '/src="' . $public_url . '(.*)"/Ui', $this->body_html, $matches, PREG_SET_ORDER ) ) {

			foreach( $matches as $m ) {
				$orig = $m[0];
				$src = $m[1];

				$id = 'i_' . uniqid();
				
				$src_decoded = rawurldecode( $src );
				
				$paths = [
					$src,
					$src_decoded,
					SysConf_Path::getImages() . $src_decoded
				];
				
				
				foreach($paths as $path) {
					if(IO_File::isReadable($path)) {
						/*
						$image_data = IO_File::read( $path );
						$image_data = 'data:'.IO_File::getMimeType($path).';base64,'.base64_encode( $image_data );
						$this->body_html = str_replace( $orig, 'src="' . $image_data . '"', $this->body_html );
						*/
						
						$this->body_html = str_replace( $orig, 'src="cid:' . $id . '"', $this->body_html );
						$this->addImage( $id, $path );
						
						break;
					}
				}
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
		
		$file = new Mailing_Email_File( $file_name );
		$file->setPath( $file_path );

		$this->attachments[$file->getId()] =  $file;
	}
	
	public function addAttachmentsData( string $file_name, string $file_mime_type, string $file_data ) : void
	{
		$file = new Mailing_Email_File( $file_name );
		$file->setData( $file_data );
		$file->setFileName( $file_name );
		$file->setFileMimeType( $file_mime_type );
		
		$this->attachments[$file->getId()] =  $file;
		
	}

	/**
	 * @return Mailing_Email_File[]
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
		$image = new Mailing_Email_File( $cid );
		$image->setPath( $path );
		
		$this->images[$cid] = $image;
	}
	
	public function addImageData( string $cid, string $file_name, string $file_mime_type, string $file_data ) : void
	{
		$image = new Mailing_Email_File( $cid );
		$image->setFileName( $file_name );
		$image->setFileMimeType( $file_mime_type );
		$image->setData( $file_data );
		
		$this->images[$cid] = $image;
	}

	/**
	 * @return Mailing_Email_File[]
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

}