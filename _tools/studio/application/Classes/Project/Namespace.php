<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

/**
 *
 */
class Project_Namespace extends BaseObject
{
	const APPLICATION_NS_ID = 'application';
	const MODULE_NS_PREFIX = 'module:';

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 *
	 * @var string
	 */
	protected $root_dir_path = '';

	/**
	 * @var bool
	 */
	protected $is_internal = false;

	/**
	 *
	 * @param string $id
	 * @param string $label
	 */
	public function __construct($id, $label)
	{
		$this->id = $id;
		$this->label = $label;
	}

	/**
	 * @return bool
	 */
	public function isInternal()
	{
		return $this->is_internal;
	}

	/**
	 * @param bool $is_internal
	 */
	public function setIsInternal( $is_internal )
	{
		$this->is_internal = $is_internal;
	}



	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
	}



	/**
	 * @return string
	 */
	public function getRootDirPath()
	{
		return $this->root_dir_path;
	}

	/**
	 * @param string $root_dir_path
	 */
	public function setRootDirPath($root_dir_path)
	{
		$this->root_dir_path = $root_dir_path;
	}

}