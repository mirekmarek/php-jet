<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

abstract class OAuth_UserHandler
{
	protected string $oauth_service = '';
	
	protected string $oauth_user_id = '';
	
	protected string $oauth_user_email = '';
	
	public function getOauthService(): string
	{
		return $this->oauth_service;
	}
	
	public function setOauthService( string $oauth_service ): void
	{
		$this->oauth_service = $oauth_service;
	}
	
	public function getOauthUserId(): string
	{
		return $this->oauth_user_id;
	}
	
	public function setOauthUserId( string $oauth_user_id ): void
	{
		$this->oauth_user_id = $oauth_user_id;
	}
	
	public function getOauthUserEmail(): string
	{
		return $this->oauth_user_email;
	}
	
	public function setOauthUserEmail( string $oauth_user_email ): void
	{
		$this->oauth_user_email = $oauth_user_email;
	}
	
	
	abstract public function handle() : void;
}