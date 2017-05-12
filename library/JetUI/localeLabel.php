<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\Locale;

/**
 * Class flag
 * @package JetUI
 */
class localeLabel extends BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'localeLabel';


	/**
	 * @var Locale
	 */
	protected $locale;


	/**
	 * @param Locale $locale
	 */
	public function __construct( Locale $locale )
	{
		$this->locale = $locale;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

}