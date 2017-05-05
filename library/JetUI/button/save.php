<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

/**
 * Class button_save
 * @package JetUI
 */
class button_save extends button
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
	protected $icon = 'save';

	/**
	 * @var string
	 */
	protected $icon_class = UI::DEFAULT_ICON_CLASS;

}