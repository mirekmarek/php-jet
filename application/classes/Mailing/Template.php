<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

use Jet\BaseObject;
use Jet\Data_Text;
use Jet\IO_File;
use Jet\Locale;

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
	 * @param string $id
	 * @param string|Locale $locale
	 * @param array $data
	 */
	public function __construct($id, $locale, array $data)
	{
		if( is_string($locale) ) {
			$locale = new Locale($locale);
		}

		$this->id = $id;
		$this->locale = $locale;

		$base_path = JETAPP_EMAIL_TEMPLATES_PATH.$this->locale.'/'.$this->id.'/';

		$this->subject = IO_File::read($base_path.static::SUBJECT_FILE_NAME);
		$this->boxy_txt = IO_File::read($base_path.static::BODY_TXT_FILE_NAME);
		$this->body_html = IO_File::read($base_path.static::BODY_HTML_FILE_NAME);

		$this->subject = Data_Text::replaceData($this->subject, $data);
		$this->boxy_txt = Data_Text::replaceData($this->boxy_txt, $data);
		$this->body_html = Data_Text::replaceData($this->body_html, $data);
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
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject)
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
	public function setBoxyTxt($boxy_txt)
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
	public function setBodyHtml($body_html)
	{
		$this->body_html = $body_html;
	}



	/**
	 * @param $to
	 */
	public function send( $to ) {

		$sender = Mailing::getSenderConfig($this->locale);

		$subject = $this->getSubject();

		$headers = "From: " . $sender->getName() . "<".$sender->getEmail().">\r\n";
		$headers .= "Reply-To: ". $sender->getEmail() . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		$message = $this->getBodyHtml();


		mail($to, $subject, $message, $headers);
	}

}