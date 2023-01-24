<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_Jet_Form_DefaultViews
{
	protected static array $views = [
		'Form' => [
			'start' => 'start',
			'end' => 'end',
			'message' => 'message',
		],
		
		Form_Field::TYPE_CHECKBOX => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label/checkbox',
			'input' => 'field/input/checkbox',
		],

		Form_Field::TYPE_COLOR => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/color',
		],

		Form_Field::TYPE_DATE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/date',
		],

		Form_Field::TYPE_DATE_TIME => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/date-time',
		],

		Form_Field::TYPE_EMAIL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/email',
		],

		Form_Field::TYPE_FILE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/file',
		],

		Form_Field::TYPE_FILE_IMAGE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/file-image',
		],

		Form_Field::TYPE_FLOAT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/float',
		],

		Form_Field::TYPE_HIDDEN => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/hidden',
		],
		
		Form_Field::TYPE_CSRF_PROTECTION => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/csrf_protection',
		],
		

		Form_Field::TYPE_INPUT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/input',
		],

		Form_Field::TYPE_INT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/int',
		],

		Form_Field::TYPE_MONTH => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/month',
		],

		Form_Field::TYPE_MULTI_SELECT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/multi-select',
		],

		Form_Field::TYPE_PASSWORD => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/password',
		],

		Form_Field::TYPE_RADIO_BUTTON => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/radio-button',
		],

		Form_Field::TYPE_RANGE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/range',
		],


		Form_Field::TYPE_SEARCH => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/search',
		],

		Form_Field::TYPE_SELECT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/select',
		],

		Form_Field::TYPE_TEL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/tel',
		],

		Form_Field::TYPE_TEXTAREA => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/textarea',
		],

		Form_Field::TYPE_TIME => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/time',
		],

		Form_Field::TYPE_URL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/url',
		],

		Form_Field::TYPE_WEEK => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/week',
		],

		Form_Field::TYPE_WYSIWYG => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/wysiwyg',
		]
	];


	public static function get( string $element, string $view ) : string
	{
		if(!isset(static::$views[$element][$view])) {
			throw new Form_Exception('View '.$view.' for element '.$element.' is not defined');
		}
		
		return static::$views[$element][$view];
	}

	public static function set( string $element, string $view, string $value ) : void
	{
		static::$views[$element][$view] = $value;
	}
	
	public static function registerNewFieldType( string $field_type, array $views = [] ) : void
	{
		$default_views = [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'container_start' => 'field/container/start',
			'container_end' => 'field/container/end',
			'error' => 'field/error',
			'help' => 'field/help',
			'label' => 'field/label',
			'input' => 'field/input/input',
		];
		
		foreach($default_views as $element=>$view) {
			if(!isset($views[$element])) {
				$views[$element] = $view;
			}
		}
		
		static::$views[$field_type] = $views;
	}

}