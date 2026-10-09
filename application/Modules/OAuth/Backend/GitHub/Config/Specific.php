<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Backend\GitHub;

use Jet\Config_Definition;
use Jet\Form_Definition;
use JetApplication\OAuth_BackendModule_Config;

#[Config_Definition(
	name: 'OAuth-GitHub'
)]
class Config_Specific extends OAuth_BackendModule_Config
{
	#[Config_Definition]
	#[Form_Definition]
	protected string $oauth_endpoint_URL = 'https://github.com/login/oauth/authorize';
	
	#[Config_Definition]
	#[Form_Definition]
	protected string $token_endpoint_URL = 'https://github.com/login/oauth/access_token';
	
	#[Config_Definition]
	#[Form_Definition]
	protected string $user_detail_endpoint_URL = 'https://api.github.com/user';

}