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
class Validator_Tel extends Validator
{
	protected static string $type = self::TYPE_TEL;
	
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	public const ERROR_CODE_INVALID_TEL_NUMBER_TYPE = 'invalid_tel_number_type';
	
	public const TEL_NUMBER_TYPE_GENERAL = 'general';
	public const TEL_NUMBER_TYPE_FIXED = 'fixed';
	public const TEL_NUMBER_TYPE_EMERGENCY = 'emergency';
	public const TEL_NUMBER_TYPE_MOBILE = 'mobile';
	public const TEL_NUMBER_TYPE_TOLL_FREE = 'toll_free';
	public const TEL_NUMBER_TYPE_PREMIUM = 'premium';
	public const TEL_NUMBER_TYPE_SHARED = 'shared';
	public const TEL_NUMBER_TYPE_UAN = 'uan';
	public const TEL_NUMBER_TYPE_pager = 'pager';
	public const TEL_NUMBER_TYPE_PERSONAL = 'personal';
	public const TEL_NUMBER_TYPE_VOIP = 'voip';
	public const TEL_NUMBER_TYPE_SHORTCODE = 'shortcode';
	public const TEL_NUMBER_TYPE_VOICEMAIL = 'voicemail';
	
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
		self::ERROR_CODE_INVALID_TEL_NUMBER_TYPE => 'Invalid telephone number type',
	];
	
	protected ?Locale $locale = null;
	
	protected string $tel_prefix = '';
	protected array $patterns = [];
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_BOOL,
		label: 'Telephone number with prefix',
		getter: 'getTelNumberWithPrefix',
		setter: 'setTelNumberWithPrefix',
	)]
	protected bool $tel_number_with_prefix = false;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_ARRAY,
		label: 'Telephone number with prefix',
		getter: 'getAllowedTelNumberTypes',
		setter: 'setAllowedTelNumberTypes',
	)]
	protected array $allowed_tel_number_types = [
		self::TEL_NUMBER_TYPE_FIXED,
		self::TEL_NUMBER_TYPE_MOBILE,
		self::TEL_NUMBER_TYPE_PERSONAL,
	];
	
	public function getLocale(): ?Locale
	{
		if(!$this->locale) {
			$this->locale = Locale::getCurrentLocale();
		}
		return $this->locale;
	}
	
	public function setLocale( ?Locale $locale ): void
	{
		$this->locale = $locale;
		$this->initData();
	}
	
	protected function initData() : void
	{
		$data = require __DIR__.'/Tel/Data/'.$this->getLocale()->getRegion().'.php';
		
		$this->tel_prefix = $data['prefix'];
		$this->patterns = $data['patterns'];
	}
	
	public function getTelPrefix(): string
	{
		$this->initData();
		return $this->tel_prefix;
	}
	
	public function getPatterns(): array
	{
		$this->initData();
		
		return $this->patterns;
	}
	
	public function getTelNumberWithPrefix(): bool
	{
		return $this->tel_number_with_prefix;
	}
	
	public function setTelNumberWithPrefix( bool $tel_number_with_prefix ): void
	{
		$this->tel_number_with_prefix = $tel_number_with_prefix;
	}
	
	public function getAllowedTelNumberTypes(): array
	{
		return $this->allowed_tel_number_types;
	}
	
	public function setAllowedTelNumberTypes( array $allowed_tel_number_types ): void
	{
		$this->allowed_tel_number_types = $allowed_tel_number_types;
	}
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		$codes[] = static::ERROR_CODE_INVALID_TEL_NUMBER_TYPE;
		
		return $codes;
	}
	
	public function validate_value( mixed $value ): bool
	{
		$this->initData();
		
		if($this->getTelNumberWithPrefix()) {
			if(!str_starts_with($value, $this->tel_prefix)) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT, ['tel_prefix'=>$this->tel_prefix] );
				return false;
			}
			
			$value = substr($value, strlen($this->tel_prefix));
		}
		
		$detected_tel_number_type = null;
		
		foreach($this->patterns as $tel_number_type=>$pattern) {
			if( $tel_number_type===static::TEL_NUMBER_TYPE_GENERAL ) {
				continue;
			}
			
			if(preg_match($pattern, $value)) {
				$detected_tel_number_type = $tel_number_type;
				break;
			}
		}
		
		
		if(!$detected_tel_number_type) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT, [ 'detected_tel_number_type' => 'unknown' ] );
			return false;
		}
		
		if(!in_array($detected_tel_number_type, $this->getAllowedTelNumberTypes())) {
			$this->setError( self::ERROR_CODE_INVALID_TEL_NUMBER_TYPE, [ 'detected_tel_number_type' => $detected_tel_number_type ] );
			return false;
		}
		
		return true;
	}
}