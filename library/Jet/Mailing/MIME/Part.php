<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Mailing_MIME_Part {
	public static string $eol = "\n";
	
	protected bool $is_root = false;
	
	protected string $content_type = '';
	
	protected string $encoding = '';
	
	protected string $boundary = '';
	
	protected string $charset = '';

	protected string $name = '';
	
	protected string $description = '';
	
	protected string $disposition = '';
	
	protected string $filename = '';
	
	protected string $id = '';
	
	protected string $body = '';
	
	/**
	 * @var static[]
	 */
	protected array $parts = [];
	
	public function __construct()
	{
	}
	
	public function getIsRoot(): bool
	{
		return $this->is_root;
	}
	
	public function setIsRoot( bool $is_root ): void
	{
		$this->is_root = $is_root;
	}
	
	
	
	public function getContentType(): string
	{
		return $this->content_type;
	}
	
	public function setContentType( string $content_type ): void
	{
		$this->content_type = $content_type;
	}
	
	public function getEncoding(): string
	{
		return $this->encoding;
	}
	
	public function setEncoding( string $encoding ): void
	{
		$this->encoding = $encoding;
	}
	
	public function getBoundary(): string
	{
		return $this->boundary;
	}
	
	public function setBoundary( string $boundary ): void
	{
		$this->boundary = $boundary;
	}
	
	public function getCharset(): string
	{
		return $this->charset;
	}
	
	public function setCharset( string $charset ): void
	{
		$this->charset = $charset;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function setName( string $name ): void
	{
		$this->name = $name;
	}
	
	public function getDescription(): string
	{
		return $this->description;
	}
	
	public function setDescription( string $description ): void
	{
		$this->description = $description;
	}
	
	public function getDisposition(): string
	{
		return $this->disposition;
	}
	
	public function setDisposition( string $disposition ): void
	{
		$this->disposition = $disposition;
	}
	
	public function getFilename(): string
	{
		return $this->filename;
	}
	
	public function setFilename( string $filename ): void
	{
		$this->filename = $filename;
	}
	
	
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function setId( string $id ): void
	{
		$this->id = $id;
	}
	
	public function getBody(): string
	{
		return $this->body;
	}
	
	public function setBody( string $body ): void
	{
		$this->body = $body;
	}
	
	public function getParts(): array
	{
		return $this->parts;
	}
	
	public function setParts( array $parts ): void
	{
		$this->parts = $parts;
	}
	
	public function addPart( Mailing_MIME_Part $part ) : void
	{
		$this->parts[] = $part;
	}
	
	public function toString() : string
	{
		$res = '';
		
		if(!$this->is_root) {
			if($this->content_type) {
				$res .= 'Content-Type: '.$this->content_type.';';
				if($this->charset) {
					$res .= ' charset="'.$this->charset.'"';
				}
				if($this->boundary) {
					$res .= ' boundary="'.$this->boundary.'"';
				}
				if($this->name) {
					$res .= ' name="'.$this->name.'"';
				}
				$res .= static::$eol;
			}
			
			if($this->description) {
				$res .= 'Content-Description: '.$this->description.static::$eol;
			}
			
			if($this->disposition) {
				$res .= 'Content-Disposition: '.$this->disposition.'; filename="'.$this->filename.'";'.static::$eol;
			}
			
			if($this->id) {
				$res .= 'Content-ID: <'.$this->id.'>'.static::$eol;
			}
			
			if($this->encoding) {
				$res .= 'Content-Transfer-Encoding: '.$this->encoding.static::$eol;
			}
			$res .= static::$eol;
		}
		
		if($this->parts) {
			foreach($this->parts as $part) {
				//$res .= static::$eol.'--'.$part->getBoundary().static::$eol;
				$res .= '--'.$this->boundary.static::$eol;
				$res .= $part->toString();
				$res .= static::$eol;
			}
			
			$res .= '--'.$this->boundary.'--'.static::$eol.static::$eol;
		}
		
		if($this->body) {
			$res .= $this->body.static::$eol;
		}
		
		
		return $res;
	}
	
	public function __toString() : string
	{
		return $this->toString();
	}
}