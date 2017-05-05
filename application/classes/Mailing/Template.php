<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\BaseObject;
use Jet\Data_Text;
use Jet\IO_File;
use Jet\Locale;

/**
 * Class Mailing_Template
 * @package JetExampleApp
 */
class Mailing_Template extends BaseObject
{
	const SUBJECT_FILE_NAME = 'subject.txt';
	const BODY_TXT_FILE_NAME = 'body.txt';
	const BODY_HTML_FILE_NAME = 'body.html';

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $root_dir = '';

	/**
	 * @var string
	 */
	protected $subject = '';

	/**
	 * @var string
	 */
	protected $boxy_txt = '';

	/**
	 * @var string
	 */
	protected $body_html = '';

	/**
	 * Mailing constructor.
	 *
	 * @param string        $id
	 * @param string|Locale $locale
	 * @param array         $data
	 */
	public function __construct( $id, $locale, array $data )
	{
		if( is_string( $locale ) ) {
			$locale = new Locale( $locale );
		}

		$this->id = $id;
		$this->locale = $locale;
		$this->root_dir = JETAPP_EMAIL_TEMPLATES_PATH.$this->locale.'/'.$this->id.'/';

		$this->readTemplate();
		$this->applyData( $data );
	}

	/**
	 *
	 */
	public function readTemplate()
	{
		$this->subject = IO_File::read( $this->root_dir.static::SUBJECT_FILE_NAME );
		$this->boxy_txt = IO_File::read( $this->root_dir.static::BODY_TXT_FILE_NAME );
		$this->body_html = IO_File::read( $this->root_dir.static::BODY_HTML_FILE_NAME );

	}

	/**
	 * @param array $data
	 */
	public function applyData( array $data )
	{
		$this->subject = Data_Text::replaceData( $this->subject, $data );
		$this->boxy_txt = Data_Text::replaceData( $this->boxy_txt, $data );
		$this->body_html = Data_Text::replaceData( $this->body_html, $data );

	}

	/**
	 * @return string
	 */
	public function getRootDir()
	{
		return $this->root_dir;
	}

	/**
	 * @param string $root_dir
	 */
	public function setRootDir( $root_dir )
	{
		$this->root_dir = $root_dir;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param $to
	 */
	public function send( $to )
	{

		$sender = Mailing::getSenderConfig( $this->locale );

		$boundary = uniqid( 'mp' );

		$subject = $this->getSubject();

		$headers = "From: ".$sender->getName()."<".$sender->getEmail().">\r\n";
		$headers .= "Reply-To: ".$sender->getEmail()."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/alternative;boundary=".$boundary."\r\n";

		$message = "This is a MIME encoded message.";
		$message .= "\r\n\r\n--$boundary\r\n";
		$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
		$message .= $this->getBoxyTxt();
		$message .= "\r\n\r\n--$boundary\r\n";
		$message .= "Content-type: text/html;charset=utf-8\r\n\r\n";
		$message .= $this->getBodyHtml();
		$message .= "\r\n\r\n--$boundary--";

		mail( $to, $subject, $message, $headers );

	}

	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject( $subject )
	{
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getBoxyTxt()
	{
		return $this->boxy_txt;
	}

	/**
	 * @param string $boxy_txt
	 */
	public function setBoxyTxt( $boxy_txt )
	{
		$this->boxy_txt = $boxy_txt;
	}

	/**
	 * @return string
	 */
	public function getBodyHtml()
	{
		return $this->body_html;
	}

	/**
	 * @param string $body_html
	 */
	public function setBodyHtml( $body_html )
	{
		$this->body_html = $body_html;
	}

}