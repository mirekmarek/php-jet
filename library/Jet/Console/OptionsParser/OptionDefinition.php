<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Console
 */

namespace Jet;

class Console_OptionsParser_OptionDefinition extends Object {
	const TYPE_STRING = "string";
	const TYPE_INT = "int";
	const TYPE_FLOAT = "float";
	const TYPE_BOOL = "bool";

	/**
	 * @var string
	 */
	protected $name = "";

	/**
	 * @var string
	 */
	protected $option_short = "";

	/**
	 * @var string
	 */
	protected $option_long = "";

	/**
	 * @var string
	 */
	protected $type = self::TYPE_STRING;

	/**
	 * @var bool
	 */
	protected $is_required = true;

	/**
	 * @var null|mixed
	 */
	protected $default_value;

	/**
	 * @var string
	 */
	protected $help = "";


	/**
	 * @var null|mixed
	 */
	protected $value;

	/**
	 * @var null|callable
	 */
	protected $validation_callback;

	/**
	 * @param string $name
	 * @param string $option_long
	 * @param string $option_short
	 * @param string $type (optional)
	 */
	public function __construct( $name, $option_long, $option_short, $type=self::TYPE_STRING ) {
		$this->name = (string)$name;
		$this->option_short = (string)$option_short;
		$this->option_long = (string)$option_long;
		$this->type = (string)$type;

		if(strlen($this->option_short)>1) {
			//TODO: throw new
		}

		if(!in_array($this->type, array())) {
			//TODO: throw new
		}
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getOptionLong() {
		return $this->option_long;
	}

	/**
	 * @return string
	 */
	public function getOptionShort() {
		return $this->option_short;
	}

	/**
	 * @param mixed $default_value
	 */
	public function setDefaultValue($default_value) {
		$default_value = $this->checkValueType($default_value);
		$this->default_value = $default_value;
	}

	/**
	 * @return mixed|null
	 */
	public function getDefaultValue() {
		return $this->default_value;
	}

	/**
	 * @param string $help
	 */
	public function setHelp($help) {
		$this->help = (string)$help;
	}

	/**
	 * @return string
	 */
	public function getHelp() {
		return $this->help;
	}

	/**
	 * @param bool $is_required
	 */
	public function setIsRequired($is_required) {
		$this->is_required = (bool)$is_required;
	}

	/**
	 * @return bool
	 */
	public function getIsRequired() {
		return $this->is_required;
	}

	/**
	 * @param callable $validation_callback
	 */
	public function setValidationCallback( callable $validation_callback) {
		$this->validation_callback = $validation_callback;
	}

	/**
	 * @return callable|null
	 */
	public function getValidationCallback() {
		return $this->validation_callback;
	}

	/**
	 * @return string
	 */
	public function getShortOptionDefinition() {
		if($this->type==self::TYPE_BOOL) {
			return $this->option_short;
		}

		if($this->is_required) {
			return $this->option_short.":";
		} else {
			return $this->option_short."::";
		}
	}

	/**
	 * @return string
	 */
	public function getLongOptionDefinition() {
		if($this->type==self::TYPE_BOOL) {
			return $this->option_long;
		}

		if($this->is_required) {
			return $this->option_long.":";
		} else {
			return $this->option_long."::";
		}
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value) {
		$value = $this->checkValueType($value);
		$this->value = $value;
	}

	/**
	 * @return mixed|null
	 */
	public function getValue() {
		return $this->value===null ? $this->default_value : $this->value;
	}

	/**
	 * @param array $options
	 */
	public function extractValue( array $options ) {
		if($this->type==self::TYPE_BOOL) {
			$this->setValue(
					array_key_exists($this->option_short, $options) ||
					array_key_exists($this->option_long, $options)
			);
			return;
		}

		if(array_key_exists($this->option_short, $options)) {
			$this->setValue($options[$this->option_short]);
		}

		if(array_key_exists($this->option_long, $options)) {
			$this->setValue($options[$this->option_long]);
		}
	}


	/**
	 * @param mixed $value
	 * @return bool|float|int|string
	 */
	protected function checkValueType( $value ) {
		switch( $this->type ) {
			case self::TYPE_STRING:
				return (string)$value;
			case self::TYPE_INT:
				return (int)$value;
			case self::TYPE_FLOAT:
				return (float)$value;
			case self::TYPE_BOOL:
				return (bool)$value;
			break;
			default:
				//TODO: throw new ...
			break;
		}
	}

}