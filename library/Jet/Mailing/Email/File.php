<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Mailing_Email_File extends BaseObject {
	
	protected string $id = '';
	
	protected string $path = '';
	
	protected string $file_name = '';
	
	protected string $file_mime_type = '';
	
	protected string $data = '';
	
	public function __construct( string $id )
	{
		$this->id = $id;
	}
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getPath(): string
	{
		return $this->path;
	}
	
	public function setPath( string $path ): void
	{
		$this->path = $path;
	}
	
	public function getFileName(): string
	{
		if(!$this->file_name) {
			$this->file_name = basename($this->path);
		}
		
		return $this->file_name;
	}
	
	public function setFileName( string $file_name ): void
	{
		$this->file_name = $file_name;
	}
	
	public function getData(): string
	{
		if(!$this->data) {
			return IO_File::read( $this->path );
		}
		
		return $this->data;
	}
	
	public function setData( string $data ): void
	{
		$this->data = $data;
	}
	
	public function getFileMiteType() : string
	{
		if(!$this->file_mime_type) {
			$this->file_mime_type = Debug_ErrorHandler::doItSilent(function() {
				return IO_File::getMimeType( $this->path );
			});
			if(!$this->file_mime_type) {
				$this->file_mime_type = '';
			}
		}
		
		return $this->file_mime_type;
	}
	
	public function setFileMimeType( string $file_mime_type ): void
	{
		$this->file_mime_type = $file_mime_type;
	}
	
	
	
	public function __toString() : string
	{
		return $this->getFileName();
	}
	
}