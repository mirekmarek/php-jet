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

class JetML extends Object implements Mvc_Layout_Postprocessor_Interface {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = 'JetML_Factory';
	/**
	 * @var string
	 */
	protected static $__factory_class_method = 'getJetMLPostprocessorInstance';
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = 'Jet\\JetML';

	const TAGS_PREFIX = 'jetml';

	/**
	 * @var \DOMDocument
	 */
	protected $_DOM_document;

	/**
	 * @var string
	 */
	protected $toolkit = 'Dojo';

	/**
	 * @var Mvc_UIManagerModule_Abstract
	 */
	protected $UI_manager_module;

	/**
	 * @var Mvc_Layout
	 */
	protected $layout;

	/**
	 * @var string
	 */
	protected $icons_URL = '';

	/**
	 * @var array
	 */
	protected $icon_sizes = array(
		'small' => array(
			'width' => 16,
			'height' => 16,
			'wspace' => 4,
			'hspace' => 4,
		),
		'normal' => array(
			'width' => 20,
			'height' => 20,
			'wspace' => 4,
			'hspace' => 4,
		),
		'large' => array(
			'width' => 32,
			'height' => 32,
			'wspace' => 4,
			'hspace' => 4,
		),

		'flag_small' => array(
			'width' => 16,
			'height' => 10,
			'wspace' => 4,
			'hspace' => 4,
		),
		'flag_normal' => array(
			'width' => 24,
			'height' => 15,
			'wspace' => 4,
			'hspace' => 4,
		),
		'flag_large' => array(
			'width' => 38,
			'height' => 24,
			'wspace' => 4,
			'hspace' => 4,
		)

	);

	/**
	 * @var string
	 */
	protected  $icon_default_size = 'normal';

	/**
	 * @var string
	 */
	protected  $icon_file_suffix = 'png';


	/**
	 * @var string
	 */
	protected  $flags_URL = '';


	/**
	 * @var array
	 */
	protected static $_tag_class_map_cache = array();


	/**
	 *
	 */
	public function  __construct() {
	}


	/**
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 * @param Mvc_Layout_OutputPart[] $output_parts
	 */
	public function layoutPostProcess( &$result, Mvc_Layout $layout, array $output_parts ) {
	}

	/**
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	public function finalPostProcess( &$result, Mvc_Layout $layout ){
		$this->layout = $layout;

		$result = $this->parse( $result );
	}


	/**
	 * @return Mvc_Layout
	 */
	public function getLayout() {
		return $this->layout;
	}


	/**
	 * @return string
	 */
	public function getToolkit() {
		return $this->toolkit;
	}

	/**
	 * @param string $toolkit
	 */
	public function setToolkit($toolkit) {
		$this->toolkit = $toolkit;
	}

	/**
	 * @return \DOMDocument
	 */
	public function getDOMDocument() {
		return $this->_DOM_document;
	}

	/**
	 *
	 * @param string $data
	 *
	 * @throws JetML_Exception
	 * @return string
	 */
	public function parse( $data ) {

		if( strpos($data, static::TAGS_PREFIX.'_' )===false ) {
			return $data;
		}

		$this->_DOM_document = new \DOMDocument('1.0', 'UTF-8');



		libxml_use_internal_errors(true);



		//TODO: loadHTML is so much tolerant... add some error handling ...

		//$data = \mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8');


		$this->_DOM_document->loadHTML('<?xml version="1.0" encoding="UTF-8">' . $data);

		foreach ($this->_DOM_document->childNodes as $item) {
			if( $item->nodeType==XML_PI_NODE ) {
				$this->_DOM_document->removeChild($item);
				break;
			}
		}


        //$this->_DOM_document->loadXML($data);
		$this->_DOM_document->formatOutput = true;

		/*
        $data_per_lines = explode(JET_EOL, $data);

        foreach( libxml_get_errors() as $xml_error ) {
            **
             * @var \libXMLError $xml_error
             *

            // error handling
            //var_dump($xml_error);
            //var_dump($data_per_lines[$xml_error->line-1]);
        }
		*/
		libxml_clear_errors();

		$ID_prefix = $this->layout->getUIContainerIDPrefix();

		$prefix_str_len = strlen(static::TAGS_PREFIX);


		foreach( $this->_DOM_document->getElementsByTagName('*') as $node ) {

			/**
			 * @var \DOMElement $node
			 */
			if(substr($node->tagName, 0, $prefix_str_len)!=static::TAGS_PREFIX) {
				continue;
			}

			if($ID_prefix && $node->hasAttribute('id')) {
				$node->setAttribute('id', $ID_prefix.$node->getAttribute('id') );
			}


			$tag_inst = self::getTagInstance( $node );
			$replacement = $tag_inst->getReplacement();

			$node->parentNode->replaceChild($replacement, $node);


		}

		return $this->_DOM_document->saveHTML();
	}

	/**
	 *
	 * @param \DOMElement $node
	 *
	 * @throws Exception
	 * @return JetML_Widget_Abstract
	 */
	public function getTagInstance(\DOMElement $node) {
		$tag_name = $node->tagName;

		if(!isset(static::$_tag_class_map_cache[$tag_name])) {

			$o_tag_name = $tag_name;

			$tag_name = substr($node->tagName, 6);
			$tag_name = explode('_', $tag_name);

			foreach($tag_name as $i=>$t) {
				$tag_name[$i] = ucfirst(strtolower($t));
			}
			$tag_name = implode('_', $tag_name);

			static::$_tag_class_map_cache[$o_tag_name] = $tag_name;
		} else {
			$tag_name = static::$_tag_class_map_cache[$tag_name];
		}

		return JetML_Factory::getJetMLWidgetInstance($this, $this->toolkit, $tag_name, $node);
	}



	/**
	 * Sets icons URL
	 *
	 * @param string $URL
	 */
	public function setIconsURL($URL){
		$this->icons_URL = $URL;
	}

	/**
	 * Gets icons URL
	 *
	 * @throws JetML_Exception
	 * @return string
	 */
	public function getIconsURL(){
		return $this->icons_URL;
	}

	/**
	 * @param string $icon_default_size
	 */
	public function setIconDefaultSize($icon_default_size) {
		$this->icon_default_size = $icon_default_size;
	}

	/**
	 * @return string
	 */
	public function getIconDefaultSize() {
		return $this->icon_default_size;
	}

	/**
	 * @param string $icon_file_suffix
	 */
	public function setIconFileSuffix($icon_file_suffix) {
		$this->icon_file_suffix = $icon_file_suffix;
	}

	/**
	 * @return string
	 */
	public function getIconFileSuffix() {
		return $this->icon_file_suffix;
	}

	/**
	 * @param $size_name
	 * @param $width
	 * @param $height
	 * @param $wspace
	 * @param $hspace
	 */
	public function setIconSize(
			$size_name,
			$width,
			$height,
			$wspace,
			$hspace ) {
		$this->icon_sizes[$size_name] = array(
			'width' => $width,
			'height' => $height,
			'wspace' => $wspace,
			'hspace' => $hspace,
		);
	}

	/**
	 * @param string $size_name
	 *
	 * @return array
	 */
	public function getIconSizeData( $size_name ) {
		return $this->icon_sizes[$size_name];
	}

	/**
	 * Gets flags URL
	 *
	 * @throws JetML_Exception
	 * @return string
	 */
	public function getFlagsURL(){
		return $this->flags_URL;
	}

	/**
	 * Sets flags URL
	 *
	 * @param string $URL
	 */
	public function setFlagsURL($URL){
		$this->flags_URL = $URL;
	}



}