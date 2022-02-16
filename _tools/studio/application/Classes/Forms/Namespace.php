<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
class Forms_Namespace
{

	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * @var string
	 */
	protected string $root_dir = '';

	/**
	 *
	 * @param string $namespace
	 * @param string $root_dir
	 */
	public function __construct( string $namespace, string $root_dir )
	{
		$this->namespace = $namespace;
		$this->root_dir = $root_dir;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getRootDir(): string
	{
		return $this->root_dir;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->namespace;
	}


}