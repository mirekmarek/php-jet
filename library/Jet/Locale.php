<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use \IntlDateFormatter as PHP_IntlDateFormatter;
use \NumberFormatter as PHP_NumberFormatter;
use \Locale as PHP_Locale;


/**
 *
 */
class Locale extends BaseObject
{


	/**
	 * Most abbreviated style, only essential data (12/13/52 or 3:30pm)
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const DATE_TIME_FORMAT_SHORT = PHP_IntlDateFormatter::SHORT;

	/**
	 * Medium style (Jan 12, 1952)
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const DATE_TIME_FORMAT_MEDIUM = PHP_IntlDateFormatter::MEDIUM;

	/**
	 * Long style (January 12, 1952 or 3:30:32pm)
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const DATE_TIME_FORMAT_LONG = PHP_IntlDateFormatter::LONG;

	/**
	 * Completely specified style (Tuesday, April 12, 1952 AD or 3:30:42pm PST)
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const DATE_TIME_FORMAT_FULL = PHP_IntlDateFormatter::FULL;


	/**
	 * Gregorian Calendar
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const CALENDAR_GREGORIAN = PHP_IntlDateFormatter::GREGORIAN;

	/**
	 * Non-Gregorian Calendar
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const CALENDAR_TRADITIONAL = PHP_IntlDateFormatter::TRADITIONAL;


	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * @var string[]
	 */
	protected static $all_locales = [
		'af_ZA', 'am_ET', 'ar_AE', 'ar_BH', 'ar_DZ', 'ar_EG', 'ar_IQ', 'ar_JO', 'ar_KW', 'ar_LB', 'ar_LY', 'ar_MA',
		'arn_CL', 'ar_OM', 'ar_QA', 'ar_SA', 'ar_SY', 'ar_TN', 'ar_YE', 'as_IN', 'az_Cyrl_AZ', 'az_Latn_AZ', 'ba_RU',
		'be_BY', 'bg_BG', 'bn_BD', 'bn_IN', 'bo_CN', 'br_FR', 'bs_Cyrl_BA', 'bs_Latn_BA', 'ca_ES', 'co_FR', 'cs_CZ',
		'cy_GB', 'da_DK', 'de_AT', 'de_CH', 'de_DE', 'de_LI', 'de_LU', 'dsb_DE', 'dv_MV', 'el_GR', 'en_029', 'en_AU',
		'en_BZ', 'en_CA', 'en_GB', 'en_IE', 'en_IN', 'en_JM', 'en_MY', 'en_NZ', 'en_PH', 'en_SG', 'en_TT', 'en_US',
		'en_ZA', 'en_ZW', 'es_AR', 'es_BO', 'es_CL', 'es_CO', 'es_CR', 'es_DO', 'es_EC', 'es_ES', 'es_GT', 'es_HN',
		'es_MX', 'es_NI', 'es_PA', 'es_PE', 'es_PR', 'es_PY', 'es_SV', 'es_US', 'es_UY', 'es_VE', 'et_EE', 'eu_ES',
		'fa_IR', 'fi_FI', 'fil_PH', 'fo_FO', 'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'fr_LU', 'fr_MC', 'fy_NL', 'ga_IE',
		'gd_GB', 'gl_ES', 'gsw_FR', 'gu_IN', 'ha_Latn_NG', 'he_IL', 'hi_IN', 'hr_BA', 'hr_HR', 'hsb_DE', 'hu_HU',
		'hy_AM', 'id_ID', 'ig_NG', 'ii_CN', 'is_IS', 'it_CH', 'it_IT', 'iu_Cans_CA', 'iu_Latn_CA', 'ja_JP', 'ka_GE',
		'kk_KZ', 'kl_GL', 'km_KH', 'kn_IN', 'kok_IN', 'ko_KR', 'ky_KG', 'lb_LU', 'lo_LA', 'lt_LT', 'lv_LV', 'mi_NZ',
		'mk_MK', 'ml_IN', 'mn_MN', 'mn_Mong_CN', 'moh_CA', 'mr_IN', 'ms_BN', 'ms_MY', 'mt_MT', 'nb_NO', 'ne_NP',
		'nl_BE', 'nl_NL', 'nn_NO', 'nso_ZA', 'oc_FR', 'or_IN', 'pa_IN', 'pl_PL', 'prs_AF', 'ps_AF', 'pt_BR', 'pt_PT',
		'qut_GT', 'quz_BO', 'quz_EC', 'quz_PE', 'rm_CH', 'ro_RO', 'ru_RU', 'rw_RW', 'sah_RU', 'sa_IN', 'se_FI', 'se_NO',
		'se_SE', 'si_LK', 'sk_SK', 'sl_SI', 'sma_NO', 'sma_SE', 'smj_NO', 'smj_SE', 'smn_FI', 'sms_FI', 'sq_AL',
		'sr_Cyrl_BA', 'sr_Cyrl_CS', 'sr_Cyrl_ME', 'sr_Cyrl_RS', 'sr_Latn_BA', 'sr_Latn_CS', 'sr_Latn_ME', 'sr_Latn_RS',
		'sv_FI', 'sv_SE', 'sw_KE', 'syr_SY', 'ta_IN', 'te_IN', 'tg_Cyrl_TJ', 'th_TH', 'tk_TM', 'tn_ZA', 'tr_TR',
		'tt_RU', 'tzm_Latn_DZ', 'ug_CN', 'uk_UA', 'ur_PK', 'uz_Cyrl_UZ', 'uz_Latn_UZ', 'vi_VN', 'wo_SN', 'xh_ZA',
		'yo_NG', 'zh_CN', 'zh_HK', 'zh_MO', 'zh_SG', 'zh_TW', 'zu_ZA',
	];

