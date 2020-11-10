<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


abstract class DataModels_Parser_Parameter {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $declared_in_class = '';

	/**
	 * @var bool
	 */
	protected $inherited = false;

	/**
	 *
	 * @param DataModels_Parser $parser
	 * @param ClassParser_Class $parse_class
	 * @param string $name
	 * @param string $raw_value
	 * @param string $declared_in_class
	 * @param bool $inherited
	 */
	public function __construct( DataModels_Parser $parser, ClassParser_Class $parse_class, $name, $raw_value, $declared_in_class, $inherited )
	{
		$this->name = trim($name);
		$this->value = eval('return '.$this->updateValue( $parser, $parse_class, $raw_value ).';');
		$this->declared_in_class = $declared_in_class;
		$this->inherited = $inherited;
	}

	/**
	 * @param DataModels_Parser $parser
	 * @param ClassParser_Class $parse_class
	 * @param string $raw_value
	 * @return string
	 */
	protected function updateValue( DataModels_Parser $parser, ClassParser_Class $parse_class, $raw_value )
	{
		$value = trim($raw_value);

		$parser_regexp = '/([a-zA-Z0-9_]*)::([a-zA-Z0-9_]*)/';

		preg_match_all( $parser_regexp, $raw_value, $matches, PREG_SET_ORDER );;
		if($matches) {
			foreach( $matches as $m ) {
				$orig_str = $m[0];
				$class_name = $m[1];
				$constant = $m[2];

				$real_value = $parser->getConstantValue($parse_class->parser->getFullClassName( $class_name ), $constant);

				$value = str_replace( $orig_str, var_export($real_value, true), $value );
			}
		}

		return $value;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( $value )
	{
		$this->value = $value;
	}



	/**
	 * @return string
	 */
	public function getDeclaredInClass()
	{
		return $this->declared_in_class;
	}

	/**
	 * @return bool
	 */
	public function isInherited()
	{
		return $this->inherited;
	}

}
