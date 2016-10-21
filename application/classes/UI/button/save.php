<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

class UI_button_save extends UI_button
{

    /**
     * @var string
     */
    protected $type = 'submit';

    /**
     * @var string
     */
    protected $class = 'primary';

    /**
     * @var string
     */
    protected $icon = 'floppy-disk';

    /**
     * @var string
     */
    protected $icon_class = UI::DEFAULT_ICON_CLASS;

}