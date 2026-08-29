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
class Form_Field_MetaTags extends Form_Field
{
	
	protected string $_type = 'meta-tags';
	protected string $_validator_type = 'meta-tags';
	protected string $_input_catcher_type = 'meta-tags';

	protected int $new_rows_count = 5;
	
	public function getNewRowsCount(): int
	{
		return $this->new_rows_count;
	}
	
	public function setNewRowsCount( int $new_rows_count ): void
	{
		$this->new_rows_count = $new_rows_count;
	}
	
	public static function register() : void
	{
		Factory_InputCatcher::registerNewInputCatcherType( InputCatcher_MetaTags::getType(), InputCatcher_MetaTags::class );
		Factory_Validator::registerNewValidatorType( Validator_MetaTags::getType(), Validator_MetaTags::class );
		
		Factory_Form::registerNewFieldType(
			field_type: 'meta-tags',
			field_class_name: Form_Field_MetaTags::class,
			renderers: [
				'input' => Form_Renderer_Field_Input_MetaTags::class
			]
		);
		
		SysConf_Jet_Form_DefaultViews::registerNewFieldType('meta-tags', [
			'input' => 'field/input/meta-tags'
		]);
	}
	
}

Form_Field_MetaTags::register();