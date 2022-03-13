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
class SysConf_Jet_UI_DefaultViews
{
	protected static array $views = [
		'tabs-js' => [
			'start' => 'tabs-js/start',
			'end' => 'tabs-js/end',
			'tab' => 'tabs-js/tab',
			'content/start' => 'tabs-js/tab/content-start',
			'content/end' => 'tabs-js/tab/content-end',
		],

		'tabs' => [
			'main' => 'tabs',
		],
		'tabs/tab' => [
			'main' => 'tabs/tab',
		],
		'messages/message' => [
			'main' => 'messages/message',
		],
		'button/create' => [
			'main' => 'button/create',
		],
		'button/delete' => [
			'main' => 'button/delete',
		],
		'button/save' => [
			'main' => 'button/save',
		],
		'button/edit' => [
			'main' => 'button/edit',
		],
		'button/go-back' => [
			'main' => 'button/go-back',
		],
		'button' => [
			'main' => 'button',
		],
		'flag' => [
			'main' => 'flag',
		],
		'icon' => [
			'main' => 'icon',
		],
		'locale' => [
			'main' => 'locale',
		],
		'badge' => [
			'main' => 'badge',
		],
		'locale-label' => [
			'main' => 'locale-label',
		],
		'search-field' => [
			'main' => 'search-field',
		],
		'dialog' => [
			'start' => 'dialog/start',
			'footer' => 'dialog/footer',
			'end' => 'dialog/end',
		],
		'data-grid' => [
			'main' => 'data-grid',
			'header' => 'data-grid/header',
			'header/column' => 'data-grid/header/column',
			'body' => 'data-grid/body',
			'footer' => 'data-grid/footer',
		],
		'tree' => [
			'main' => 'tree',
		]
	];



	public static function get( string $element, string $view='main' ) : string
	{
		return static::$views[$element][$view];
	}

	public static function set( string $element, string $view, string $value ) : void
	{
		static::$views[$element][$view] = $value;
	}

}