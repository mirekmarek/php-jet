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
class UI_localeLabel extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'localeLabel';


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
	public function getLocale(): Locale
	{
		return $this->locale;
	}

}