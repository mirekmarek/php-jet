<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

class Javascript_Lib_Dojo extends Javascript_Lib_Abstract {
	
	/**
	 * Dojo configuration
	 *
	 * @var Javascript_Lib_Dojo_Config
	 */
	protected $config = null;


   
	/**
	 * Dojo config (djConfig)
	 *
	 * @var array
	 */
	protected $djConfig = array(
			"parseOnLoad" => true,
			"locale" => "en-us",
			"isDebug" => false,
		);

	/**
	 * @param Mvc_Layout $layout
	 */
	public function __construct( Mvc_Layout $layout ) {
		parent::__construct( $layout );

		$this->config = new Javascript_Lib_Dojo_Config();

		$locale = $this->layout->getRouter()->getLocale();
		$locale = strtolower($locale->getLanguage())."-".strtolower($locale->getRegion());

		$this->setOption("parseOnLoad", $this->config->getParseOnLoad());
		$this->setOption("isDebug", $this->config->getIsDebug());
		$this->setOption("locale", $locale);
	}

	/**
	 * Returns dojo version
	 * 
	 * @return string 
	 */
	public function getVersionNumber() {
		return $this->config->getVersion();
	}

	/**
	 * Sets option including main setting and djConfig setting
	 *
	 * @param string $option
	 * @param mixed $value
	 *
	 * @throws Javascript_Exception
	 */
	public function setOption( $option, $value ) {

		switch($option){
			case "parseOnLoad":
			case "locale":
			case "isDebug":
				$this->djConfig[$option] = $value;
			break;

			default:
				throw new Javascript_Exception(
					"Unknown Dojo option: '{$option}'",
					Javascript_Exception::CODE_UNKNOWN_JS_LIB_OPTION
				);
			break;
		}
	}
	
	/**
	 * Equivalent to dojo.require().
	 * If $parameters["css"] is set (string or array or strings), additional CSS for given component is written into output
	 *
	 * @param string $component - dojo module
	 * @param array $parameters(optional)
	 * @return void 
	 */
	public function requireComponent( $component, $parameters=array() ) {
		if( in_array( $component, $this->required_components ) ) {
			return;
		}

		$this->required_components[] = $component;
		if(
			isset($parameters["css"]) &&
			$parameters["css"]
		){
			if(!is_array($parameters["css"])){
				$parameters["css"] = array($parameters["css"]);
			} 
			foreach($parameters["css"] as $css){
				if(in_array($css, $this->required_components_CSS)){
					continue;
				}
				$this->required_components_CSS[] = $css;
			}
		}
	}

	/**
	 * Returns generated HTML code that replaces <jet_layout_javascripts/> in layout
	 *
	 * @throws Javascript_Exception
	 * @return string
	 */
	public function getHTMLSnippet() {

		$result = "";

		$result .= '<link rel="stylesheet" type="text/css" href="'.$this->config->getURI().'dojo/resources/dojo.css">'."\n";
		$result .= '<link rel="stylesheet" type="text/css" href="'.$this->config->getThemeURI().'">'."\n";

		$result .= '<script type="text/javascript">'."\n";
		$result .= '  var djConfig = '.json_encode($this->djConfig).';'."\n";
		$result .= '</script>'."\n";

		$source_URL = $this->config->getURI();

		if($this->required_components_CSS){
			foreach($this->required_components_CSS as $css){
				$css = $this->config->replaceConstants($css);

				$result .= '<link rel="stylesheet" type="text/css" href="'.$source_URL.$css.'">'."\n";
			}
		}

		$result .= '<script type="text/javascript" src="'.$this->config->getDojoJsURI().'" charset="utf-8"></script>'."\n";
		$package_URL = $this->config->getDojoPackageURI();
		if($package_URL){
			$result .= '<script type="text/javascript" src="'.$package_URL.'" charset="utf-8"></script>'."\n";
		}

		if($this->required_components){
			$result .= '<script type="text/javascript">'."\n";
			foreach( $this->required_components as $rc ) {
				if(!$rc) {
					continue;
				}
				$result .= 'dojo.require("'.$rc.'");'."\n";
			}
			$result .= '</script>';
		}

		return $result;
	}

	/**
	 * This method is called when processing is completed and the content is placed in its positions
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	public function finalPostProcess( &$result, Mvc_Layout $layout ) {
		$replace_data = array(
			"DOJO_THEME" => $this->config->getDefaultTheme()
		);

		$result = Data_Text::replaceData($result, $replace_data);
	}
}