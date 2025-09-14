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
class Cache_Record_Data
{
	protected string $key = '';
	protected mixed $data = null;
	protected int $timestamp = 0;
	
	public function __construct( string $key, mixed $data, int $timestamp )
	{
		$this->key = $key;
		$this->data = $data;
		$this->timestamp = $timestamp;
	}
	
	public function getKey(): string
	{
		return $this->key;
	}
	
	public function getData(): mixed
	{
		return $this->data;
	}
	
	public function getTimestamp(): int
	{
		return $this->timestamp;
	}
	
}