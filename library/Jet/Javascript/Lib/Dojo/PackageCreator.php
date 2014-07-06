<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

class Javascript_Lib_Dojo_PackageCreator extends Object {

	/**
	 * @var string
	 */
	protected $base_path = '';
	/**
	 * @var string
	 */
	protected $base_URL = '';

	/**
	 * @var array
	 */
	protected $component_scripts = [];

	/**
	 * @var array
	 */
	protected $components = ['dojo'];

	/**
	 * @var array
	 */
	protected $dojo_core_components = [
		'require',
		'module',
		'exports',
		'dojo',
		'dojo/Deferred',
		'dojo/when',
		'dojo/_base/Deferred',
		'dojo/_base/json',
		'dojo/has',
		'dojo/_base/config',
		'dojo/_base/kernel',
		'dojo/sniff',
		'dojo/_base/lang',
		'dojo/_base/sniff',
		'dojo/io-query',
		'dojo/_base/window',
		'dojo/dom',
		'dojo/json',
		'dojo/dom-form',
		'dojo/_base/array',
		'dojo/on',
		'dojo/aspect',
		'dojo/errors/create',
		'dojo/errors/RequestError',
		'dojo/errors/CancelError',
		'dojo/promise/Promise',
		'dojo/request/util',
		'dojo/errors/RequestTimeoutError',
		'dojo/request/watch',
		'dojo/selector/_loader',
		'dojo/request/handlers',
		'dojo/request/xhr',
		'dojo/_base/xhr',
		'dojo/_base/declare',
		'dojo/Evented',
		'dojo/topic',
		'dojo/dom-style',
		'dojo/dom-geometry',
		'dojo/_base/event',
		'dojo/mouse',
		'dojo/keys',
		'dojo/_base/connect',
		'dojo/dom-class',
		'dojo/_base/Color',
		'dojo/_base/fx',
		'dojo/domReady',
		'dojo/ready',
		'dojo/dom-construct',
		'dojo/dom-prop',
		'dojo/dom-attr',
		'dojo/query',
		'dojo/NodeList-dom',
		'dojo/_base/html',
		'dojo/_base/NodeList',
		'dojo/_base/unload'
	];

	/**
	 * @var array
	 */
	protected $requiring = [];

	/**
	 * @var array
	 */
	protected $templates = [];

	/**
	 * @var array
	 */
	protected $i18n = [
			'dojo/cldr/nls/number',
			'dojo/cldr/nls/gregorian',
			'dojo/cldr/nls/currency',

			'dojo/cldr/nls/%LANG%/number',
			'dojo/cldr/nls/%LANG%/gregorian',
			'dojo/cldr/nls/%LANG%/currency'
		];


	/**
	 * @param string $base_path
	 * @param string $base_URL
	 * @param array $components
	 * @param Locale $locale
	 */
	public function __construct( $base_path, $base_URL, Locale $locale, $components ) {

		$this->base_path = $base_path;
		$this->base_URL = $base_URL;

		$language = strtolower($locale->getLanguage());

		foreach( $this->i18n as $i=>$i18n ) {
			$this->i18n[$i] = str_replace('%LANG%', $language, $i18n);
		}


		$this->requireComponent('dojo/dojo');

		foreach( $components as $component ) {
			$this->requireComponent( $component );
		}
	}

	/**
	 * @param Locale $locale
	 * @param array $components
	 * @return string
	 */
	public static function getKey( Locale $locale, $components ) {
		$key = '';
		foreach( $components as $component ) {
			$key .= $component;
		}
		$key = md5($key).'-'.$locale;

		return $key;
	}

	/**
	 * @param string $component
	 */
	public function requireComponent( $component ) {


		$component = str_replace('.', '/', $component);

		if(
			in_array($component, $this->components) ||
			in_array($component, $this->requiring) ||
			in_array($component, $this->dojo_core_components)
		) {
			return;
		}

		$base_path = null;
		list($namespace) = explode('/', $component);
		if($namespace!='dojo' && $namespace!='dijit' && $namespace!='dojox') {

			$script = $this->getScript($component, JET_PUBLIC_SCRIPTS_PATH);

			$this->component_scripts[$component] = $script;

			$this->requiring[] = $component;
			$this->components[] = $component;

			return;

		}


		if(
			$namespace=='dijit' &&
			$component!='dijit/dijit' &&
			!in_array('dijit/dijit', $this->components)
		) {
			$this->requireComponent('dijit/dijit');
		}



		$this->requiring[] = $component;

		$script = $this->getScript($component, $base_path);


		list($components, $requires, $templates) = $this->parseDependencies( $component, $script );

		$this->handleTemplates($templates, $script);

		$this->component_scripts[$component] = $script;

		foreach( $requires as $require ) {
			$this->requireComponent($require);
		}

		if(!in_array($component, $components)) {
			$components[] = $component;
		}

		foreach($components as $p_component) {
			if(in_array($p_component, $this->components)) {
				continue;
			}

			$this->components[] = $p_component;
		}

	}

	/**
	 * @param array $templates
	 * @param string &$script
	 */
	protected function handleTemplates( $templates, &$script ) {
		foreach( $templates as $original_str=>$template_path ) {
			$replacement = str_replace('dojo/text!', '__get_template!', $original_str);

			$template = $this->getTemplate( $template_path );

			$this->templates[$template_path] = $template;

			$script = str_replace( $original_str, $replacement, $script );
		}

	}

