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
class Mailing_Email_Template extends BaseObject
{

	const SUBJECT_VIEW = 'subject';
	const BODY_TXT_VIEW = 'body_txt';
	const BODY_HTML_VIEW = 'body_html';


	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;

	/**
	 * @var ?string
	 */
	protected ?string $base_id = '';

	/**
	 * @var string
	 */
	protected string $sender_specification = '';

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
	 * @var ?callable
	 */
	protected static $view_dir_generator = null;

	/**
	 * @var ?Mvc_View
	 */
	protected ?Mvc_View $__view = null;

	/**
	 * @return callable|null
	 */
	public static function getViewDirGenerator(): ?callable
	{
		if(!static::$view_dir_generator) {
			static::$view_dir_generator = function( Mailing_Email_Template $template ) : string {
				$path = Mvc_Base::get( $template->getBaseId() )->getViewsPath() . 'EmailTemplates/';

				if( $template->getLocale() ) {
					$path .= $template->getLocale() . '/';
				}

				$path .= $template->getName() . '/';

				return $path;
			};
		}

		return static::$view_dir_generator;
	}

	/**
	 * @param callable|null $view_dir_generator
	 */
	public static function setViewDirGenerator( ?callable $view_dir_generator ): void
	{
		static::$view_dir_generator = $view_dir_generator;
	}




	/**
	 *
	 * @param string $name
	 * @param string|Locale|null $locale
	 * @param string|null $base_id
	 * @param string $specification
	 */
	public function __construct( string $name, string|Locale|null $locale = null, ?string $base_id = null, string $specification = '' )
	{
		if( $locale === null ) {
			$locale = Locale::getCurrentLocale();
		}

		if(
			$locale &&
			is_string( $locale )
		) {
			$locale = new Locale( $locale );
		}

		if( $base_id === null ) {
			if( Mvc::getCurrentBase() ) {
				$base_id = Mvc::getCurrentBase()->getId();
			}
		}

		$this->name = $name;
		$this->locale = $locale;
		$this->base_id = $base_id;
		$this->sender_specification = $specification;
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
		if(!$this->view_dir) {
			$generator = static::getViewDirGenerator();

			$this->view_dir = $generator( $this );
		}

		return $this->view_dir;
	}


	/**
	 * @return Mvc_View
	 */
	public function getView(): Mvc_View
	{
		if( !$this->__view ) {
			$this->__view = new Mvc_View( $this->getViewDir() );
		}

		return $this->__view;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
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
	public function getBaseId(): string
	{
		return $this->base_id;
	}

	/**
	 * @return string
	 */
	public function getSenderSpecification(): string
	{
		return $this->sender_specification;
	}

	/**
	 * @return Mailing_Config_Sender
	 */
	public function getSender(): Mailing_Config_Sender
	{
		return Mailing::getConfig()->getSender( $this->locale, $this->base_id, $this->sender_specification );
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