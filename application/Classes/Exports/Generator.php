<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\SysConf_Path;


class Exports_Generator
{
	protected bool $buffer_mode = false;
	
	protected string $input_charset = 'UTF-8';

	protected string $output_charset = 'UTF-8';

	protected ?string $target_path = null;

	protected ?string $tmp_file_path = null;
	
	protected string $export_code;
	
	protected ?MVC_Base_LocalizedData_Interface $base;

	public function __construct( string $export_code, ?MVC_Base_LocalizedData_Interface $base=null )
	{
		$this->export_code = $export_code;
		$this->base = $base;
		
	}
	
	public function enableBufferMode(): void
	{
		$this->buffer_mode = true;
		if( $this->base ) {
			$this->tmp_file_path = SysConf_Path::getTmp().'export_'.$this->export_code.'_'.$this->base->getBase()->getId().'_'.$this->base->getLocale().'_'.date('YmdHis');
		} else {
			$this->tmp_file_path = SysConf_Path::getTmp().'export_'.$this->export_code.'_'.date('YmdHis');
			
		}
	}
	
	public function setOutputFile( string $target_file_name ) : void
	{
		$this->target_path = Exports::getRootPath().$target_file_name;
		
		if( $this->base ) {
			$this->tmp_file_path = SysConf_Path::getTmp().'export_'.$this->export_code.'_'.$this->base->getBase()->getId().'_'.$this->base->getLocale().'_'.date('YmdHis');
		} else {
			$this->tmp_file_path = SysConf_Path::getTmp().'export_'.$this->export_code.'_'.date('YmdHis');
		}
	}

	public function getInputCharset(): string
	{
		return $this->input_charset;
	}

	public function setInputCharset( string $input_charset ): void
	{
		$this->input_charset = $input_charset;
	}

	public function getOutputCharset(): string
	{
		return $this->output_charset;
	}

	public function setOutputCharset( string $output_charset ): void
	{
		$this->output_charset = $output_charset;
	}

	public function getTargetPath(): string
	{
		return $this->target_path;
	}

	public function setTargetPath( string $target_path ): void
	{
		$this->target_path = $target_path;
	}

	public function getTmpFilePath(): string
	{
		return $this->tmp_file_path;
	}

	public function setTmpFilePath( string $tmp_file_path ): void
	{
		$this->tmp_file_path = $tmp_file_path;
	}


	public function start() : void
	{
		if($this->tmp_file_path) {
			IO_File::write($this->tmp_file_path, '');
		}
	}

	public function done() : void
	{
		if($this->tmp_file_path) {
			if($this->buffer_mode) {
				IO_File::send($this->tmp_file_path);
			} else {
				$target_dir = dirname($this->target_path);
				if(!IO_Dir::exists($target_dir)) {
					IO_Dir::create($target_dir);
				}
				
				IO_File::copy( $this->tmp_file_path, $this->target_path );
			}
			
			IO_File::delete( $this->tmp_file_path );
		} else {
			Application::end();
		}
	}

	protected function _line( string $line ) : void
	{
		if($this->input_charset!=$this->output_charset) {
			$line = iconv($this->input_charset, $this->output_charset, $line);
		}

		$line .= PHP_EOL;

		if($this->tmp_file_path) {
			IO_File::append( $this->tmp_file_path, $line );
		} else {
			echo $line;
		}
	}



}