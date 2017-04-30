<?php
namespace JetApplicationModule\JetExample\AuthController;

use Jet\DataModel_Helper;

$event_administration = new Event_Administration();
$event_site = new Event_Site();

DataModel_Helper::drop(get_class($event_administration));
DataModel_Helper::drop(get_class($event_site));
