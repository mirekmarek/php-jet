<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

abstract class JetML_Widget_Abstract extends Object {

	/**
	 * @var JetML
	 */
	protected $parser;

	/**
	 * @var \DOMElement
	 */
	protected $node;

	/**
	 *
	 * @var array
	 */
	protected $internal_properties = array('icon', 'icon_size', 'flag', 'flag_size' );

	/**
	 *
	 * @var array
	 */
	protected $HTML_properties = array('style', 'class', 'id', 'onclick', 'ondblclick', 'onmousedown', 'onmousemove', 'onmouseover', 'onmouseout', 'onmouseup', 'onkeydown', 'onkeypress', 'onkeyup', 'onblur', 'onchange', 'onfocus', 'onreset', 'onselect' );


	/**
	 * @var bool|null
	 */
	protected $do_not_translate_texts = null;

	/**
	 * @var array|null
	 */
	protected $translation_data = null;

	/**
	 * @var string|null
	 */
	protected $custom_translator_namespace = null;

	/**
	 * @var Locale|null
	 */
	protected $custom_translator_locale = null;



	/**
	 * @param JetML $parser
	 * @param \DOMElement $node
	 *
	 * @return JetML_Widget_Abstract
	 */
	public function __construct( JetML $parser, \DOMElement $node){
		$this->parser = $parser;
		$this->node = $node;

	}

	/**
	 * @param string $attribute
	 * @param string $default_value
	 * @return string
	 */
	public function getNodeAttribute( $attribute, $default_value='' ) {
		return $this->node->hasAttribute($attribute) ?
				$this->node->getAttribute($attribute)
				:
				$default_value;
	}


	/**
	 * @return \DOMElement
	 */
	abstract public function getReplacement();


	/**
	 * @param string $scope
	 * @param string $icon
	 * @param string $size
	 *
	 * @return string
	 */
	protected function getIconURL($scope, $icon, $size) {
		if(substr($icon,0,7)=='module/') {
			$icons_URL = explode('/', $icon);

			$module_name = $icons_URL[1];
			$icon = $icons_URL[2];

			$module_name = str_replace('\\', '/', $module_name);

			$icons_URL = JET_MODULES_URI.$module_name.'/public/icons/'.$size.'/'.$icon;
		} else {

			$icons_URL = $this->parser->{'get'.$scope.'sURL'}();
			$icons_URL .= $size . '/';
			$icons_URL .= $icon;
		}

		$icons_URL = str_replace('\\', '/', $icons_URL);

		$icons_URL .= '.'.$this->parser->getIconFileSuffix();

		return $icons_URL;
	}


	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function getIcon(){

		$title = $this->getTranslation($this->getNodeAttribute('title'));

		if($this->node->hasAttribute('flag')){
			return $this->_getIconSnippet(
				'Flag',
				$this->getNodeAttribute('flag'),
				$this->getNodeAttribute('flag_size'),
				$title
			);

		} else if($this->node->hasAttribute('icon')){
			return $this->_getIconSnippet(
				'Icon',
				$this->getNodeAttribute('icon'),
				$this->getNodeAttribute('icon_size'),
				$title
			);
		}

		if(!$title) {
			return null;
		}

		$span = $this->parser->getDOMDocument()->createElement('span', $title);

		return $span;
	}


	/**
	 * @param $scope
	 * @param $icon
	 * @param $size
	 * @param $title
	 *
	 * @return \DOMElement[]|\DOMElement
	 */
	protected function _getIconSnippet( $scope, $icon, $size, $title ) {
		if(!$size){
			$size = $this->parser->{'getIconDefaultSize'}();
		}

		$icon_URL = $this->getIconURL($scope, $icon, $size);

		if($scope=='Flag') {
			$icon_data = $this->parser->getIconSizeData('flag_'.$size);
		} else {
			$icon_data = $this->parser->getIconSizeData($size);
		}



		$span = $this->parser->getDOMDocument()->createElement('span', $title);

		if($icon){
			$img = $this->parser->getDOMDocument()->createElement('img');
			$img->setAttribute('src', $icon_URL);
			$img->setAttribute('title', $title);

			foreach($icon_data as $k=>$v) {
				$img->setAttribute($k, $v);
			}

			return array( $img, $span );
		} else {
			return $span;
		}
	}


	/**
	 * Returns translation.
	 *
	 * @see Translator
	 *
	 * @param string $phrase
	 *
	 * @return string
	 */
	public function getTranslation( $phrase ) {
		if($this->do_not_translate_texts===null) {
			$this->do_not_translate_texts = $this->getNodeAttribute('do_not_translate_texts', '');
			$this->translation_data = $this->getNodeAttribute('translation_data', '');
			$this->custom_translator_namespace = $this->getNodeAttribute('custom_translator_namespace', '');
			$this->custom_translator_locale = $this->getNodeAttribute('custom_translator_locale', '');

			$this->do_not_translate_texts = (strtolower($this->do_not_translate_texts)=='true');
			$this->translation_data = $this->translation_data ? json_decode( $this->translation_data ) : array();
			$this->custom_translator_namespace = $this->custom_translator_namespace ? $this->custom_translator_namespace : null;
			$this->custom_translator_locale = $this->custom_translator_locale ? $this->custom_translator_locale : null;
		}

		if($this->do_not_translate_texts) {
			if(!$this->translation_data) {
				return $phrase;
			}

			return Data_Text::replaceData($phrase, $this->translation_data);
		}

		return Tr::_($phrase, $this->translation_data, $this->custom_translator_namespace, $this->custom_translator_locale);
	}

}