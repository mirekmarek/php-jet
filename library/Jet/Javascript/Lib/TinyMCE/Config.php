<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib_TinyMCE
 */
namespace Jet;

/**
 * Class Javascript_Lib_TinyMCE_Config
 *
 * @JetConfig:data_path = '/js_libs/TinyMCE'
 * @JetConfig:section_is_obligatory = false
 */
class Javascript_Lib_TinyMCE_Config extends Application_Config {

	/**
	 * @var array
	 */
	protected $_editor_configs = null;


	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'Version of TinyMCE'
	 * @//JetConfig:default_value = '3.5.6'
	 * @JetConfig:default_value = '4.1'
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'TinyMCE scripts URI or URL'
	 * @//JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_URI%tiny_mce/%VERSION%/tiny_mce.js'
	 * @JetConfig:default_value = '//tinymce.cachefly.net/%VERSION%/tinymce.min.js'
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $URI;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @//JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_URI%Jet/WYSIWYG/TinyMCE35.js'
	 * @JetConfig:default_value = '%JET_PUBLIC_SCRIPTS_URI%Jet/WYSIWYG/TinyMCE40.js'
	 *
	 * @var string
	 */
	protected $wrapper_URI;


	/**
	 * @JetConfig:type = Config::TYPE_ARRAY
	 * @JetConfig:description = 'Editor configurations. See http://www.tinymce.com/wiki.php/Configuration. Language directive is set according to current language. JET constants can be used for content_css directive .'
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = array( 'default' => array( 'mode' => 'exact', 'theme' => 'modern', 'apply_source_formatting' => true, 'remove_linebreaks' => false, 'entity_encoding' => 'raw', 'convert_urls' => false, 'verify_html' => true, 'content_css' => '%JET_PUBLIC_STYLES_URI%wysiwyg.css' ) )
	 *
	 * @var array
	 */
	protected $editor_configs;

    /**
     * @var string
     */
    protected $language = '';

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Get URI/URL where TinyMCE script is placed
	 *
	 * @return string
	 */
	public function getURI(){
		$replacements = [
			'VERSION' => $this->version,
		];

		return Data_Text::replaceData($this->URI, $replacements);
//		return Data_Text::replaceSystemConstants($this->URI, $replacements);
	}

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }




	/**
	 * @return array
	 * @throws Javascript_Lib_TinyMCE_Exception
	 */
	public function getEditorConfigs() {
		if( $this->_editor_configs===null ) {

			$this->_editor_configs = [];

			if(!$this->editor_configs) {
				throw new Javascript_Lib_TinyMCE_Exception(
					'Main configuration /js_libs/TinyMCE/editor_configs/* is missing. ',
					Javascript_Lib_TinyMCE_Exception::CODE_EDITOR_CONFIGURATION_MISSING
				);

			}

			foreach($this->editor_configs as $name=>$cfg) {

				if(isset($cfg['content_css'])) {
					$cfg['content_css'] = Data_Text::replaceSystemConstants($cfg['content_css']);
				}

				$this->_editor_configs[$name] = $cfg;
			}
		}

		return $this->_editor_configs;
	}

	/**
	 * @param string $editor_config_name
	 *
	 * @return array
	 *
	 * @throws Javascript_Lib_TinyMCE_Exception
	 */
	public function getEditorConfig( $editor_config_name ) {
		if( $this->_editor_configs===null ) {
			$this->getEditorConfigs();
		}

		if(!isset($this->_editor_configs[$editor_config_name])) {
			throw new Javascript_Lib_TinyMCE_Exception(
				'Unknown editor configuration \''.$editor_config_name.'\'. Main configuration /js_libs/TinyMCE/editor_configs/'.$editor_config_name.' is missing. ',
				Javascript_Lib_TinyMCE_Exception::CODE_UNKNOWN_EDITOR_CONFIGURATION
			);
		}

        $cfg = $this->_editor_configs[$editor_config_name];

        if( ($language = $this->getLanguage()) ) {
            $cfg['language'] = Mvc::getCurrentLocale()->getLanguage();
        }

		return $cfg;
	}

	/**
	 * @return string
	 */
	public function getWrapperURI() {
		return $this->wrapper_URI;
		//return Data_Text::replaceSystemConstants($this->wrapper_URI);
	}
}