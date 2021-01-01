<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Trait_MagicMethods
{

	/**
	 *
	 */
	public function __wakeup() : void
	{
		/**
		 * @var DataModel $this
		 */
		$this->setIsSaved();
	}

	/**
	 * @return array
	 */
	public function __debugInfo() : array
	{
		/** @noinspection PhpUndefinedClassInspection */
		/** @noinspection PhpUndefinedMethodInspection */
		$r = parent::__debugInfo();

		$r['_data_model_saved'] = $this->getIsSaved();

		if( $this->getLoadFilter() ) {
			$r['_load_filter'] = $this->getLoadFilter();
		}

		return $r;
	}

	/**
	 *
	 */
	public function __clone() : void
	{
		/**
		 * @var DataModel $this
		 */
		/** @noinspection PhpUndefinedClassInspection */
		/** @noinspection PhpUndefinedMethodInspection */
		parent::__clone();

		$this->setIsNew();
	}

}