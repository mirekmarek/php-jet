<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Auth_User_Interface;
use Jet\Data_DateTime;
use Jet\Locale;

interface Auth_APIUser extends Auth_User_Interface
{
	public function getId(): int;
	public function getEmail(): string;
	public function setEmail( string $email ): void;
	public function getLocale(): ?Locale;
	public function setLocale( Locale|string $locale ): void;
	public function getName(): string;
	public function getDescription(): string;
	public function setDescription( string $description ): void;
	public function isBlocked(): bool;
	public function isBlockedTill(): null|Data_DateTime;
	public function block( string|Data_DateTime|null $till = null ) : void;
	public function unBlock() : void;

	public function resetPassword(): void;

	public function verifyPasswordStrength( string $password ): bool;

	
	
	/**
	 * @param bool $get_as_string
	 * @return array<string,string|Locale>
	 */
	public static function getLocales( bool $get_as_string=true ): array;
	
	public function sendWelcomeEmail( string $password ): void;
	public function getUserIsBlocked(): bool;
	public function setUserIsBlocked( bool $user_is_blocked ): void;
	public function getUserIsBlockedTill(): ?Data_DateTime;
	public function setUserIsBlockedTill( ?Data_DateTime $user_is_blocked_till ): void;
	
}