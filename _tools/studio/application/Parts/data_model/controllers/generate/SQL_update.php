<?php
namespace JetStudio;

use Jet\DataModel_Backend;
use Jet\Debug;
use Jet\SysConf_Jet;

Debug::setOutputIsHTML( false );

$current = DataModels::getCurrentModel();
if(!$current) {
	die();
}

$current->prepare();

$backend = DataModel_Backend::get( $current );

if($backend->helper_tableExists($current)) {
	echo implode(SysConf_Jet::EOL().SysConf_Jet::EOL(), $backend->helper_getUpdateCommand( $current ));
} else {
	//echo $backend->helper_getCreateCommand( $current );
}

die();