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
	protected ?string $site_id = '';

	/**
	 * @var string
	 */
	protected string $specification = '';

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
	 * @var ?Mvc_View
	 */
	protected ?Mvc_View $__view = null;


	/**
	 *
	 * @param string $name
	 * @param string|Locale|null $locale
	 * @param string|null $site_id
	 * @param string $specification
	 */
	public function __construct( string $name, string|Locale|null $locale=null, ?string $site_id=null, string $specification='' )
	{
		if($locale===null) {
			$locale = Locale::getCurrentLocale();
		}

		if(
			$locale &&
			is_string( $locale )
		) {
			$locale = new Locale( $locale );
		}

		if($site_id===null) {
			if(Mvc::getCurrentSite()) {
				$site_id = Mvc::getCurrentSite()->getId();
			}
		}

		$this->name = $name;
		$this->locale = $locale;
		$this->site_id = $site_id;
		$this->specification = $specification;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() : Mvc_View
	{
		if(!$this->__view) {
			$path = Mailing::getBaseViewDir();

			if( $this->site_id ) {
				$path .= $this->site_id.'/';
			}

			if( $this->locale ) {
				$path .= $this->locale.'/';
			}

			$path .= $this->name.'/';


			$this->__view = new Mvc_View( $path );
		}

		return $this->__view;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() : Locale
	{
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getSiteId() : string
	{
		return $this->site_id;
	}

	/**
	 * @return string
	 */
	public function getSpecification() : string
	{
		return $this->specification;
	}

	/**
	 * @return Mailing_Config_Sender
	 */
	public function getSender() : Mailing_Config_Sender
	{
		return Mailing::getConfig()->getSender( $this->locale, $this->site_id, $this->specification );
	}


	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setVar(string  $key, mixed $value ) : void
	{
		$this->data[$key] = $value;
		$this->getView()->setVar($key, $value);
	}

	/**
	 * @return string
	 */
	public function getSubject() : string
	{
		return trim($this->getView()->render(static::SUBJECT_VIEW));
	}

	/**
	 * @param bool $parse_images
	 *
	 * @return string
	 */
	public function getBodyHtml( bool $parse_images=true ) : string
	{
		$html =  $this->getView()->render(static::BODY_HTML_VIEW);

		if($parse_images) {

			$public_url = str_replace('/','\\/', SysConf_URI::getPublic());

			if(preg_match_all('/src=["]'.$public_url.'(.*)["]/Ui', $html, $matches, PREG_SET_ORDER )) {

				foreach( $matches as $m ) {
					$orig=$m[0];
					$image=$m[1];

					$id = 'i_'.uniqid();


					$this->addImage( $id, SysConf_Path::getPublic().$image );

					$html = str_replace( $orig, 'src="cid:'.$id.'"', $html );
				}

			}
		}

		return $html;
	}

	/**
	 * @return string
	 */
	public function getBodyTxt() : string
	{
		return $this->getView()->render(static::BODY_TXT_VIEW);
	}

	/**
	 * @param string $file_path
	 * @param string $file_name
	 */
	public function addAttachments( string $file_path, string $file_name='' ) : void
	{

		if(!$file_name) {
			$file_name = basename($file_path);
		}

		$this->attachments[$file_name] = $file_path;
	}

	/**
	 * @return array
	 */
	public function getAttachments() : array
	{
		return $this->attachments;
	}



	/**
	 * @param string $cid
	 * @param string $path
	 */
	public function addImage( string $cid, string $path ) : void
	{
		$this->images[$cid] = $path;
	}

	/**
	 * @return array
	 */
	public function getImages() : array
	{
		return $this->images;
	}


	/**
	 * @param string $to
	 * @param array $extra_headers
	 *
	 * @return bool
	 */
	public function send( string $to, array $extra_headers=[] ) : bool
	{

		return Mailing::sendEmail( $this, $to, $extra_headers );
	}

}