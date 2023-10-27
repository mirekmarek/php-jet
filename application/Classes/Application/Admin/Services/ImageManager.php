<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Form_Field;

interface Application_Admin_Services_ImageManager
{
	public function includeSelectImageDialog() : string;
	
	public function renderSelectImageWidget( Form_Field $form_field ) : string;
}