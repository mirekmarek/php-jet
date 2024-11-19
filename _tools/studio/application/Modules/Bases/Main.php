<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Bases;

use JetStudio\JetStudio;
use JetStudio\JetStudio_Module;

use Jet\Http_Request;

/**
 *
 */
class Main extends JetStudio_Module
{
	protected static MVCBase|bool|null $current_base = null;

	public static function getActionUrl( string $action, array $custom_get_params = [], ?string $custom_base_id = null ) : string
	{
		
		$get_params = [];
		
		if($action!='create_new_base') {
			if( Main::getCurrentBaseId() ) {
				$get_params['base'] = Main::getCurrentBaseId();
			}
		}
		
		if( $custom_base_id !== null ) {
			$get_params['base'] = $custom_base_id;
			if( !$custom_base_id ) {
				unset( $get_params['base'] );
			}
		}
		
		if( $action ) {
			$get_params['action'] = $action;
		}
		
		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}
		
		return JetStudio::getModuleManifest('Bases')->getURL().'?'.http_build_query( $get_params );
	}

	
	public static function getBases(): array
	{
		$bases = MVCBase::_getBases();
		
		uasort( $bases, function(
			MVCBase $a,
			MVCBase $b
		) {
			return strcmp( $a->getName(), $b->getName() );
		} );
		
		return $bases;
	}
	
	public static function getBase( string $id ): null|MVCBase
	{
		return MVCBase::_get( $id );
	}
	
	public static function getCurrentBaseId(): string|bool
	{
		if( static::getCurrentBase() ) {
			return static::getCurrentBase()->getId();
		}
		
		return false;
	}

	public static function getCurrentBase(): bool|MVCBase
	{
		if( static::$current_base === null ) {
			$id = Http_Request::GET()->getString( 'base' );
			
			static::$current_base = false;
			
			if(
				$id &&
				($base = static::getBase( $id ))
			) {
				static::$current_base = $base;
			}
		}
		
		return static::$current_base;
	}
	
	public function handle() : void
	{
		MVCBase::setTemplatesPath( $this->manifest->getBaseDir().'templates/' );
		
		parent::handle();
	}
	
}