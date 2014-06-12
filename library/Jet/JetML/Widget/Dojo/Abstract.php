<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Dojo_Abstract extends JetML_Widget_Abstract {
	const DOJO_TYPE_PROPERTY = 'data-dojo-type';
	const DOJO_PROPS_PROPERTY = 'data-dojo-props';

	/**
	 *
	 * @var string|array|bool $dojo_type
	 */
	protected $dojo_type = '';
	
	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'div';

	/**
	 *
	 * @var array
	 */
	protected $required_css = array();

	/**
	 *
	 * @var array
	 */
	protected $internal_properties = array('icon', 'icon_size', 'flag', 'flag_size', 'dojotype', 'custom_translator_namespace', 'custom_translator_locale', 'translation_data' );

	/**
	 * @var array
	 */
	protected $translate_properties = array('title');

	/**
	 * @var array
	 */
	protected $dojo_props_real_names_map = array();


	/**
	 * @param JetML $parser
	 * @param \DOMElement $node
	 */
	public function __construct(  JetML $parser, \DOMElement $node){
		parent::__construct( $parser, $node);

		$dojo = Mvc::requireJavascriptLib('Dojo');

		if(!$this->dojo_type) {
			return;
		}

		if(is_array($this->dojo_type)) {
			foreach($this->dojo_type as $dojo_component) {
				if(
					is_array($this->required_css) &&
					isset($this->required_css[$dojo_component])
				) {
					$dojo->requireComponent($dojo_component, array( 'css' => $this->required_css[$dojo_component] ));
				} else {
					$dojo->requireComponent($dojo_component, array());
				}
			}
		} else {
			$dojo->requireComponent($this->dojo_type, array( 'css' => $this->required_css ));
		}

	}

	/**
	 *
	 * @return \DOMElement
	 */
	public function getReplacement() {


		$dojo_props = array();

		$attributes = array();

		foreach($this->node->attributes as $attribute){
			/**
			 * @var \DOMAttr $attribute
			 */

			$a_name = $attribute->name;
			$a_value = $attribute->value;


			if( in_array($a_name, $this->internal_properties) ) {
				continue;
			}

			if( in_array($a_name, $this->translate_properties) ) {
				$a_value = $this->getTranslation( $a_value );
			}


			if(
				$this->dojo_type!==false &&
				!in_array($a_name, $this->HTML_properties)
			) {
				if($a_value=='true' || $a_value=='false') {
					$a_value = ($a_value=='true');
				}

				if(isset($this->dojo_props_real_names_map[$a_name])) {
					$a_name = $this->dojo_props_real_names_map[$a_name];
				}

				$dojo_props[$a_name] = $a_value;

				continue;
			}

			$attributes[$a_name] = $a_value;

		}


		$_dojo_props = $this->_formatDojoProps($dojo_props);

		if($this->dojo_type!==false) {
			if(is_array($this->dojo_type)) {
				$attributes[static::DOJO_TYPE_PROPERTY ] = $this->dojo_type[0];
			} else {
				$attributes[static::DOJO_TYPE_PROPERTY ] = $this->dojo_type;
			}

			$attributes[static::DOJO_PROPS_PROPERTY] = str_replace('"', '\'', implode(',', $_dojo_props));
		}


		$replacement  = $this->parser->getDOMDocument()->createElement( $this->widget_container_tag );

		$tag_content = $this->_getTagContent();

		$child_nodes = array();
		if($tag_content!==null) {
			if(!is_array($tag_content)) {
				$tag_content = array($tag_content);
			}

			$child_nodes = $tag_content;

		}
		foreach( $this->node->childNodes as $child ) {
			$child_nodes[] = $child;
		}

		foreach( $child_nodes as $child ) {
			$replacement->appendChild($child);
		}

		foreach($attributes as $a_name=>$a_value) {
			$replacement->setAttribute($a_name, $a_value);
		}

		return $replacement;

	}

	/**
	 * @param array $dojo_props
	 *
	 * @return array
	 */
	protected function _formatDojoProps( array $dojo_props ) {
		$_dojo_props = array();
		foreach( $dojo_props as $k=>$val) {
			if(
				is_string($val) &&
				isset($val[0]) &&
				$val[0]=='{'
			) {
				$_dojo_props[] = $k.':'.$val;
			} else {
				$_dojo_props[] = $k.':'.json_encode($val);
			}
		}

		return $_dojo_props;

	}

	/**
	 * @return \DOMElement|\DOMElement[]|null
	 */
	protected function _getTagContent() {
		return null;
	}
	
}