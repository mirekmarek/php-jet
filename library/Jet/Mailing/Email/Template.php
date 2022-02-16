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
class Mailing_Email_Template extends BaseObject
{

	const SUBJECT_VIEW = 'subject';
	const BODY_TXT_VIEW = 'body_txt';
	const BODY_HTML_VIEW = 'body_html';

	/**
	 * @var string
	 */
	protected string $template_id;

	/**
	 * @var Locale
	 */
	protected Locale $locale;

	/**
	 * @var string
	 */
	protected string $sender_id;

	/**
	 * @var array
	 */
	protected array $data = [];

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
	protected string $view_dir = '';

	/**
	 * @var ?MVC_View
	 */
	protected ?MVC_View $__view = null;


	/**
	 * @param string $template_id
	 * @param string $sender_id
	 * @param Locale|null $locale
	 */
	public function __construct( string $template_id, string $sender_id=Mailing::DEFAULT_SENDER_ID, Locale $locale=null  )
	{
		if(!$locale) {
			$locale = Locale::getCurrentLocale();
		}

		$this->template_id = $template_id;
		$this->locale = $locale;
		$this->sender_id = $sender_id;
		$this->view_dir = SysConf_Jet_Mailing::getTemplatesDir().$this->locale.'/'.$this->template_id.'/';
	}

	/**
	 * @param string $view_dir
	 */
	public function setViewDir( string $view_dir ): void
	{
		$this->view_dir = $view_dir;
	}


	/**
	 * @return string
	 */
	public function getViewDir(): string
	{
		return $this->view_dir;
	}


	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{
		if( !$this->__view ) {
			$this->__view = Factory_MVC::getViewInstance( $this->getViewDir() );
		}

		return $this->__view;
	}

	/**
	 * @return string
	 */
	public function getTemplateId(): string
	{
		return $this->template_id;
	}

	/**
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getSenderId(): string
	{
		return $this->sender_id;
	}

	/**
	 * @return Mailing_Config_Sender
	 */
	public function getSender(): Mailing_Config_Sender
	{
		return Mailing::getConfig()->getSender( $this->sender_id );
	}


	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setVar( string $key, mixed $value ): void
	{
		$this->data[$key] = $value;
		$this->getView()->setVar( $key, $value );
	}

	/**
	 * @return string
	 */
	public function getSubject(): string
	{
		return trim( $this->getView()->render( static::SUBJECT_VIEW ) );
	}

	/**
	 * @param bool $parse_images
	 *
	 * @return string
	 */
	public function getBodyHtml( bool $parse_images = true ): string
	{
		return $this->getView()->render( static::BODY_HTML_VIEW );
	}

	/**
	 * @return string
	 */
	public function getBodyTxt(): string
	{
		return $this->getView()->render( static::BODY_TXT_VIEW );
	}


	public function getEmail() : Mailing_Email
	{
		$email = new Mailing_Email();

		$sender = $this->getSender();

		$email->setSenderName( $sender->getName() );
		$email->setSenderEmail( $sender->getEmail() );

		$email->setSubject( $this->getSubject() );

		$email->setBodyHtml( $this->getBodyHtml() );
		$email->setBodyTxt( $this->getBodyTxt() );

		return $email;
	}


}