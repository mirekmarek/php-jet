<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetStudio\ModuleWizard\BasicAdminDataHandler;

use Jet\AJAX;
use Jet\Http_Request;
use JetStudio\Pages;
use JetStudio\Project;

$name = Http_Request::GET()->getString( 'name' );

$id = Project::generateIdentifier( $name, function( $id ) {
	return Pages::exists( $id );
} );

AJAX::response(
	[
		'id' => $id
	]
);
