<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use IntlDateFormatter as PHP_IntlDateFormatter;
use NumberFormatter as PHP_NumberFormatter;
use Locale as PHP_Locale;


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
	 * Long style (January 12, 1952, 3:30:32pm)
	 * @link http://php.net/manual/en/intl.intldateformatter-constants.php
	 */
	const DATE_TIME_FORMAT_LONG = PHP_IntlDateFormatter::LONG;

	/**
	 * Completely specified style (Tuesday, April 12, 1952 AD, 3:30:42pm PST)
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
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;

	/**
	 * @var string[]
	 */
	protected static array $all_locales = [
		'af_ZA',
		'am_ET',
		'ar_AE',
		'ar_BH',
		'ar_DZ',
		'ar_EG',
		'ar_IQ',
		'ar_JO',
		'ar_KW',
		'ar_LB',
		'ar_LY',
		'ar_MA',
		'arn_CL',
		'ar_OM',
		'ar_QA',
		'ar_SA',
		'ar_SY',
		'ar_TN',
		'ar_YE',
		'as_IN',
		'az_Cyrl_AZ',
		'az_Latn_AZ',
		'ba_RU',
		'be_BY',
		'bg_BG',
		'bn_BD',
		'bn_IN',
		'bo_CN',
		'br_FR',
		'bs_Cyrl_BA',
		'bs_Latn_BA',
		'ca_ES',
		'co_FR',
		'cs_CZ',
		'cy_GB',
		'da_DK',
		'de_AT',
		'de_CH',
		'de_DE',
		'de_LI',
		'de_LU',
		'dsb_DE',
		'dv_MV',
		'el_GR',
		'en_029',
		'en_AU',
		'en_BZ',
		'en_CA',
		'en_GB',
		'en_IE',
		'en_IN',
		'en_JM',
		'en_MY',
		'en_NZ',
		'en_PH',
		'en_SG',
		'en_TT',
		'en_US',
		'en_ZA',
		'en_ZW',
		'es_AR',
		'es_BO',
		'es_CL',
		'es_CO',
		'es_CR',
		'es_DO',
		'es_EC',
		'es_ES',
		'es_GT',
		'es_HN',
		'es_MX',
		'es_NI',
		'es_PA',
		'es_PE',
		'es_PR',
		'es_PY',
		'es_SV',
		'es_US',
		'es_UY',
		'es_VE',
		'et_EE',
		'eu_ES',
		'fa_IR',
		'fi_FI',
		'fil_PH',
		'fo_FO',
		'fr_BE',
		'fr_CA',
		'fr_CH',
		'fr_FR',
		'fr_LU',
		'fr_MC',
		'fy_NL',
		'ga_IE',
		'gd_GB',
		'gl_ES',
		'gsw_FR',
		'gu_IN',
		'ha_Latn_NG',
		'he_IL',
		'hi_IN',
		'hr_BA',
		'hr_HR',
		'hsb_DE',
		'hu_HU',
		'hy_AM',
		'id_ID',
		'ig_NG',
		'ii_CN',
		'is_IS',
		'it_CH',
		'it_IT',
		'iu_Cans_CA',
		'iu_Latn_CA',
		'ja_JP',
		'ka_GE',
		'kk_KZ',
		'kl_GL',
		'km_KH',
		'kn_IN',
		'kok_IN',
		'ko_KR',
		'ky_KG',
		'lb_LU',
		'lo_LA',
		'lt_LT',
		'lv_LV',
		'mi_NZ',
		'mk_MK',
		'ml_IN',
		'mn_MN',
		'mn_Mong_CN',
		'moh_CA',
		'mr_IN',
		'ms_BN',
		'ms_MY',
		'mt_MT',
		'nb_NO',
		'ne_NP',
		'nl_BE',
		'nl_NL',
		'nn_NO',
		'nso_ZA',
		'oc_FR',
		'or_IN',
		'pa_IN',
		'pl_PL',
		'prs_AF',
		'ps_AF',
		'pt_BR',
		'pt_PT',
		'qut_GT',
		'quz_BO',
		'quz_EC',
		'quz_PE',
		'rm_CH',
		'ro_RO',
		'ru_RU',
		'rw_RW',
		'sah_RU',
		'sa_IN',
		'se_FI',
		'se_NO',
		'se_SE',
		'si_LK',
		'sk_SK',
		'sl_SI',
		'sma_NO',
		'sma_SE',
		'smj_NO',
		'smj_SE',
		'smn_FI',
		'sms_FI',
		'sq_AL',
		'sr_Cyrl_BA',
		'sr_Cyrl_CS',
		'sr_Cyrl_ME',
		'sr_Cyrl_RS',
		'sr_Latn_BA',
		'sr_Latn_CS',
		'sr_Latn_ME',
		'sr_Latn_RS',
		'sv_FI',
		'sv_SE',
		'sw_KE',
		'syr_SY',
		'ta_IN',
		'te_IN',
		'tg_Cyrl_TJ',
		'th_TH',
		'tk_TM',
		'tn_ZA',
		'tr_TR',
		'tt_RU',
		'tzm_Latn_DZ',
		'ug_CN',
		'uk_UA',
		'ur_PK',
		'uz_Cyrl_UZ',
		'uz_Latn_UZ',
		'vi_VN',
		'wo_SN',
		'xh_ZA',
		'yo_NG',
		'zh_CN',
		'zh_HK',
		'zh_MO',
		'zh_SG',
		'zh_TW',
		'zu_ZA',
	];

	/**
	 * Example en_US, cs_CZ and so on
	 * @var string
	 */
	protected string $locale = '';

	/**
	 * Example: CZ, US, ...
	 *
	 * @var string
	 */
	protected string $region = '';

	/**
	 * Example: en, cs, sk, ...
	 *
	 * @var string
	 */
	protected string $language = '';

	/**
	 *
	 * @see http://www.php.net/manual/en/book.datetime.php
	 *
	 * date_default_timezone_get() value by default
	 *
	 * @var string
	 */
	protected string $_timezone = '';

	/**
	 *
	 * @var int
	 */
	protected int $_calendar = 0;

	/**
	 * @var array
	 */
	protected array $_currency_formatter = [];

	/**
	 *
	 * @param null|Locale $in_locale (optional, default: current locale)
	 *
	 * @return array
	 */
	public static function getAllLocalesList( null|Locale $in_locale = null ) : array
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
	public static function getCurrentLocale(): Locale
	{
		return static::$current_locale;
	}

	/**
	 * @param Locale $current_locale
	 */
	public static function setCurrentLocale( Locale $current_locale ): void
	{
		static::$current_locale = $current_locale;
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatDate($date_and_time);
	 *
	 * @param ?Data_DateTime $date_and_time
	 * @param int $format
	 *
	 * @return string
	 */
	public static function date( ?Data_DateTime $date_and_time, int $format = self::DATE_TIME_FORMAT_MEDIUM ): string
	{
		return static::getCurrentLocale()->formatDate( $date_and_time, $format );
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatDateAdnTime($date_and_time);
	 *
	 * @param Data_DateTime|null $date_and_time
	 * @param int $date_format
	 * @param int $time_format
	 *
	 * @return string
	 */
	public static function dateAndTime( ?Data_DateTime $date_and_time,
	                                    int $date_format = self::DATE_TIME_FORMAT_MEDIUM,
	                                    int $time_format = self::DATE_TIME_FORMAT_SHORT ): string
	{
		return static::getCurrentLocale()->formatDateAndTime( $date_and_time, $date_format, $time_format );
	}


	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatTime($date_and_time);
	 *
	 * @param Data_DateTime|null $date_and_time
	 * @param int $time_format
	 *
	 * @return string
	 */
	public static function time( ?Data_DateTime $date_and_time, int $time_format = self::DATE_TIME_FORMAT_SHORT ): string
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
	public static function int( int $number ): string
	{
		return static::getCurrentLocale()->formatInt( $number );
	}

	/**
	 *
	 * Alias of: Locale::getCurrentLocale()->formatFloat($number, $min_fraction_digits, $max_fraction_digits)
	 *
	 * @param float $number
	 * @param int $min_fraction_digits
	 * @param int $max_fraction_digits
	 *
	 * @return string
	 */
	public static function float( float $number, int $min_fraction_digits = 0, int $max_fraction_digits = 2 ): string
	{
		return static::getCurrentLocale()->formatFloat( $number, $min_fraction_digits, $max_fraction_digits );
	}

	/**
	 * Format file or memory size according to current locale
	 *
	 * Example:
	 *      65536 -> 64 KB
	 *
	 * @param int $bytes
	 * @param string $unit (optional, default: B)
	 * @param int $max_places (optional, default: 2) float precision
	 * @param string $glue (optional,default: ' ') space between number and units
	 *
	 * @return string
	 */
	public static function size( int $bytes, string $unit = 'iB', int $max_places = 2, string $glue = ' ' ): string
	{
		return static::getCurrentLocale()->formatSize( $bytes, $unit, $max_places, $glue );
	}

	/**
	 *
	 * @param float|int $value
	 * @param string $currency
	 *
	 * @return string
	 */
	public static function currency( float|int $value, string $currency ): string
	{
		return static::getCurrentLocale()->formatCurrency( $value, $currency );

	}

	/**
	 *
	 * @param string|null $locale
	 */
	public function __construct( string|null $locale = null )
	{
		if( $locale ) {
			$this->_setLocale( $locale );
		}
	}

	/**
	 * @return string
	 */
	public function getTimeZone(): string
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
	public function setTimeZone( string $time_zone ): void
	{
		$this->_timezone = $time_zone;
	}

	/**
	 * @return int
	 */
	public function getCalendar(): int
	{
		if( !$this->_calendar ) {
			$this->_calendar = self::CALENDAR_GREGORIAN;
		}

		return $this->_calendar;
	}

	/**
	 * @param int $calendar
	 */
	public function setCalendar( int $calendar ): void
	{
		$this->_calendar = $calendar;
	}

	/**
	 * Returns date formatted by locale
	 *
	 * @param ?Data_DateTime $date_and_time
	 * @param int $format
	 *
	 * @return string
	 */
	public function formatDate( ?Data_DateTime $date_and_time, int $format = self::DATE_TIME_FORMAT_MEDIUM ): string
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
	 * @param ?Data_DateTime $date_and_time
	 * @param int $date_format
	 * @param int $time_format
	 *
	 * @return string
	 */
	public function formatDateAndTime( ?Data_DateTime $date_and_time,
	                                   int $date_format = self::DATE_TIME_FORMAT_MEDIUM,
	                                   int $time_format = self::DATE_TIME_FORMAT_SHORT ): string
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
	 * @param ?Data_DateTime $date_and_time
	 * @param int $time_format
	 *
	 * @return string
	 */
	public function formatTime( ?Data_DateTime $date_and_time, int $time_format = self::DATE_TIME_FORMAT_SHORT ): string
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
	public function formatInt( int $number ): string
	{

		$f = new PHP_NumberFormatter( $this->locale, PHP_NumberFormatter::DECIMAL );

		$f->setAttribute( PHP_NumberFormatter::MIN_FRACTION_DIGITS, 0 );
		$f->setAttribute( PHP_NumberFormatter::MAX_FRACTION_DIGITS, 0 );

		return $f->format( $number );
	}

	/**
	 *
	 * @param float $number
	 * @param int $min_fraction_digits
	 * @param int $max_fraction_digits
	 *
	 * @return string
	 */
	public function formatFloat( float $number, int $min_fraction_digits = 0, int $max_fraction_digits = 2 ): string
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
	 * @param int $bytes
	 * @param string $unit (optional, default: B)
	 * @param int $max_places (optional, default: 2) float precision
	 * @param string $glue (optional,default: ' ') space between number and units
	 *
	 * @return string
	 */
	public function formatSize( int $bytes, string $unit = 'iB', int $max_places = 2, string $glue = ' ' ): string
	{

		$units = [
			$unit,
			'K' . $unit,
			'M' . $unit,
			'G' . $unit,
			'T' . $unit,
		];

		if( $bytes <= 0 ) {
			return '0' . $glue . $units[0];
		}

		$exp = (int)(log( $bytes, 1024 ));

		if( !isset( $units[$exp] ) ) {
			return $bytes;
		}

		$dv = pow( 1024, $exp );
		if( !$dv ) {
			$dv = 1;
		}

		$bytes = $bytes / $dv;

		return $this->formatFloat( $bytes, 0, $max_places ) . $glue . $units[$exp];
	}

	/**
	 * @param float|int $value
	 * @param string $currency
	 *
	 * @return string
	 */
	public function formatCurrency( float|int $value, string $currency ) : string
	{
		$f = new PHP_NumberFormatter( $this->locale, PHP_NumberFormatter::CURRENCY );
		return $f->formatCurrency( $value, $currency );
	}

	/**
	 *  ISO 4217
	 *
	 * @param string $currency
	 *
	 * @return PHP_NumberFormatter
	 */
	public function getCurrencyFormatter( string $currency ): PHP_NumberFormatter
	{
		if( !isset( $this->_currency_formatter[$currency] ) ) {
			$this->_currency_formatter[$currency] = new PHP_NumberFormatter( $this . '@currency=' . $currency, PHP_NumberFormatter::CURRENCY );
		}
		return $this->_currency_formatter[$currency];
	}

	/**
	 * @return string
	 */
	public function getLocale(): string
	{
		return $this->locale;
	}

	/**
	 * @param string $locale
	 */
	protected function _setLocale( string $locale ): void
	{

		$data = PHP_Locale::parseLocale( $locale );
		if(
			$data &&
			!empty( $data['language'] ) &&
			!empty( $data['region'] )
		) {
			$this->region = $data['region'];
			$this->language = $data['language'];
			$this->locale = $this->language . '_' . $this->region;
		}

	}

	/**
	 * @return string
	 */
	public function getRegion(): string
	{
		return $this->region;
	}

	/**
	 * @return string
	 */
	public function getLanguage(): string
	{
		return $this->language;
	}

	/**
	 * Returns locale name (language name + region name) in locale
	 *
	 * Example: cs_CZ locale name in cs_CZ locale: čeština (Česká republika)
	 *
	 * @param Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 * @see PHP_Locale::getDisplayName
	 *
	 */
	public function getName( Locale|null $in_locale = null ): string
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
	 * @param Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 * @see PHP_Locale::getDisplayLanguage
	 *
	 */
	public function getLanguageName( Locale|null $in_locale = null ): string
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
	 * @param Locale|null $in_locale (optional, default: current locale)
	 *
	 * @return string
	 * @see PHP_Locale::getDisplayRegion
	 *
	 */
	public function getRegionName( Locale|null $in_locale = null ): string
	{
		if( !$in_locale ) {
			$in_locale = static::getCurrentLocale();
		}


		return PHP_Locale::getDisplayRegion( $this->locale, (string)$in_locale );
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->locale;
	}

}
