<?php
/**
 *
 *
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
 * @package JetML
 */
namespace Jet;

class JetML_Factory {

	/**
	 * Gets JetML layout postprocessor
	 *
	 * @return JetML
	 */
	public static function getJetMLPostprocessorInstance(){

		$class_name =  JET_JETML_LAYOUT_POSTPROCESSOR_CLASS;

		return new $class_name();
	}

	/**
	 * @static
	 *
	 * @param JetML $postprocessor
	 * @param $tag_name
	 * @param \DOMElement $node
	 *
	 * @throws JetML_Exception

	 * @return JetML_Widget_Abstract
	 */
	public static function getJetMLWidgetInstance(  JetML $postprocessor, $tag_name,\DOMElement $node) {

		$class_name =  JET_JETML_WIDGET_CLASS_NAME_PREFIX.$tag_name;

		/* @var $tag_instance JetML_Widget_Abstract */
		$tag_instance = new $class_name($postprocessor, $node);

		if (!($tag_instance instanceof JetML_Widget_Abstract)) {
			throw new JetML_Exception(
				'Class '.$class_name.' is not subclass of JetML_Widget_Abstract',
				JetML_Exception::CODE_INVALID_WIDGET_CLASS
			);
		}
		return $tag_instance;
	}
}