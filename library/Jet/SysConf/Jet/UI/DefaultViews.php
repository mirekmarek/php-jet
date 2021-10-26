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
class SysConf_Jet_UI_DefaultViews
{
	protected static array $views = [
		'tabs-js' => [
			'main' => 'tabs-js',
			'content_start' => 'tabs-js/content-start',
			'content_end' => 'tabs-js/content-end',
		],

		'tabs-js/tab' => [
			'main' => 'tabs-js/tab',
			'content_start' => 'tabs-js/tab/content-start',
			'content_end' => 'tabs-js/tab/content-end',
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
			'body' => 'data-grid/body',
			'paginator' => 'data-grid/paginator',
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