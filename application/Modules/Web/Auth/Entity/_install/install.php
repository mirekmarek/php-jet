<?php
namespace JetApplicationModule\Web\Auth\Entity;

use Jet\DataModel_Helper;

DataModel_Helper::create( Visitor::class );
DataModel_Helper::create( Visitor_Roles::class );
DataModel_Helper::create( Role::class );
DataModel_Helper::create( Role_Privilege::class );