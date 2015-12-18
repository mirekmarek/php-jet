<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_Array extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_ARRAY;
	/**
	 * @var array
	 */
	protected $default_value = [];
	/**
	 * @var string
	 */
	protected $form_field_type = 'MultiSelect';

	/**
	 * @var string
	 */
	protected $item_type = null;

	/**
	 * @param string $item_type
	 */
	public function setItemType($item_type) {
		$this->item_type = $item_type;
	}

	/**
	 * @return string
	 */
	public function getItemType() {
		return $this->item_type;
	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value ) {
		if(!is_array($value)) {
			$value = [];
		}
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType().'';

		$res .= ', required: '.($this->is_required ? 'yes':'no');

		if($this->default_value) {
			$res .= ', default value: '.implode(',', $this->default_value);
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

}