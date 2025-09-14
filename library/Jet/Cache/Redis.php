<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Redis;
use RedisException;


require_once 'Abstract.php';

/**
 *
 */
class Cache_Redis extends Cache_Abstract
{

	/**
	 * @var string
	 */
	protected string $host = '';

	/**
	 * @var int
	 */
	protected int $port = 0;

	/**
	 * @var Redis|null
	 */
	protected ?Redis $client = null;

	/**
	 * @var ?bool
	 */
	protected ?bool $is_active = null;

	/**
	 * @return bool
	 */
	public static function getRedisInstalled(): bool
	{
		return class_exists( '\Redis', false );
	}

	/**
	 * Cache_Redis constructor.
	 * @param string $host
	 * @param int $port
	 */
	public function __construct( string $host = '127.0.0.1', int $port = 6379 )
	{
		$this->host = $host;
		$this->port = $port;
	}

	/**
	 * @return bool
	 */
	public function connect(): bool
	{
		if( $this->is_active !== null ) {
			return $this->is_active;
		}

		if( !static::getRedisInstalled() ) {
			$this->is_active = false;
			return false;
		}


		$ok = true;
		try {
			$this->client = new Redis();

			if( !$this->client->connect( $this->host, $this->port ) ) {
				$ok = false;
			}
		} /** @noinspection PhpUnusedLocalVariableInspection */ catch( RedisException $e ) {
			$ok = false;
		}

		if( !$ok ) {
			$this->is_active = false;
			$this->client = null;

			return false;
		}

		$this->is_active = true;

		return true;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->connect();
	}


	/**
	 * @param string $key
	 */
	public function delete( string $key ): void
	{
		if( !$this->connect() ) {
			return;
		}

		$this->client->del( $key );
	}

	/**
	 * @param string $prefix
	 */
	public function deleteItems( string $prefix ): void
	{
		if( !$this->connect() ) {
			return;
		}

		$keys = $this->client->keys( $prefix . '*' );

		if( $keys ) {
			$this->client->del( $keys );
		}
	}

	/**
	 * @param string $key
	 * @param mixed $data
	 */
	protected function set( string $key, mixed $data ): void
	{
		if( !$this->connect() ) {
			return;
		}

		$this->client->set( $key, serialize( $data ) );

	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	protected function get( string $key ): mixed
	{

		if( !$this->connect() ) {
			return null;
		}

		$data = $this->client->get( $key );

		if( !$data ) {
			return null;
		}

		$data = Debug_ErrorHandler::doItSilent(function() use ($data) {
			return unserialize( $data );
		});


		if( !$data ) {
			return null;
		}

		return $data;
	}
	
	
	protected function readData( string $key ): ?Cache_Record_Data
	{
		if(!$this->isActive()) {
			return null;
		}
		
		$data = $this->get( $key );
		if( !$data ) {
			return null;
		}
		
		return new Cache_Record_Data(
			key: $key,
			data: $data['data'],
			timestamp: $data['timestamp'],
		);
	}
	
	protected function writeData( string $key, mixed $data ): void
	{
		if(!$this->isActive()) {
			return;
		}
		
		$this->set( $key, [
			'timestamp' => time(),
			'data' => $data,
		] );
	}
	
	protected function readHtml( string $key ): ?Cache_Record_HTMLSnippet
	{
		if(!$this->isActive()) {
			return null;
		}
		
		$data = $this->get( $key );
		if( !$data ) {
			return null;
		}
		
		return new Cache_Record_HTMLSnippet(
			key: $key,
			html: $data['html'],
			timestamp: $data['timestamp'],
		);
	}
	
	protected function writeHtml( string $key, string $html ): void
	{
		if(!$this->isActive()) {
			return;
		}
		
		$this->set( $key, [
			'timestamp' => time(),
			'html' => $html,
		] );
	}
	
	public function resetHtmlFiles( string $prefix ): void
	{
		$this->deleteItems($prefix);
	}
	
	public function resetHtmlFile( string $key ): void
	{
		$this->delete( $key );
	}
}