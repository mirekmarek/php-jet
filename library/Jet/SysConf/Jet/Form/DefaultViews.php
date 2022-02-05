<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
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
		
		Form::TYPE_CHECKBOX => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label/checkbox',
			'input' => 'field/input/checkbox',
		],

		Form::TYPE_COLOR => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/color',
		],

		Form::TYPE_DATE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/date',
		],

		Form::TYPE_DATE_TIME => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/date-time',
		],

		Form::TYPE_EMAIL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/email',
		],

		Form::TYPE_FILE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/file',
		],

		Form::TYPE_FILE_IMAGE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/file-image',
		],

		Form::TYPE_FLOAT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/float',
		],

		Form::TYPE_HIDDEN => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/hidden',
		],

		Form::TYPE_INPUT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/input',
		],

		Form::TYPE_INT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/int',
		],

		Form::TYPE_MONTH => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/month',
		],

		Form::TYPE_MULTI_SELECT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/multi-select',
		],

		Form::TYPE_PASSWORD => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/password',
		],

		Form::TYPE_RADIO_BUTTON => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/radio-button',
		],

		Form::TYPE_RANGE => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/range',
		],


		Form::TYPE_SEARCH => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/search',
		],

		Form::TYPE_SELECT => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/select',
		],

		Form::TYPE_TEL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/tel',
		],

		Form::TYPE_TEXTAREA => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/textarea',
		],

		Form::TYPE_TIME => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/time',
		],

		Form::TYPE_URL => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/url',
		],

		Form::TYPE_WEEK => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/week',
		],

		Form::TYPE_WYSIWYG => [
			'field' => 'field',
			'row_start' => 'field/row/start',
			'row_end' => 'field/row/end',
			'input_container_start' => 'field/container/start',
			'input_container_end' => 'field/container/end',
			'error' => 'field/error',
			'label' => 'field/label',
			'input' => 'field/input/wysiwyg',
		]
	];


	public static function get( string $element, string $view ) : string
	{
		return static::$views[$element][$view];
	}

	public static function set( string $element, string $view, string $value ) : void
	{
		static::$views[$element][$view] = $value;
	}

}