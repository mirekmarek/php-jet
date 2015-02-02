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
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $component_scripts = [];

	/**
	 * @var array
	 */
	protected $required_components = [];

	/**
	 * @var array
	 */
	protected $components = ['dojo'];

	/**
	 * @var array
	 */
	protected $packages = [];

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
	protected $i18n = [];


	/**
	 * @param string $base_URL
	 * @param Locale $locale
	 * @param array $packages
	 * @param array $components
	 */
	public function __construct( $base_URL, Locale $locale, $packages, $components ) {

		$base_path = str_replace('_URI%', '_PATH%', $base_URL);


		$this->base_path = $base_path;
		$this->base_URL = $base_URL;
		$this->locale = $locale;


		foreach( $packages as $dojo_package ) {
			$this->registerPackage($dojo_package);
		}


		$this->required_components = $components;
	}

	/**
	 * @param $package
	 */
	public function registerPackage( $package ) {
		$this->packages[] = $package;
	}

	/**
	 *
	 * @return string
	 */
	public function getKey() {

		$key = implode(';', $this->required_components);
		$key = $this->locale.'-'.md5($key);

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

		if(!in_array($namespace, $this->packages)) {

			/*
			$script = $this->getScript($component, $base_path);


			list($components, $requires, $templates) = $this->parseDependencies( $component, $script, true );


			var_dump($namespace, $components, $requires, $templates, $script);
			die('???');
			*/

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

		list($components, $requires ) = $this->parseDependencies( $component, $script );


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
	 * @param string $component
	 * @param string $script
	 *
	 * @return array
	 */
	protected function parseDependencies(
		/** @noinspection PhpUnusedParameterInspection */
		$component,
		&$script
	) {

		$requires = [];
		$components = [];

		$matches = [];

		if(preg_match_all('/define\(([^,]*),"([^"]*)"\.split\(" "\)/i', $script, $matches, PREG_SET_ORDER)) {
			foreach( $matches as $match ) {
				$c = $match[1];
				$r = preg_replace('/[ ]{2,}/', '',$match[2] );
				$r = str_replace(' ', '","',$r);

				$replacement = 'define('.$c.',["'.$r.'"]';

				$script = str_replace($match[0], $replacement, $script);
			}
		}

		preg_match_all('/define\(([^,]*),[ ]*\[([^\]]*)]/i', $script, $matches, PREG_SET_ORDER);


		foreach($matches as $match) {
			$match[2] = preg_replace('/\/\/.*/i', '', $match[2]);

			$_component = str_replace(' ', '', str_replace("'", '', str_replace('"','', $match[1])));
			$_require = explode(',', str_replace(' ', '', str_replace("'", '', str_replace('"','', $match[2]))));

			foreach($_require as $r) {

				$r = trim($r);

				if(!$r) {
					continue;
				}

				if(strpos($r, '!')!==false) {
					if(strpos($r,'?')!==false) {
						//$_require = array_merge($_require, $this->parseRegularExpr($r));
					} else {
						$original_str = $r;

						list( $ns, $param ) = explode('!', $r);

						if(!$ns || !$param) {
							continue;
						}

						$ns = $this->resolveComponentPath( $_component, $ns );
						$param = $this->resolveComponentPath( $_component, $param );


						switch( $ns ) {
							case 'dojo/text':
								$script = str_replace($original_str, '__get_template!'.$param, $script );

								if(!in_array($param, $this->templates)) {
									$this->templates[] = $param;
								}

								continue;
							break;
							case 'dojo/i18n':
								$script = str_replace($original_str, 'dojo/i18n!'.$param, $script );

								if(!in_array($param, $this->i18n)) {
									$this->i18n[] = $param;
								}
							break;
							case 'dojo/query':
							break;
							default:
								$requires[] = $ns;
								//var_dump($original_str, $ns, $param);
							break;
						}

					}

					continue;
				}

				$r = $this->resolveComponentPath( $_component, $r );

				if(!in_array($r, $requires)) {
					$requires[] = $r;
				}
			}


			$components[] = $_component;
		}

		foreach( $requires as $i=>$r ) {
			if(in_array($r, $components)) {
				unset( $requires[$i] );
			}
		}


		return [ $components, $requires ];
	}

	/**
	 * @param string $component
	 * @param string|null $base_path
	 *
	 * @return string
	 */
	protected function getScript( $component, $base_path = null ) {
		$base_path = $base_path ? $base_path : $this->base_path;

		$path = Data_Text::replaceSystemConstants( $base_path . $component . '.js' );

		if($path[0]=='/' && $path[1]=='/') {
			$path = 'http:'.$path;
		}

		$script = IO_File::read( $path );

		//@ sourceMappingURL=place.js.map
		$script = preg_replace('/\/\/@ sourceMappingURL=.*map/', '', $script);

		return $script;
	}

	/**
	 * @param string $component
	 * @param string|null $base_path
	 * @return bool
	 */
	protected function getScriptExists( $component, $base_path = null ) {
		$base_path = $base_path ? $base_path : $this->base_path;

		$path = Data_Text::replaceSystemConstants( $base_path . $component . '.js' );

		if($path[0]=='/' && $path[1]=='/') {
			$path = 'http:'.$path;
		}

		return IO_File::exists( $path );

	}

	/**
	 * @param string $template_path
	 * @return string
	 */
	protected function getTemplate( $template_path ) {
		$path = Data_Text::replaceSystemConstants( $this->base_path . $template_path );

		if($path[0]=='/' && $path[1]=='/') {
			$path = 'http:'.$path;
		}

		$template = IO_File::read( $path );

		return $template;
	}

	/**
	 * @param string $component
	 * @param string $require
	 * @return string
	 */
	protected function resolveComponentPath( $component, $require ) {
		if($require[0]=='.') {
			$dir = dirname($component);


			if($require[1]=='/') {
				$require = $dir . substr($require, 1);
			} else {
				$require = explode('/', $require);

				while( $require[0]=='..' ) {
					array_shift($require);
					$dir = dirname($dir);
				}

				$require = $dir.'/'.implode('/', $require);

			}
		}


		return $require;
	}



	/**
	 * @return string
	 */
	public function createPackage() {
		$dojo_main_key = 'dojo/dojo';
		$base_URL = Data_Text::replaceSystemConstants($this->base_URL);
		$language = strtolower($this->locale->getLanguage());


		$this->requireComponent('dojo/dojo');

		foreach( $this->required_components as $component ) {
			$this->requireComponent( $component );
		}

		$JS = '';



		$JS .= ' var __templates={};'.JET_EOL;

		foreach( $this->templates as $template ) {
			$JS .= '__templates['.json_encode($template).'] = '.json_encode($this->getTemplate($template)).';'.JET_EOL;
		}


		$this->component_scripts[$dojo_main_key] .= JET_EOL;
		foreach( $this->packages as $package ) {
			$this->component_scripts[$dojo_main_key] .= "dojo.registerModulePath('".$package."','".$base_URL."".$package."');".JET_EOL;

		}
		$this->component_scripts[$dojo_main_key] .= 'define("__get_template", [], function(){ return { dynamic: true, load: function(id, require, load){ load(__templates[id]); }};});'.JET_EOL;


		foreach($this->i18n as $i18n) {

			$i18n_lang_script = dirname($i18n).'/'.$language.'/'.basename($i18n);

			$this->component_scripts[$dojo_main_key] .= JET_EOL.'//i18n '.$i18n.JET_EOL;
			$this->component_scripts[$dojo_main_key] .= $this->getScript( $i18n ).JET_EOL;
			if($this->getScriptExists($i18n_lang_script)) {

				$this->component_scripts[$dojo_main_key] .= $this->getScript( $i18n_lang_script ).JET_EOL;
			}
			$this->component_scripts[$dojo_main_key] .= '//-----------------------------'.JET_EOL;

		}

		foreach($this->components as $component) {
			if(!isset($this->component_scripts[$component])) {
				continue;
			}


			$JS .= JET_EOL.'// '.$component.JET_EOL;
			$JS .= $this->component_scripts[$component];
			$JS .= JET_EOL.'//-----------------------------'.JET_EOL.JET_EOL;
		}

		return $JS;
	}


	/**
	 * @return string
	 */
	public function getPackageFileName() {
		$key = $this->getKey();
		return Mvc_Layout::JS_PACKAGES_DIR_NAME.'dojo-'.$key.'.js';
	}

	/**
	 * @return string
	 */
	public function getPackageFilePath() {
		return JET_PUBLIC_PATH.$this->getPackageFileName();
	}

	/**
	 *
	 */
	public function getPackageURI() {
		return '%JET_PUBLIC_URI%'.$this->getPackageFileName();
	}

	/**
	 *
	 */
	public function generatePackageFile() {

		$package_path = $this->getPackageFilePath();

		if(!IO_File::exists($package_path)) {


			IO_File::write(
				$package_path,
				$this->createPackage()
			);
		}

	}
}