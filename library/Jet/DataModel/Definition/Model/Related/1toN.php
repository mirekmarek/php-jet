<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Definition_Model_Related_1toN extends DataModel_Definition_Model_Related
{

	/**
	 * @var string
	 */
	protected $iterator_class_name = __NAMESPACE__.'\DataModel_Related_1toN_Iterator';

	/**
	 * @return string
	 */
	public function getIteratorClassName()
	{
		return $this->iterator_class_name;
	}

	/**
	 * @param string $iterator_class_name
	 */
	public function setIteratorClassName( $iterator_class_name )
	{
		$this->iterator_class_name = $iterator_class_name;
	}


	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents()
	{
		parent::_initParents();


		if( ( $iterator_class_name = Reflection::get( $this->class_name, 'iterator_class_name', null ) ) ) {
			$this->iterator_class_name = $iterator_class_name;
		}
	}
}