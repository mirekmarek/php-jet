<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Locale
 */
namespace Jet;

class Locale extends Object {
	/**
	 * @var string[]
	 */
	protected static $all_locales = array(
		"af_ZA", "am_ET", "ar_AE", "ar_BH", "ar_DZ", "ar_EG", "ar_IQ", "ar_JO", "ar_KW", "ar_LB", "ar_LY", "ar_MA",
		"arn_CL", "ar_OM", "ar_QA", "ar_SA", "ar_SY", "ar_TN", "ar_YE", "as_IN", "az_Cyrl_AZ", "az_Latn_AZ", "ba_RU",
		"be_BY", "bg_BG", "bn_BD", "bn_IN", "bo_CN", "br_FR", "bs_Cyrl_BA", "bs_Latn_BA", "ca_ES", "co_FR", "cs_CZ",
		"cy_GB", "da_DK", "de_AT", "de_CH", "de_DE", "de_LI", "de_LU", "dsb_DE", "dv_MV", "el_GR", "en_029", "en_AU",
		"en_BZ", "en_CA", "en_GB", "en_IE", "en_IN", "en_JM", "en_MY", "en_NZ", "en_PH", "en_SG", "en_TT", "en_US",
		"en_ZA", "en_ZW", "es_AR", "es_BO", "es_CL", "es_CO", "es_CR", "es_DO", "es_EC", "es_ES", "es_GT", "es_HN",
		"es_MX", "es_NI", "es_PA", "es_PE", "es_PR", "es_PY", "es_SV", "es_US", "es_UY", "es_VE", "et_EE", "eu_ES",
		"fa_IR", "fi_FI", "fil_PH", "fo_FO", "fr_BE", "fr_CA", "fr_CH", "fr_FR", "fr_LU", "fr_MC", "fy_NL", "ga_IE",
		"gd_GB", "gl_ES", "gsw_FR", "gu_IN", "ha_Latn_NG", "he_IL", "hi_IN", "hr_BA", "hr_HR", "hsb_DE", "hu_HU",
		"hy_AM", "id_ID", "ig_NG", "ii_CN", "is_IS", "it_CH", "it_IT", "iu_Cans_CA", "iu_Latn_CA", "ja_JP", "ka_GE",
		"kk_KZ", "kl_GL", "km_KH", "kn_IN", "kok_IN", "ko_KR", "ky_KG", "lb_LU", "lo_LA", "lt_LT", "lv_LV", "mi_NZ",
		"mk_MK", "ml_IN", "mn_MN", "mn_Mong_CN", "moh_CA", "mr_IN", "ms_BN", "ms_MY", "mt_MT", "nb_NO", "ne_NP", "nl_BE",
		"nl_NL", "nn_NO", "nso_ZA", "oc_FR", "or_IN", "pa_IN", "pl_PL", "prs_AF", "ps_AF", "pt_BR", "pt_PT", "qut_GT",
		"quz_BO", "quz_EC", "quz_PE", "rm_CH", "ro_RO", "ru_RU", "rw_RW", "sah_RU", "sa_IN", "se_FI", "se_NO", "se_SE",
		"si_LK", "sk_SK", "sl_SI", "sma_NO", "sma_SE", "smj_NO", "smj_SE", "smn_FI", "sms_FI", "sq_AL", "sr_Cyrl_BA",
		"sr_Cyrl_CS", "sr_Cyrl_ME", "sr_Cyrl_RS", "sr_Latn_BA", "sr_Latn_CS", "sr_Latn_ME", "sr_Latn_RS", "sv_FI",
		"sv_SE", "sw_KE", "syr_SY", "ta_IN", "te_IN", "tg_Cyrl_TJ", "th_TH", "tk_TM", "tn_ZA", "tr_TR", "tt_RU",
		"tzm_Latn_DZ", "ug_CN", "uk_UA", "ur_PK", "uz_Cyrl_UZ", "uz_Latn_UZ", "vi_VN", "wo_SN", "xh_ZA", "yo_NG",
		"zh_CN", "zh_HK", "zh_MO", "zh_SG", "zh_TW", "zu_ZA"
	);