	/**
	 * @param string $component
	 * @param string $script
	 *
	 * @return array
	 */
	protected function parseDependencies( $component, $script ) {

		$requires = [];
		$components = [];

		$component_base_path = dirname($component).'/';

		$matches = [];

		$templates = [];



		if(preg_match_all('/define\(([^,]*),"([^"]*)"\.split\(" "\)/i', $script, $matches, PREG_SET_ORDER)) {
			foreach( $matches as $match ) {
				$c = $match[1];
				$r = preg_replace('/[ ]{2,}/', '',$match[2] );
				$r = str_replace(' ', '","',$r);

				$replacement = 'define('.$c.',["'.$r.'"]';

				$script = str_replace($match[0], $replacement, $script);
			}
		}

		preg_match_all('/define\(([^,]*),\[([^\]]*)]/i', $script, $matches, PREG_SET_ORDER);

		foreach($matches as $match) {
			$_component = str_replace(' ', '', str_replace("'", '', str_replace('"','', $match[1])));
			$__require = explode(',', str_replace(' ', '', str_replace("'", '', str_replace('"','', $match[2]))));

			$_require = [];

			foreach($__require as $r) {
				if(strpos($r, '!')!==false) {
					if(strpos($r,'?')===false) {
						$original_str = $r;

						$param = 'dojo/text!';

						if( substr($r,0, strlen($param) )==$param ) {
							$template_path = $this->normalizePath( $component_base_path, substr($r, strlen($param)));


							$templates[$original_str] = $template_path;

							continue;
						}

						$param = 'dojo/i18n!';

						if( substr($r,0, strlen($param) )==$param ) {
							$i18n_path = substr($r, strlen($param));

							if($i18n_path[0]=='.') {
								$i18n_path = $this->normalizePath( $component_base_path, $i18n_path);
							}

							if(!in_array($i18n_path, $this->i18n)) {
								$this->i18n[] = $i18n_path;
							}


							continue;
						}


					} else {
						//$_require = array_merge($_require, $this->parseRegularExpr($r));
					}

					continue;
				}

				$_require[] = $r;
			}

			foreach( $_require as $_r ) {
				if(!$_r) {
					continue;
				}

				if($_r[0]=='.') {
					$require = $this->normalizePath( $component_base_path, $_r );
				} else {
					$require = $_r;
				}

				if(!in_array($require, $requires)) {
					$requires[] = $require;
				}
			}

			$components[] = $_component;
		}

		return [ $components, $requires, $templates ];
	}

	/**
	 * @param string $component
	 * @param string|null $base_path
	 *
	 * @return string
	 */
	protected function getScript( $component, $base_path = null ) {
		$base_path = $base_path ? $base_path : $this->base_path;

		$path = $base_path . $component . '.js';


		$script = IO_File::read( $path );

		return $script;

	}

	/**
	 * @param string $template_path
	 * @return string
	 */
	protected function getTemplate( $template_path ) {
		$path = $this->base_path . $template_path;


		$template = IO_File::read( $path );

		return $template;
	}


	/**
	 * @param string $base_path
	 * @param string $relative_path
	 *
	 * @return string
	 */
	protected function normalizePath( $base_path, $relative_path ) {
		$path = $base_path.$relative_path;


		$path = preg_replace('#/\.(?=/)|^\./|\./$#', '', $path);

		$regex = '#\/*[^/\.]+/\.\.#Uu';

		while(preg_match($regex, $path)) {
			$path = preg_replace($regex, '', $path);
		}


		return $path;
	}


	/**
	 * @return string
	 */
	public function createPackage() {
		$JS = '';

		/*
		foreach($this->components as $component) {
			if(!isset($this->component_scripts[$component])) {
				continue;
			}

			$JS .= '// '.$component.JET_EOL;
		}
		$JS .= JET_EOL;
		*/

		$JS .= ' var __templates={};'.JET_EOL;

		foreach( $this->templates as $ID=>$template ) {
			$JS .= '__templates['.json_encode($ID).'] = '.json_encode($template).';'.JET_EOL;
		}

		$dojo_main_key = 'dojo/dojo';

		$this->component_scripts[$dojo_main_key] .= "dojo.registerModulePath('dojo','".$this->base_URL."dojo');".JET_EOL;
		$this->component_scripts[$dojo_main_key] .= "dojo.registerModulePath('dijit','".$this->base_URL."dijit');".JET_EOL;
		$this->component_scripts[$dojo_main_key] .= "dojo.registerModulePath('dojox','".$this->base_URL."dojox');".JET_EOL;
		$this->component_scripts[$dojo_main_key] .= 'define("__get_template", [], function(){ return { dynamic: true, load: function(id, require, load){ load(__templates[id]); }};});'.JET_EOL;

		foreach($this->i18n as $i18n) {
			$script = $this->getScript( $i18n );

			$this->component_scripts[$dojo_main_key] .= JET_EOL.'//i18n '.$i18n.JET_EOL;
			$this->component_scripts[$dojo_main_key] .= $script;
			$this->component_scripts[$dojo_main_key] .= '//-----------------------------'.JET_EOL;

		}

		foreach($this->components as $component) {
			if(!isset($this->component_scripts[$component])) {
				continue;
			}


			$JS .= JET_EOL.'// '.$component.JET_EOL;
			//$JS .= JET_EOL.'console.debug("'.$component.'");'.JET_EOL;
			$JS .= $this->component_scripts[$component];
			$JS .= JET_EOL.'//-----------------------------'.JET_EOL.JET_EOL;
		}

		return $JS;
	}
}