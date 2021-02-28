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
class Mailing_Email extends BaseObject
{

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




	public function prepare( string &$message, string &$header ) : void
	{
		$subject = $this->getSubject();

		$boundary_1 = uniqid( 'MP' );
		$boundary_2 = $boundary_1 . 'SP1';
		$boundary_3 = $boundary_1 . 'SP2';

		$eol = PHP_EOL;

		$headers = [];

		if($this->getSenderName()) {
			$headers['From'] = $this->getSenderName() . "<" . $this->getSenderEmail() . ">";
		} else {
			$headers['From'] = $this->getSenderEmail();
		}

		$headers['Subject'] = $subject;
		$headers['Reply-To'] = $this->getSenderEmail();
		$headers['MIME-Version'] = '1.0';
		$headers['Content-Type'] = 'multipart/mixed; boundary=' . $boundary_1 . ';';
		foreach( $this->getCustomHeaders() as $h => $v ) {
			$headers[$h] = $v;
		}


		$header = '';
		foreach( $headers as $h => $v ) {
			$header .= $h . ": " . mb_encode_mimeheader( $v ) . $eol;
		}


		$message = "This is a MIME encoded message." . $eol;
		$message .= $eol . "--$boundary_1" . $eol;
		$message .= "Content-Type: multipart/related; boundary=" . $boundary_2 . ";" . $eol;
		$message .= $eol . "--$boundary_2" . $eol;
		$message .= "Content-Type: multipart/alternative; boundary=" . $boundary_3 . ";" . $eol;
		$message .= $eol . "--$boundary_3" . $eol;

		$message .= "Content-type: text/plain;charset=utf-8" . $eol;
		$message .= $eol;
		$message .= $this->getBodyTxt() . $eol;


		$message .= $eol . "--$boundary_3" . $eol;


		$message .= "Content-type: text/html;charset=utf-8" . $eol;
		$message .= $eol;
		$message .= $this->getBodyHtml() . $eol;

		$message .= $eol . "--$boundary_3--" . $eol;

		foreach( $this->getImages() as $image_id => $image_path ) {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
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

		foreach( $this->getAttachments() as $file_path => $filename ) {
			$message .= $eol . "--$boundary_1" . $eol;
			$message .= 'Content-Type: application/octet-stream; name="' . mb_encode_mimeheader( $filename ) . '"' . $eol;
			$message .= 'Content-Transfer-Encoding: base64' . $eol;
			$message .= 'Content-Disposition: attachment' . $eol;
			$message .= chunk_split( base64_encode( IO_File::read( $file_path ) ) );
		}


		$message .= "--$boundary_1--" . $eol;

	}


	/**
	 * @param string $to
	 *
	 * @return bool
	 */
	public function send( string $to ): bool
	{
		return Mailing::sendEmail( $this, $to );
	}

}