	/**
	 * Example en_US, cs_CZ and so on
	 * @var string
	 */
	protected $locale = "";

	/**
	 * Example: CZ, US, ...
	 *
	 * @var string
	 */
	protected $region = "";

	/**
	 * Example: en, cs, sk, ...
	 *
	 * @var string
	 */
	protected $language = "";

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
	 * @see \IntlDateFormatter
	 *
	 * \IntlDateFormatter::GREGORIAN by default
	 *
	 * @var int
	 */
	protected $_calendar;


	/**
	 *
	 * @param string $locale
	 */
	public function __construct( $locale = null ) {
		if($locale) {
			$this->_setLocale( $locale );
		}
	}

	/**
	 * @param string $locale
	 */
	protected function _setLocale( $locale ) {
		$data = \Locale::parseLocale($locale);
		if(
			$data &&
			!empty($data["language"]) &&
			!empty($data["region"])
		) {
			$this->region = $data["region"];
			$this->language = $data["language"];
			$this->locale = $this->language."_".$this->region;
		}
	}

	/**
	 * @return string
	 */
	public function getTimeZone() {
		if(!$this->_timezone) {
			$this->_timezone = date_default_timezone_get();
		}
		return $this->_timezone;
	}

	/**
	 *
	 * @param string $time_zone
	 */
	public function setTimeZone( $time_zone ) {
		$this->_timezone = $time_zone;
	}

	/**
	 * @return int
	 */
	public function getCalendar() {
		if($this->_calendar===null) {
			$this->_calendar = \IntlDateFormatter::GREGORIAN;
		}

		return $this->_calendar;
	}

	/**
	 * @param int $calendar
	 */
	public function setCalendar($calendar) {
		$this->_calendar = $calendar;
	}


	/**
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @return mixed
	 */
	public function toString() {
		return $this->locale;
	}

	/**
	 * @return mixed
	 */
	public function __toString() {
		return $this->toString();
	}


	/**
	 * Returns locale name (language name + region name) in locale
	 *
	 * Example: cs_CZ locale name in cs_CZ locale: čeština (Česká republika)
	 *
	 * @see Locale::getDisplayName
	 *
	 * @param string|Locale $in_locale (optional, default: current locale)
	 *
	 * @return string
	 */
	public function getName( $in_locale=null ) {
		if(!$in_locale) {
			$in_locale = Mvc::getCurrentLocale();
		}

		return \Locale::getDisplayName( $this->locale, (string)$in_locale );
	}


	/**
	 * Returns date and time formatted by locale
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public function formatDateAndTime( DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM  ) {
		if(!$date_and_time){
			return "";
		}

		$fmt = new \IntlDateFormatter(
			$this->locale,
			$format,
			\IntlDateFormatter::SHORT,
			$this->getTimeZone(),
			$this->getCalendar()
		);
		return $fmt->format($date_and_time);
	}

	/**
	 * Returns date and time formatted by current locale
	 *
	 * Alias of: Mvc::getCurrentLocale()->formatDateAdnTime($date_and_time);
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public static function dateAndTime( DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM ) {
		return Mvc::getCurrentLocale()->formatDateAndTime($date_and_time, $format);
	}

	/**
	 * Returns date formatted by locale
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public function formatDate(  DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM ) {
		if(!$date_and_time){
			return "";
		}
		$fmt = new \IntlDateFormatter(
			$this->locale,
			$format,
			\IntlDateFormatter::NONE,
			$this->getTimeZone(),
			$this->getCalendar()
		);

		return $fmt->format($date_and_time);
	}

	/**
	 * Returns date formatted by current locale
	 *
	 * Alias of: Mvc::getCurrentLocale()->formatDate($date_and_time);
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public static function date(  DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM  ) {
		return Mvc::getCurrentLocale()->formatDate($date_and_time, $format);
	}

	/**
	 * Returns date formatted by locale
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public function formatTime(  DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM  ) {
		if(!$date_and_time){
			return "";
		}

		$fmt = new \IntlDateFormatter(
			$this->locale,
			\IntlDateFormatter::NONE,
			$format,
			$this->getTimeZone(),
			$this->getCalendar()
		);

		return $fmt->format($date_and_time);
	}

	/**
	 * Returns date formatted by current locale
	 *
	 * Alias of: Mvc::getCurrentLocale()->formatTime($date_and_time)
	 *
	 * @param DateTime $date_and_time
	 * @param int $format (optional, default: \IntlDateFormatter::MEDIUM)
	 *
	 * @return string
	 */
	public function time(  DateTime $date_and_time, $format=\IntlDateFormatter::MEDIUM  ) {
		return Mvc::getCurrentLocale()->formatTime($date_and_time, $format);
	}

