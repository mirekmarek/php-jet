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
class UI_localeLabel extends UI_BaseElement
{

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
	 * @return string
	 */
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_UI_DefaultViews::get('locale-label');
		}

		return $this->view_script;
	}

	/**
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->locale;
	}

}