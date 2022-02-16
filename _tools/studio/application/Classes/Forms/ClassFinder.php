<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form_Definition_Interface;
use Jet\IO_File;
use Jet\IO_Dir;
use ReflectionClass;

/**
 */
class Forms_ClassFinder
{

	/**
	 * @var Forms_Class[]
	 */
	protected array $classes = [];

	/**
	 * @var bool
	 */
	protected bool $_new_founded = false;

	/**
	 * @var array
	 */
	protected array $dirs = [];

	/**
	 * @var string[]
	 */
	protected array $interfaces = [
		Form_Definition_Interface::class
	];

	/**
	 *
	 * @param array $dirs
	 */
	public function __construct( array $dirs )
	{
		$this->dirs = $dirs;
	}

	/**
	 *
	 */
	public function find(): void
	{
		do {
			$this->_new_founded = false;
			foreach( $this->dirs as $dir ) {
				$this->readDir( $dir );
			}
		} while( $this->_new_founded );

		ksort( $this->classes );
	}

	/**
	 * @param string $dir
	 */
	protected function readDir( string $dir ): void
	{
		
		$dirs = IO_Dir::getList( $dir, '*', true, false );
		$files = IO_Dir::getList( $dir, '*.php', false, true );

		foreach( $files as $path => $name ) {
			$file_data = IO_File::read( $path );

			if(
				!str_contains( $file_data, 'Form_Definition' ) &&
				!str_contains( $file_data, 'DataModel' )
			) {

				continue;
			}

			$parser = new ClassParser( $file_data );

			foreach( $parser->classes as $class ) {
				$full_name = $parser->namespace->namespace . '\\' . $class->name;

				if( isset( $this->classes[$full_name] ) ) {
					continue;
				}

				$reflection = new ReflectionClass( $full_name );
				
				$interfaces = $reflection->getInterfaceNames();

				if( !array_intersect( $interfaces, $this->interfaces ) ) {
					continue;
				}

				$cl = new Forms_Class(
					$path,
					$parser->namespace->namespace,
					$class->name,
					$reflection
				);

				$this->classes[$cl->getFullClassName()] = $cl;

				$this->_new_founded = true;

			}

		}

		foreach( $dirs as $path => $name ) {
			$this->readDir( $path );
		}
	}

	/**
	 * @return Forms_Class[]
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}


}