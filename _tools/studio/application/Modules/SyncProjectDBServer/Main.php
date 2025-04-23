<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\SyncProjectDBServer;

use Jet\ErrorPages;
use Jet\Http_Request;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Service_CustomAccessControl;


class Main extends JetStudio_Module implements JetStudio_Module_Service_CustomAccessControl
{
	
	protected bool $server_activated = false;
	
	public function handleAccessControl(): bool|null
	{
		$request_uri = $_SERVER['REQUEST_URI'];
		
		if(str_contains($request_uri, '?')) {
			$request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
		}
		
		$key = str_replace( rtrim($this->getManifest()->getURL()), '', $request_uri);
		if(!$key) {
			return null;
		}
		
		$config = ServerConfig::get();
		
		if($key!=$config->getServerUrlPath()) {
			ErrorPages::handleNotFound();
		}
		
		$headers = Http_Request::headers();
		
		$key = $headers['X-J-S-Sync-DB-Key'] ?? $headers['x-j-s-sync-db-key'] ?? null;
		
		if(
			!$key ||
			$key!=$config->getServerKey()
		) {
			ErrorPages::handleUnauthorized();
			die();
		}
		
		$this->server_activated = true;
		
		return true;
	}
	
	public function getServerActivated(): bool
	{
		return $this->server_activated;
	}
	
	
}
