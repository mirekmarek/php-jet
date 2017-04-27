<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_RadioButton
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_RadioButton extends Form_Renderer_Bootstrap_Field_Abstract {

	/**
	 * @var string
	 */
	protected $tag = 'input';

    /**
     * @var string
     */
    protected $base_css_class = 'radio';

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @return string
	 */
	public function render()
	{
	    $result = '';

        /**
         * @var Form_Field_RadioButton $field
         */
        $field = $this->_field;

        $result .= $this->render_containerStart();

        $base_class = $this->getBaseCssClass();
        $this->setBaseCssClass('');
        foreach( $field->getSelectOptions() as $key=>$option ) {
            $tag_options = [
                'type' => 'radio',
                'name' => $this->getTagNameValue(),
                'value' => $key
            ];

            if($field->getValue()==$key) {
                $tag_options['checked'] = 'checked';
            }

            $class = $base_class;
            if($field->getIsReadonly()) {
                $tag_options['disabled'] = 'disabled';
                $class .= ' disabled';
            }

            $result .= '<div class="'.$class.'">';
            $result .= '<label>';
            $result .= $this->generate($tag_options);
            $result .= $option;
            $result .= '</label>';
            $result .= '</div>';
        }
        $this->setBaseCssClass($base_class);

        $result .= $this->render_containerEnd();

        return $result;
	}

}

