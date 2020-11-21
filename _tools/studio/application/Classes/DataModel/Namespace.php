<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
class DataModel_Namespace {

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var string
	 */
	protected $root_dir = '';

	/**
	 *
	 * @param string $namespace
	 * @param string $root_dir
	 */
	public function __construct( $namespace, $root_dir )
	{
		$this->namespace = $namespace;
		$this->root_dir = $root_dir;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getRootDir()
	{
		return $this->root_dir;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->namespace;
	}


}