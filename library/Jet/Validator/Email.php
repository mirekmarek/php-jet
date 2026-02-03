<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Email extends Validator
{
	protected static string $type = self::TYPE_EMAIL;
	
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	public const ERROR_CODE_INVALID_DOMAIN = 'invalid_domain';
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_BOOL,
		label: 'Check e-mail by DNS',
		getter: 'getCheckDomainEnabled',
		setter: 'setCheckDomainEnabled',
	)]
	protected bool $check_domain_enabled = true;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
		self::ERROR_CODE_INVALID_DOMAIN => 'Invalid e-mail domain',
	];
	
	public function getCheckDomainEnabled(): bool
	{
		return $this->check_domain_enabled;
	}
	
	public function setCheckDomainEnabled( bool $check_domain_enabled ): void
	{
		$this->check_domain_enabled = $check_domain_enabled;
	}
	
	
	
	
	public function validate_value( mixed $value ): bool
	{
		if(
			$value &&
			!filter_var( $value, FILTER_VALIDATE_EMAIL )
		) {
			$this->setError( self::ERROR_CODE_INVALID_FORMAT );
			
			return false;
		}
		
		$domain = explode('@', $value)[1];
		
		
		if($this->getCheckDomainEnabled()) {
			if( !$this->checkDomain($domain) ) {
				$this->setError( self::ERROR_CODE_INVALID_DOMAIN );
				
				return false;
			}
		}
		
		return true;
	}
	
	
	protected function checkDomain( string $domain ) : bool
	{
		if(!filter_var($domain, FILTER_VALIDATE_DOMAIN )) {
			return false;
		}
		
		if( !checkdnsrr($domain, 'MX') ) {
			return false;
		}
		
		return true;
		
	}
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		
		return $codes;
	}
}