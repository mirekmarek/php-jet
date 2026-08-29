<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Factory_Form;
use Jet\Factory_InputCatcher;
use Jet\Factory_Validator;
use Jet\Form_Field;
use Jet\SysConf_Jet_Form_DefaultViews;

/**
 *
 */
class Form_Field_AssocArray extends Form_Field
{
	
	protected string $_type = 'assoc-array';
	protected string $_validator_type = 'assoc-array';
	protected string $_input_catcher_type = 'assoc-array';

	protected int $new_rows_count = 5;
	
	protected string $assoc_char = '=>';
	
	
	public function getNewRowsCount(): int
	{
		return $this->new_rows_count;
	}
	
	public function setNewRowsCount( int $new_rows_count ): void
	{
		$this->new_rows_count = $new_rows_count;
	}
	
	public function getAssocChar(): string
	{
		return $this->assoc_char;
	}
	
	public function setAssocChar( string $assoc_char ): void
	{
		$this->assoc_char = $assoc_char;
	}
	
	public static function register() : void
	{
		Factory_InputCatcher::registerNewInputCatcherType( InputCatcher_AssocArray::getType(), InputCatcher_AssocArray::class );
		Factory_Validator::registerNewValidatorType( Validator_AssocArray::getType(), Validator_AssocArray::class );
		
		Factory_Form::registerNewFieldType(
			field_type: 'assoc-array',
			field_class_name: Form_Field_AssocArray::class,
			renderers: [
				'input' => Form_Renderer_Field_Input_AssocArray::class
			]
		);
		
		SysConf_Jet_Form_DefaultViews::registerNewFieldType('assoc-array', [
			'input' => 'field/input/assoc-array'
		]);
	}
}

Form_Field_AssocArray::register();