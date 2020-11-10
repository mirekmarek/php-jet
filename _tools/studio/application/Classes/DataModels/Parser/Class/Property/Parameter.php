<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

class DataModels_Parser_Class_Property_Parameter extends DataModels_Parser_Parameter {

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
			case 'data_model_class':
				$this->value = $parse_class->parser->getFullClassName( $this->value );
				break;
			case 'form_field_get_select_options_callback':
				if(is_array($this->value)) {
					if($this->value[0]!='this') {
						$this->value[0] = $parse_class->parser->getFullClassName( $this->value[0] );
					}
				}
				break;

		}
	}
}