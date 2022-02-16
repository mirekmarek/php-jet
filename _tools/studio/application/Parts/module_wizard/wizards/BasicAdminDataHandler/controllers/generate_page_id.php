<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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

AJAX::commonResponse(
	[
		'id' => $id
	]
);
