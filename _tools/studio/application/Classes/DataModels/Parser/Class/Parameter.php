<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

class DataModels_Parser_Class_Parameter extends DataModels_Parser_Parameter {

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
		parent::__construct( $parser, $parse_class, $name, $raw_value, $declared_in_class, $inherited );

		switch( $this->name ) {
			case 'id_controller_class_name':
			case 'iterator_class_name':
			case 'data_model_parent_model_class_name':
			case 'parent_model_class_name':
			case 'N_model_class_name':
				$this->value = $parse_class->parser->getFullClassName( $this->value );
			break;

		}

	}
}