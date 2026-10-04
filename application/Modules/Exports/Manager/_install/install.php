<?php
namespace JetApplicationModule\Exports\Manager;

use Jet\DataModel_Helper;

DataModel_Helper::create( PlannedOutage::class );
DataModel_Helper::create( ExportLogger_Event::class );
