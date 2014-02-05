<?php
/**
 *
 *
 *
 * @see Mvc/readme.txt
 *
 * The layout is similar to the view. Therefore allows to use. phtml files to generate output.
 * It also allows pass the layout script variables ($layout->setVar('variable', 'value'); )
 *
 * Of course, but has characteristics of highly specific for the carry out its role:
 * Allows for each positions in the output place specific content.
 *
 * - Handles the tags to determine the positions in the layout  (tag: <jet_layout_position name='positionName'/>, <jet_layout_main_position/> ), @see addOutputPart::addOutput() @see Mvc_Layout::handlePositions()
 * - Handles the tags for JavaScript initialization   (tag: <jet_layout_javascripts/> ), @see Mvc_Layout::handleJavascript(), @see Mvc_Layout::requireJavascriptLib(), @see JavaScript_Abstract, @see Mvc/readme.txt
 * - Handles the tags for layout parts including (tag: <jet_layout_part name='part-name'/>), @see Mvc_Layout::handleParts()
 * - Handles the tags for modules dispatching (tag: <jet_module:ModuleName:controllerAction action_param='action_param_value' />)
 *
 * NOTICE: @see Mvc_Layout_Postprocessor_Interface
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

class Mvc_Layout extends Mvc_View_Abstract  {

	const TAG_PART = 'jet_layout_part';
	const TAG_POSITION = 'jet_layout_position';
	const TAG_MAIN_POSITION = 'jet_layout_main_position';

	const TAG_JAVASCRIPT = 'jet_layout_javascripts';

	const TAG_META_TAGS = 'jet_layout_meta_tags';
	const TAG_HEADER_SUFFIX = 'jet_layout_header_suffix';
	const TAG_BODY_PREFIX = 'jet_layout_body_prefix';
	const TAG_BODY_SUFFIX = 'jet_layout_body_suffix';


	const TAG_MODULE = 'jet_module:';

	const DEFAULT_OUTPUT_POSITION = '__main__';

	const JS_REPLACEMENT_REGEXP = '~Jet\.modules\.([a-zA-Z_]+)\.~sU';

	const JS_REPLACEMENT_CURRENT_MODULE = 'CURRENT_MODULE';
	const JS_REPLACEMENT_UI_MANAGER_MODULE = 'UI_MANAGER_MODULE';


	/**
	 * Data of the output that will be placed into the layout
	 *
	 * @var Mvc_Layout_OutputPart[]
	 */
	protected $output_parts = array();

	/**
	 * @see Mvc_Layout::requireJavascript();
	 *
	 * @var Javascript_Lib_Abstract[]
	 */
	protected $required_javascript = array();

	/**
	 *
	 * @var Mvc_Router_Abstract
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $UI_container_ID = '';

	/**
	 * @var string
	 */
	protected $UI_container_ID_prefix = '';

	/**
	* Constructor
	*
	* @param string $scripts_dir
	* @param string $script_name
	*/
	public function __construct( $scripts_dir, $script_name ) {
		$this->setScriptsDir($scripts_dir);
		$this->setScriptName($script_name);

		$this->_data = new Data_Array();
	}


	/**
	 *
	 * @return Mvc_Router_Abstract
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * @param Mvc_Router_Abstract $router
	 */
	public function setRouter(Mvc_Router_Abstract $router) {
		$this->router = $router;
		$this->UI_container_ID = $router->getUIManagerModuleInstance()->getUIContainerID();
		if($this->UI_container_ID) {
			$this->UI_container_ID_prefix = $this->UI_container_ID.'_';
		} else {
			$this->UI_container_ID_prefix = '';
		}
	}


	/**
	 * @return string
	 */
	public function getUIContainerID() {
		return $this->UI_container_ID;
	}

	/**
	 * @return string
	 */
	public function getUIContainerIDPrefix() {
		return $this->UI_container_ID_prefix;
	}


	/**
	 * Enables JetML postprocessor
	 * @return JetML
	 */
	public function enableJetML() {
		if($this->_data->exists('JetML_postprocessor')) {
			return $this->_data->getRaw('JetML_postprocessor');
		}

		$this->setVar('JetML_postprocessor', JetML_Factory::getJetMLPostprocessorInstance() );

		return $this->_data->getRaw('JetML_postprocessor');
	}

	/**
	 * Disables JetML
	 */
	public function disableJetML() {
		$this->unsetVar('JetML_postprocessor');
	}

	/**
	 * Returns:
	 *
	 * If $include_tag=false then
	 *
	 * array( 'position_name'=>'position_name' )
	 *
	 * If $include_tag=true then
	 *
	 * array( 'position_name'=>'<position_tag>' )
	 *
	 * @param bool $include_tag (optional, default: false)
	 *
	 * @return array
	 */
	public function getPositions( $include_tag=false ) {
		return $this->getPositionsFromResult( $this->_render(), $include_tag );

	}

	/**
	 * Returns:
	 *
	 * If $include_tag=false then
	 *
	 * array( 'position_name'=>'position_name' )
	 *
	 * If $include_tag=true then
	 *
	 * array( 'position_name'=>'<position_tag>' )
	 *
	 * @param $result
	 *
	 * @param bool $include_tag
	 *
	 * @return array
	 */
	public function getPositionsFromResult( $result, $include_tag=false ) {
		$positions = array();

		$matches = array();
		if(preg_match_all('/<'.Mvc_Layout::TAG_POSITION.'[ ]{1,}name="([a-zA-Z0-9\-_ ]*)"[^\/]*\/>/i', $result, $matches, PREG_SET_ORDER)) {

			foreach($matches as $match) {
				$orig = $match[0];
				$position = $match[1];

				if($position[0]=='-') {
					continue;
				}

				if($include_tag) {
					$positions[$position] = $orig;
				} else {
					$positions[$position] = $position;
				}
			}
		}

		if(preg_match_all('/<'.Mvc_Layout::TAG_MAIN_POSITION.'[^\/]*\/>/i', $result, $matches, PREG_SET_ORDER)) {
			foreach($matches as $match) {
				$orig = $match[0];

				if($include_tag) {
					$positions[Mvc_Layout::DEFAULT_OUTPUT_POSITION] = $orig;
				} else {
					$positions[Mvc_Layout::DEFAULT_OUTPUT_POSITION] = Mvc_Layout::DEFAULT_OUTPUT_POSITION;
				}
			}
		}

		return $positions;
	}

	/**
	 * Adds output to specified position
	 *
	 * @param string $output
	 * @param string $output_ID
	 * @param string $module_name
	 * @param string $position (optional, default: main position)
	 * @param bool $position_required (optional, default:true)
	 * @param int $position_order (optional, default:null)
	 *
	 */
	public function addOutputPart(
			$output,
			$output_ID,
			$module_name,
			$position = self::DEFAULT_OUTPUT_POSITION,
			$position_required = true,
			$position_order = null
	) {
		if(isset($this->output_parts[$output_ID])) {
			$current_output = $this->output_parts[$output_ID]->getOutput();
			$this->output_parts[$output_ID]->setOutput( $current_output.$output );
			return;
		}

		if(
			$position_order === null ||
			$position_order===false
		) {
			$position_order = 0;
			foreach($this->output_parts as $o) {
				if($o->getPosition()!==$position) {
					continue;
				}

				if($o->getPositionOrder()>=$position_order) {
					$position_order = $o->getPositionOrder() + 1;
				}

			}
		}


		$o = new Mvc_Layout_OutputPart($output_ID, $output, $position, $position_required, $position_order, $module_name );

		$this->output_parts[$output_ID] = $o;
	}


	/**
	 * @param Mvc_Layout_OutputPart[] $output_parts
	 */
	public function setOutputParts(array $output_parts) {
		$this->output_parts = array();
		foreach($output_parts as $output_part) {
			$this->setOutputPart($output_part);
		}
	}

	/**
	 * @return Mvc_Layout_OutputPart[]
	 */
	public function getOutputParts() {
		return $this->output_parts;
	}

	/**
	 * @param $ID
	 *
	 * @return Mvc_Layout_OutputPart|null
	 */
	public function getOutputPart( $ID ) {
		return isset($this->output_parts[$ID]) ? $this->output_parts[$ID] : null;
	}

	/**
	 * @param Mvc_Layout_OutputPart $output_part
	 */
	public function setOutputPart( Mvc_Layout_OutputPart $output_part ) {
		$this->output_parts[$output_part->getID()] = $output_part;
	}

	/**
	 * @return string
	 *
	 * @throws Mvc_Layout_Exception
	 */
	protected  function _render() {
		if($this->_script_name===false) {
			$result = '<'.self::TAG_MAIN_POSITION.'/>';
		} else {
			$this->getScriptPath();

			ob_start();

			/** @noinspection PhpIncludeInspection */
			include $this->_script_path;

			if(static::$_add_script_path_info) {
				echo JET_EOL.'<!-- LAYOUT: '.$this->_script_name.' --> '.JET_EOL;
			}

			$result = ob_get_clean();
		}

		return $result;
	}


	/**
	 * Create instance of class that provides JavaScript toolkit initialization and its including into layout.
	 *
	 * Example:
	 *
	 * We want to initialize Dojo toolkit and to use dijit.form.InputBox class (component)
	 * Well. We had to add some code layout. Something like this:
	 *
	 * <code>
	 * <script type='text/javascript'>
	 *  var djConfig = {'parseOnLoad':false,'locale':'en-us'};
	 * </script>
	 *
	 * <link rel='stylesheet' type='text/css' href='http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojox/grid/resources/claroGrid.css'>
	 * <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/dojo.xd.js' charset='utf-8'></script>
	 * <script type='text/javascript'>
	 * dojo.require('dijit.form.InputBox');
	 * </script>
	 * </code>
	 * .... and more ...
	 *
	 * How to do it? Manually? It is not a good idea.
	 * Do it like this:
	 *
	 * <code>
	 *	$Dojo = $layout->requireJavascriptLib('Dojo');
	 *	$Dojo->requireComponent('dijit.form.InputBox');
	 * </code>
	 *
	 * And that's all!
	 *
	 * ATTENTION:
	 * The JavaScript tag ( <jet_layout_javascripts/> ) MUST exist in layout script !!!
	 *
	 *
	 * @see JavaScript_Abstract
	 * @see Mvc/readme.txt
	 *
	 * @param string $javascript
	 *
	 * @return Javascript_Lib_Abstract
	 */
	public function requireJavascriptLib( $javascript ) {
		if( !isset($this->required_javascript[$javascript]) ) {
			$this->required_javascript[$javascript] = Javascript_Factory::getJavascriptLibInstance( $javascript, $this );
		}

		return $this->required_javascript[$javascript];
	}

	/**
	 * Returns rendered layout according to specified .phtml file name
	 * and also does the output postprocessing by relevant objects
	 * (@see Mvc_Layout_Postprocessor_Interface, @see  Mvc_Layout::$data )
	 *
	 * @throws Mvc_Layout_Exception
	 *
	 * @return string
	 */
	public function render() {

		$result = $this->_render();

		$this->handleModules( $result );

		foreach($this->output_parts as $o) {

			/**
			 * @var Mvc_Layout_OutputPart $o
			 */
			$res = $o->getOutput();
			$this->handleModules($res);
			$this->handleModulesJavascripts($res, $o->getModuleName());
			$o->setOutput($res);

		}

		$this->handleParts( $result );

		foreach( $this->_data->getRawData() as $item ) {
			if(
				!is_object($item) ||
				!$item instanceof Mvc_Layout_Postprocessor_Interface
			) {
				continue;
			}

			/**
			 * @var Mvc_Layout_Postprocessor_Interface $item
			 */
			$item->layoutPostProcess( $result, $this, $this->output_parts );
		}


		$this->handlePositions( $result );

		$this->handleSitePageTags( $result );

		$current_module_name = '';

		if($this->router) {
			$current_module_name = $this->router->getUIManagerModuleName();
		}
		$this->handleModulesJavascripts($result, $current_module_name);

		foreach( $this->_data->getRawData() as $item ) {
			if(
				!is_object($item) ||
				!$item instanceof Mvc_Layout_Postprocessor_Interface
			) {
				continue;
			}

			$item->finalPostProcess( $result, $this, $this->output_parts );
		}
		$this->handleJavascripts( $result );
		$this->handleConstants( $result );

		$this->output_parts = array();

		return $result;
	}

	/**
	 * @param string &$result
	 */
	protected function handlePositions( &$result ) {
		$output = array();
		$sort_hash = array();

		foreach( $this->output_parts as $o_ID=>$o ) {
			$sort_hash[ $o_ID ] = $o->getPositionOrder();
		}

		asort( $sort_hash );
		foreach( array_keys($sort_hash) as $o_ID  ) {
			$output[ $o_ID ] = $this->output_parts[ $o_ID ];
		}

		$this->output_parts = $output;


		do {
			$matches_count = 0;

			foreach( $this->output_parts as $o ) {
				/**
				 * @var Mvc_Layout_OutputPart $o
				 */
				$output_result = $o->getOutput();

				$matches_count = $this->_handlePositions( $output_result, false );

				if($matches_count) {
					$o->setOutput( $output_result );
					continue 2;
				}

			}

		} while( $matches_count>0 );

		$this->_handlePositions( $result, true );

	}

	/**
	 * Place the output to an adequate position
	 *
	 * @param string &$result
	 * @param bool $handle_main_position
	 *
	 * @return int
	 */
	protected function _handlePositions( &$result, $handle_main_position ) {

		$matches_count = 0;
		$matches = array();

		if(preg_match_all('/<'.self::TAG_POSITION.'[ ]{1,}name="([a-zA-Z0-9\-_ ]*)"[^\/]*\/>/i', $result, $matches, PREG_SET_ORDER)) {

			$matches_count = $matches_count + count($matches);

			foreach($matches as $match) {
				$orig = $match[0];
				$position = $match[1];

				$output_on_position = '';

				foreach( $this->output_parts as $o_ID=>$o ) {
					if($o->getPosition()!=$position) {
						continue;
					}

					$output_on_position .= $o->getOutput();
					unset( $this->output_parts[$o_ID] );
				}

				$result = str_replace($orig, $output_on_position, $result);

			}
		}


		if(
			$handle_main_position &&
			preg_match_all('/<'.self::TAG_MAIN_POSITION.'[^\/]*\/>/i', $result, $matches, PREG_SET_ORDER)
		) {
			$orig = $matches[0][0];
			$output_on_position = '';

			foreach( $this->output_parts as $o_ID=>$o ) {
				if( $o->getPosition()==self::DEFAULT_OUTPUT_POSITION ) {
					$output_on_position .= $o->getOutput();
					unset( $this->output_parts[$o_ID] );
				}
			}

			foreach( $this->output_parts as $o_ID=>$o ) {
				if( !$o->getPositionRequired() ) {
					$output_on_position .= $o->getOutput();
					unset( $this->output_parts[$o_ID] );
				}
			}

			$result = str_replace($orig, $output_on_position, $result);
		}

		return $matches_count;

	}

	/**
	 * @param string &$result
	 */
	protected function handleConstants( &$result ) {
		if($this->router) {
			$data = array();
			$data['JET_SITE_BASE_URI'] = $this->router->getSiteBaseURI();
			$data['JET_SITE_IMAGES_URI'] = $this->router->getSiteImagesURI();
			$data['JET_SITE_SCRIPTS_URI'] = $this->router->getSiteScriptsURI();
			$data['JET_SITE_STYLES_URI'] = $this->router->getSiteStylesURI();
			$data['JET_UI_CONTAINER_ID'] = $this->getUIContainerID();
			$data['JET_UI_CONTAINER_ID_PREFIX'] = $this->getUIContainerIDPrefix();
			$data['JET_PAGE_TITLE'] = $this->router->getPage()->getTitle();

			$data['JET_SITE_TITLE'] = $this->router->getSite()->getLocalizedData($this->router->getLocale())->getTitle();
			$data['JET_LANGUAGE'] = $this->router->getLocale()->getLanguage();

			$result = Data_Text::replaceData($result, $data );
		}

	}

	/**
	 * @param string &$result
	 */
	protected function handleSitePageTags( &$result ) {
		if(
			!$this->router ||
			!($page = $this->router->getPage())
		) {
			$dat = array();
			$dat[self::TAG_META_TAGS] = '';
			$dat[self::TAG_HEADER_SUFFIX] = '';
			$dat[self::TAG_BODY_PREFIX] = '';
			$dat[self::TAG_BODY_SUFFIX] = '';
		} else {
			$dat = array();

			$site_localized_data = $this->router->getSite()->getLocalizedData($this->router->getLocale());

			$meta_tags = array();

			foreach($site_localized_data->getDefaultMetaTags() as $mt) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if($key==':') {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			foreach($page->getMetaTags() as $mt) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if($key==':') {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			$dat[self::TAG_META_TAGS] = '';

			foreach($meta_tags as $mt) {
				$dat[self::TAG_META_TAGS] .= JET_EOL.JET_TAB.$mt;
			}


			$dat[self::TAG_HEADER_SUFFIX] = $site_localized_data->getDefaultHeadersSuffix();
			$dat[self::TAG_BODY_PREFIX] = $site_localized_data->getDefaultBodyPrefix();
			$dat[self::TAG_BODY_SUFFIX] = $site_localized_data->getDefaultBodySuffix();

			if($page->getHeadersSuffix()) {
				$dat[self::TAG_HEADER_SUFFIX] = $page->getHeadersSuffix();
			}

			if($page->getBodyPrefix()) {
				$dat[self::TAG_BODY_PREFIX] = $page->getBodyPrefix();
			}
			if($page->getBodySuffix()) {
				$dat[self::TAG_BODY_SUFFIX] = $page->getBodySuffix();
			}
		}




		foreach($dat as $tag=>$rep_l) {
			$result = $this->_replaceTagByValue($result, $tag, $rep_l);
		}
	}

	/**
	 * Handle the Module tag  ( <jet_module:* /> )
	 *
	 * In fact it search and dispatch all modules included by the tag
	 *
	 * @see Mvc/readme.txt
	 *
	 * @param string &$result
	 */
	protected function handleModules( &$result ) {

		$matches = array();

		if( preg_match_all('/<'.self::TAG_MODULE.'([a-zA-Z_:\\\\]{3,})([^\/]*)\/>/i', $result, $matches, PREG_SET_ORDER) ) {

			foreach($matches as $match) {
				$orig_str = $match[0];

				$action_data = explode(':', $match[1]);
				if(!isset($action_data[1])) {
					$action_data[1] = Mvc_Dispatcher::DEFAULT_ACTION;
				}
				list($module_name, $action) = $action_data;

				$action_params = array();

				$_properties = substr(trim($match[2]), 0, -1);
				$_properties = preg_replace('/[ ]{2,}/i', ' ', $_properties);
				$_properties = explode( '" ', $_properties );

				foreach( $_properties as $property ) {
					if( !$property || strpos($property, '=')===false ) {
						continue;
					}

					$property = explode('=', $property);

					$property_name = array_shift($property);
					$property_value = implode('=', $property);

					$property_name = strtolower($property_name);
					$property_value = str_replace('"', '', $property_value);

					$action_params[$property_name] = $property_value;
				}

				$tmp_position = '--layout-tmp-pos-'.str_replace('\\', '-', $module_name).'-'.$action;

				$result = str_replace($orig_str, '<'.static::TAG_POSITION.' name="'.$tmp_position.'"/>', $result);

				$content_data = Mvc_Factory::getPageContentInstance();

				$content_data->setOutputPosition( $tmp_position );

				if($action_params) {
					$action_params = array($action_params);
				}

				$qi = new Mvc_Dispatcher_Queue_Item(
					$module_name,
					$action,
					$action_params,
					$content_data
				);

				$qi->setCustomServiceType( Mvc_Router::SERVICE_TYPE_STANDARD );
				$this->router->getDispatcherInstance()->dispatchQueueItem($qi);
			}
		}
	}

	/**
	 * @param string &$result
	 * @param string $current_module_name
	 */
	protected function handleModulesJavascripts( &$result, $current_module_name) {

		$current_module_name = str_replace('\\', '\\\\', $current_module_name);

		$matches = array();
		preg_match_all(static::JS_REPLACEMENT_REGEXP, $result, $matches, PREG_SET_ORDER);
		$replacements = array();

		if($this->router) {
			$UI_manager = $this->router->getUIManagerModuleInstance();
			foreach($matches as $match) {
				list($search, $module_name) = $match;
				switch( $module_name) {
					case self::JS_REPLACEMENT_CURRENT_MODULE:
						$replacements[$search] = $UI_manager->getLayoutJsReplacementCurrentModule($current_module_name);
						break;
					case self::JS_REPLACEMENT_UI_MANAGER_MODULE:
						$replacements[$search] = $UI_manager->getLayoutJsReplacementUiManagerModule();
						break;
					default:
						$module_name = str_replace('\\', '\\\\', $module_name);
						$replacements[$search] = $UI_manager->getLayoutJsReplacementModule($module_name);
				}
			}
		} else {
			foreach($matches as $match) {
				list($search, $module_name) = $match;
				switch( $module_name ) {
					case self::JS_REPLACEMENT_CURRENT_MODULE:
						if($this->UI_container_ID) {
							$replacements[$search] = 'Jet.modules.getModuleInstance(\''.$current_module_name.'\', \''.$this->UI_container_ID.'\').';

						} else {
							$replacements[$search] = 'Jet.modules.getModuleInstance(\''.$current_module_name.'\').';
						}
						break;
					case self::JS_REPLACEMENT_UI_MANAGER_MODULE:
						$replacements[$search] = 'Jet.getUIManagerModuleInstance().';
						break;
					default:
						$module_name = str_replace('\\', '\\\\', $module_name);
						$replacements[$search] = 'Jet.modules.getModuleInstance(\''.$module_name.'\').';
				}
			}
		}


		$result = str_replace(array_keys($replacements), array_values($replacements), $result);

	}

	/**
	 * Handle the JavaScript tag  ( <jet_layout_javascripts/> )
	 *
	 * @see Mvc_Layout::requireJavascript();
	 * @see JavaScript_Abstract
	 * @see Mvc/readme.txt
	 *
	 * @param string &$result
	 */
	protected function handleJavascripts( &$result ) {

		$JS_snippet = '';

		if($this->required_javascript) {
			foreach( $this->required_javascript as $JS ) {
				$JS_snippet .= $JS->getHTMLSnippet();
			}
		}

		$result = $this->_replaceTagByValue($result, self::TAG_JAVASCRIPT, $JS_snippet);


		foreach($this->required_javascript as $js) {
			$js->finalPostProcess($result, $this);
		}
	}

	/**
	 * @param string $output
	 * @param string $tag
	 * @param string $snippet
	 *
	 * @return mixed
	 */
	protected function _replaceTagByValue( $output, $tag, $snippet ) {
		$matches = array();

		if( preg_match_all('/<[ ]*'.$tag.'[ ]*\/>/i', $output, $matches, PREG_SET_ORDER) ) {
			$orig = $matches[0][0];


			$output = str_replace($orig, $snippet, $output);
		}

		if( preg_match_all('/<[ ]*'.$tag.'[ ]*>*<\/[ ]*'.$tag.'[ ]*>/i', $output, $matches, PREG_SET_ORDER) ) {
			$orig = $matches[0][0];


			$output = str_replace($orig, $snippet, $output);
		}

		return $output;

	}

}