	/**
	 * Example en_US, cs_CZ and so on
	 * @var string
	 */
	protected $locale = '';

	/**
	 * Example: CZ, US, ...
	 *
	 * @var string
	 */
	protected $region = '';

	/**
	 * Example: en, cs, sk, ...
	 *
	 * @var string
	 */
	protected $language = '';

	/**
	 *
	 * @see http://www.php.net/manual/en/book.datetime.php
	 *
	 * date_default_timezone_get() value by default
	 *
	 * @var string
	 */
	protected $_timezone;

	/**
	 *
	 * @var int
	 */
	protected $_calendar;

	/**
	 * @var array
	 */
	protected $_currency_formatter = [];

	/**
	 *
	 * @param null|string|Locale $in_locale (optional, default: current locale)
	 *
	 * @return array
	 */
	public static function getAllLocalesList( $in_locale = null )
	{
		if( !$in_locale ) {
			$in_locale = static::getCurrentLocale();
		}

		$result = [];

		foreach( static::$all_locales as $locale ) {
			$result[$locale] = PHP_Locale::getDisplayName( $locale, $in_locale );
		}

		asort( $result );

		return $result;
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale()
	{
		return static::$current_locale;
	}

	/**
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale )
	{
		static::$current_locale = $current_locale;
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatDate($date_and_time);
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $format
	 *
	 * @return string
	 */
	public static function date( Data_DateTime $date_and_time, $format = self::DATE_TIME_FORMAT_MEDIUM )
	{
		return static::getCurrentLocale()->formatDate( $date_and_time, $format );
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatDateAdnTime($date_and_time);
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $date_format
	 * @param int           $time_format
	 *
	 * @return string
	 */
	public static function dateAndTime( Data_DateTime $date_and_time, $date_format = self::DATE_TIME_FORMAT_MEDIUM, $time_format = self::DATE_TIME_FORMAT_SHORT )
	{
		return static::getCurrentLocale()->formatDateAndTime( $date_and_time, $date_format, $time_format );
	}


	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatTime($date_and_time);
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $time_format
	 *
	 * @return string
	 */
	public static function time( Data_DateTime $date_and_time, $time_format = self::DATE_TIME_FORMAT_SHORT )
	{
		return static::getCurrentLocale()->formatTime( $date_and_time, $time_format );
	}


	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatInt($number)
	 *
	 * @param int $number
	 *
	 * @return string
	 */
	public static function int( $number )
	{
		return static::getCurrentLocale()->formatInt( $number );
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatFloat($number, $min_fraction_digits, $max_fraction_digits)
	 *
	 * @param float $number
	 * @param int   $min_fraction_digits
	 * @param int   $max_fraction_digits
	 *
	 * @return string
	 */
	public static function float( $number, $min_fraction_digits = 0, $max_fraction_digits = 2 )
	{
		return static::getCurrentLocale()->formatFloat( $number, $min_fraction_digits, $max_fraction_digits );
	}

	/**
	 * Format file or memory size according to current locale
	 *
	 * Example:
	 *      65536 -> 64 KB
	 *
	 * @param int    $bytes
	 * @param string $unit (optional, default: B)
	 * @param int    $max_places (optional, default: 2) float precision
	 * @param string $glue (optional,default: ' ') space between number and units
	 *
	 * @return string
	 */
	public static function size( $bytes, $unit = 'iB', $max_places = 2, $glue = ' ' )
	{
		return static::getCurrentLocale()->formatSize( $bytes, $unit, $max_places, $glue );
	}

	/**
	 *
	 * @param float  $value
	 * @param string $currency
	 *
	 * @return string
	 */
	public static function currency( $value ,$currency )
	{
		return static::getCurrentLocale()->formatCurrency( $value ,$currency );

	}

	/**
	 *
	 * @param string|null $locale
	 */
	public function __construct( $locale = null )
	{
		if( $locale ) {
			$this->_setLocale( $locale );
		}
	}

	/**
	 * @return string
	 */
	public function getTimeZone()
	{
		if( !$this->_timezone ) {
			$this->_timezone = date_default_timezone_get();
		}

		return $this->_timezone;
	}

	/**
	 *
	 * @param string $time_zone
	 */
	public function setTimeZone( $time_zone )
	{
		$this->_timezone = $time_zone;
	}

	/**
	 * @return int
	 */
	public function getCalendar()
	{
		if( $this->_calendar===null ) {
			$this->_calendar = self::CALENDAR_GREGORIAN;
		}

		return $this->_calendar;
	}

	/**
	 * @param int $calendar
	 */
	public function setCalendar( $calendar )
	{
		$this->_calendar = $calendar;
	}

	/**
	 * Returns date formatted by locale
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $format
	 *
	 * @return string
	 */
	public function formatDate( Data_DateTime $date_and_time, $format = self::DATE_TIME_FORMAT_MEDIUM )
	{
		if( !$date_and_time ) {
			return '';
		}

		$fmt = new PHP_IntlDateFormatter(
			$this->locale, $format, PHP_IntlDateFormatter::NONE, $this->getTimeZone(), $this->getCalendar()
		);

		return $fmt->format( $date_and_time );
	}

	/**
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $date_format
	 * @param int           $time_format
	 *
	 * @return string
	 */
	public function formatDateAndTime( Data_DateTime $date_and_time, $date_format = self::DATE_TIME_FORMAT_MEDIUM, $time_format=self::DATE_TIME_FORMAT_SHORT )
	{
		if( !$date_and_time ) {
			return '';
		}


		$fmt = new PHP_IntlDateFormatter(
			$this->locale, $date_format, $time_format, $this->getTimeZone(), $this->getCalendar()
		);

		return $fmt->format( $date_and_time );
	}


	/**
	 *
	 * @param Data_DateTime $date_and_time
	 * @param int           $time_format
	 *
	 * @return string
	 */
	public function formatTime( Data_DateTime $date_and_time, $time_format=self::DATE_TIME_FORMAT_SHORT )
	{
		if( !$date_and_time ) {
			return '';
		}


		$fmt = new PHP_IntlDateFormatter(
			$this->locale, -1, $time_format, $this->getTimeZone(), $this->getCalendar()
		);

		return $fmt->format( $date_and_time );
	}


	/**
	 *
	 * @param int $number
	 *
	 * @return string
	 */
	public function formatInt( $number )
	{

		$f = new PHP_NumberFormatter( $this->locale, PHP_NumberFormatter::DECIMAL );

		$f->setAttribute( PHP_NumberFormatter::MIN_FRACTION_DIGITS, 0 );
		$f->setAttribute( PHP_NumberFormatter::MAX_FRACTION_DIGITS, 0 );

		return $f->format( $number );
	}

	/**
	 *
	 * @param float $number
	 * @param int   $min_fraction_digits
	 * @param int   $max_fraction_digits
	 *
	 * @return string
	 */
	public function formatFloat( $number, $min_fraction_digits = 0, $max_fraction_digits = 2 )
	{

		$f = new PHP_NumberFormatter( $this->locale, PHP_NumberFormatter::DECIMAL );

		$f->setAttribute( PHP_NumberFormatter::MIN_FRACTION_DIGITS, $min_fraction_digits );
		$f->setAttribute( PHP_NumberFormatter::MAX_FRACTION_DIGITS, $max_fraction_digits );

		return $f->format( $number );
	}

	/**
	 * Format file or memory size
	 *
	 * Example:
	 *      65536 -> 64 KB
	 *
	 *
	 * @param int    $bytes
	 * @param string $unit (optional, default: B)
	 * @param int    $max_places (optional, default: 2) float precision
	 * @param string $glue (optional,default: ' ') space between number and units
	 *
	 * @return string
	 */
	public function formatSize( $bytes, $unit = 'B', $max_places = 2, $glue = ' ' )
	{

		$units = [
			$unit, 'K'.$unit, 'M'.$unit, 'G'.$unit, 'T'.$unit,
		];

		if( $bytes<=0 ) {
			return '0'.$glue.$units[0];
		}

		$exp = (int)( log( $bytes, 1024 ) );

		if( !isset( $units[$exp] ) ) {
			return $bytes;
		}

		$dv = pow( 1024, $exp );
		if( !$dv ) {
			$dv = 1;
		}

		$bytes = $bytes/$dv;

		return $this->formatFloat( $bytes, 0, $max_places ).$glue.$units[$exp];
	}

	/**
	 * @param float $value
	 * @param string $currency
	 *
	 * @return string
	 */
	public function formatCurrency( $value ,$currency )
	{
		$f = new PHP_NumberFormatter( $this->locale, PHP_NumberFormatter::CURRENCY );
		return $f->formatCurrency($value, $currency);
	}

	/**
	 *  ISO 4217
	 *
	 * @param string $currency_code
	 *
	 * @return PHP_NumberFormatter
	 */
	public function getCurrencyFormatter( $currency_code ) {
		if(!isset($this->_currency_formatter[$currency_code])) {
			$this->_currency_formatter[$currency_code] = new PHP_NumberFormatter( $this.'@currency='.$currency_code, PHP_NumberFormatter::CURRENCY );
		}
		return $this->_currency_formatter[$currency_code];
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param string $locale
	 */
	protected function _setLocale( $locale )
	{

		$data = PHP_Locale::parseLocale( $locale );
		if(
			$data &&
			!empty( $data['language'] ) &&
			!empty( $data['region'] )
		) {
			$this->region = $data['region'];
			$this->language = $data['language'];
			$this->locale = $this->language.'_'.$this->region;
		}

	}

	/**
	 * @return string
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Returns locale name (language name + region name) in locale
	 *
	 * Example: cs_CZ locale name in cs_CZ locale: čeština (Česká republika)
	 *
	 * @see PHP_Locale::getDisplayName
	 *
	 * @param string|Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 */
	public function getName( $in_locale = null )
	{
		if( !$in_locale ) {
			$in_locale = static::getCurrentLocale();
		}

		return PHP_Locale::getDisplayName( $this->locale, (string)$in_locale );
	}

	/**
	 * Returns language name in locale
	 *
	 * Example: cs_CZ locale name in cs_CZ locale: čeština
	 *
	 * @see PHP_Locale::getDisplayLanguage
	 *
	 * @param string|Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 */
	public function getLanguageName( $in_locale = null )
	{
		if( !$in_locale ) {
			$in_locale = static::getCurrentLocale();
		}

		return PHP_Locale::getDisplayLanguage( $this->locale, (string)$in_locale );
	}

	/**
	 * Returns region name in locale
	 *
	 * Example: cs_CZ region name in cs_CZ locale: Česká republika
	 *
	 * @see PHP_Locale::getDisplayRegion
	 *
	 * @param string|Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 */
	public function getRegionName( $in_locale = null )
	{
		if( !$in_locale ) {
			$in_locale = static::getCurrentLocale();
		}


		return PHP_Locale::getDisplayRegion( $this->locale, (string)$in_locale );
	}


	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return mixed
	 */
	public function toString()
	{
		return $this->locale;
	}

}
