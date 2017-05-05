<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Locale;

/**
 * Class flag
 * @package JetUI
 */
class flag extends BaseObject
{

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
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$locale = $this->locale;

		$res = '';

		$title = $locale->getRegionName().' - '.$locale->getLanguageName();

		$res .= '<div class="flag flag-'.strtolower(
				$locale->getRegion()
			).'" title="'.$title.'" alt="'.$title.'" /></div>';

		return $res;
	}

}