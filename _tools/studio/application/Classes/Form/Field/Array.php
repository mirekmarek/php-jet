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
class Form_Field_Array extends Form_Field
{
	
	protected string $_type = 'array';
	protected string $_validator_type = 'array';
	protected string $_input_catcher_type = 'array';

	protected int $new_rows_count = 5;
	
	protected string $prepend_text = '';
	
	protected string $append_text = '';
	
	public function getNewRowsCount(): int
	{
		return $this->new_rows_count;
	}

	public function setNewRowsCount( int $new_rows_count ): void
	{
		$this->new_rows_count = $new_rows_count;
	}
	
	public function getPrependText(): string
	{
		return $this->prepend_text;
	}
	
	public function setPrependText( string $prepend_text ): void
	{
		$this->prepend_text = $prepend_text;
	}

	public function getAppendText(): string
	{
		return $this->append_text;
	}

	public function setAppendText( string $append_text ): void
	{
		$this->append_text = $append_text;
	}
	
	
	public static function register() : void
	{
		Factory_InputCatcher::registerNewInputCatcherType( InputCatcher_Array::getType(), InputCatcher_Array::class );
		Factory_Validator::registerNewValidatorType( Validator_Array::getType(), Validator_Array::class );
		
		Factory_Form::registerNewFieldType(
			field_type: 'array',
			field_class_name: Form_Field_Array::class,
			renderers: [
				'input' => Form_Renderer_Field_Input_Array::class
			]
		);
		
		SysConf_Jet_Form_DefaultViews::registerNewFieldType('array', [
			'input' => 'field/input/array'
		]);
	}
}

Form_Field_Array::register();