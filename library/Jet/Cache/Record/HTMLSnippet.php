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
class Cache_Record_HTMLSnippet
{
	protected string $key = '';
	protected string $html = '';
	protected int $timestamp = 0;
	
	public function __construct( string $key, string $html, int $timestamp )
	{
		$this->key = $key;
		$this->html = $html;
		$this->timestamp = $timestamp;
	}
	
	public function getKey(): string
	{
		return $this->key;
	}
	
	public function getHtml(): string
	{
		return $this->html;
	}
	
	public function getTimestamp(): int
	{
		return $this->timestamp;
	}
	
	public function toString(): string
	{
		return $this->html;
	}
	
	public function __toString(): string
	{
		return $this->html;
	}
}