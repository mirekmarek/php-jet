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

if( $backend->helper_tableExists( $current ) ) {
	echo implode( PHP_EOL . PHP_EOL, $backend->helper_getUpdateCommand( $current ) );
}

die();