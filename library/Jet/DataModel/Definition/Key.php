<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Key extends Object {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @see DataModel::KEY_TYPE_*
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * @var array
	 */
	protected $property_names = array();


	/**
	 * @param string $name
	 * @param string $type
	 * @param array $property_names
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct($name, $type=DataModel::KEY_TYPE_INDEX, array $property_names=array() ) {
		if(!$property_names) {
			$property_names[] = $name;
		}

		if(!in_array($type, array(
			DataModel::KEY_TYPE_INDEX,
			DataModel::KEY_TYPE_PRIMARY,
			DataModel::KEY_TYPE_UNIQUE
		))) {
			throw new DataModel_Exception(
				'Unknown key type',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->name = $name;
		$this->property_names = $property_names;
		$this->type = $type;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getPropertyNames() {
		return $this->property_names;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function __set_state( $data ) {
		return new static( $data['name'], $data['type'], $data['property_names'] );

	}

}