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
class UI_locale extends UI_BaseElement
{

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'locale';

	/**
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;


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
	public function getLocale() : Locale
	{
		return $this->locale;
	}

}