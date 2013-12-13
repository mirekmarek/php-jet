<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Factory extends Factory {

	/**
	 * @var string
	 */
	protected static $jetml_widget_class_name_prefix = "Jet\\JetML_Widget_";

	const DEFAULT_JETML_LAYOUT_POSTPROCESSOR_CLASS_NAME = "Jet\\JetML";

	/**
	 * @param string $jetml_widget_class_name_prefix
	 */
	public static function setJetmlWidgetClassNamePrefix($jetml_widget_class_name_prefix) {
		static::$jetml_widget_class_name_prefix = $jetml_widget_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getJetmlWidgetClassNamePrefix() {
		return static::$jetml_widget_class_name_prefix;
	}


	/**
	 * Gets JetML layout postprocessor
	 *
	 * @return JetML
	 */
	public static function getJetMLPostprocessorInstance(){

		$class_name =  static::getClassName( static::DEFAULT_JETML_LAYOUT_POSTPROCESSOR_CLASS_NAME );

		$layout_postprocessor_instance = new $class_name();
		static::checkInstance(static::DEFAULT_JETML_LAYOUT_POSTPROCESSOR_CLASS_NAME, $layout_postprocessor_instance);

		return $layout_postprocessor_instance;
	}

	/**
	 * @static
	 *
	 * @param JetML $postprocessor
	 * @param string $toolkit
	 * @param $tag_name
	 * @param \DOMElement $node
	 *
	 * @throws JetML_Exception

	 * @return JetML_Widget_Abstract
	 */
	public static function getJetMLWidgetInstance(  JetML $postprocessor, $toolkit, $tag_name,\DOMElement $node) {

		$class_name =  static::getClassName( static::$jetml_widget_class_name_prefix."{$toolkit}_{$tag_name}" );

		/* @var $tag_instance JetML_Widget_Abstract */
		$tag_instance = new $class_name($postprocessor, $node);

		if (!($tag_instance instanceof JetML_Widget_Abstract)) {
			throw new JetML_Exception(
				"Class {$class_name} is not subclass of JetML_Widget_Abstract",
				JetML_Exception::CODE_INVALID_WIDGET_CLASS
			);
		}
		return $tag_instance;
	}
}