	/**
	 * Format giving number according to locale
	 *
	 * @param int $number
	 *
	 * @return string
	 */
	public function formatInt( $number ) {

		$f = new \NumberFormatter( $this->locale, \NumberFormatter::DECIMAL);

		$f->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
		$f->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);

		return $f->format($number);
	}

	/**
	 * Format number according to current locale
	 *
	 * Alias of: Mvc::getCurrentLocale()->formatInt($number)
	 *
	 * @param int $number
	 *
	 * @return string
	 */
	public static function int( $number ) {
		return Mvc::getCurrentLocale()->formatInt($number);
	}

	/**
	 * Format number according to locale
	 *
	 * @param float $number
	 * @param int $min_fraction_digits
	 * @param int $max_fraction_digits
	 *
	 * @return string
	 */
	public function formatFloat( $number, $min_fraction_digits=0, $max_fraction_digits=2 ) {

		$f = new \NumberFormatter( $this->locale, \NumberFormatter::DECIMAL);

		$f->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $min_fraction_digits);
		$f->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $max_fraction_digits);

		return $f->format($number);
	}

	/**
	 * Format number according to current locale
	 *
	 * Alias of: Mvc::getCurrentLocale()->formatFloat($number, $min_fraction_digits, $max_fraction_digits)
	 *
	 * @param float $number
	 * @param int $min_fraction_digits
	 * @param int $max_fraction_digits
	 *
	 * @return string
	 */
	public static function float( $number, $min_fraction_digits=0, $max_fraction_digits=2 ) {
		return Mvc::getCurrentLocale()->formatFloat($number, $min_fraction_digits, $max_fraction_digits);
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
	 * @param string $glue (optional,default: " ") space between number and units
	 *
	 * @return string
	 */
	public function formatSize( $bytes, $unit = "B", $max_places = 2, $glue=" " ) {

		$units = array(
			$unit,
			"K".$unit,
			"M".$unit,
			"B".$unit,
			"T".$unit
		);

		if( $bytes<=0){
			return "0".$glue.$units[0];
		}

		$exp = (int)(log($bytes, 1024));

		if(!isset($units[$exp])) {
			return $bytes;
		}

		$dv = pow( 1024,$exp );
		if(!$dv) {
			$dv = 1;
		}

		$bytes = $bytes / $dv;

		return $this->formatFloat($bytes, 0, $max_places).$glue.$units[$exp];
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
	 * @param string $glue (optional,default: " ") space between number and units
	 *
	 * @return string
	 */
	public static function size( $bytes, $unit = "B", $max_places = 2, $glue=" " ) {
		return Mvc::getCurrentLocale()->formatSize($bytes, $unit, $max_places, $glue);
	}

	/**
	 *
	 * @param null|string|Locale $in_locale (optional, default: current locale)
	 *
	 * @return array
	 */
	public static function getAllLocalesList( $in_locale=null ) {
		if(!$in_locale) {
			$in_locale = Mvc::getCurrentLocale();
		}

		$result = array();
		foreach(static::$all_locales as $locale) {
			$result[$locale] = \Locale::getDisplayName( $locale, $in_locale );
		}

		asort($result);

		return $result;
	}

}
