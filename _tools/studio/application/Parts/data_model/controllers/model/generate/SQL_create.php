<?php

namespace JetStudio;

use Jet\DataModel_Backend;
use Jet\Debug;

Debug::setOutputIsHTML( false );

$current = DataModels::getCurrentModel();
if( !$current ) {
	die();
}

$current->prepare();

$backend = DataModel_Backend::get( $current );

echo $backend->helper_getCreateCommand( $current );

die();